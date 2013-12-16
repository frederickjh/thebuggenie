<nav class="tab_menu header_menu<?php if (TBGContext::isProjectContext()): ?> project_context<?php endif; ?>" id="main_menu">
	<ul>
		<?php if (!TBGSettings::isSingleProjectTracker() && !TBGContext::isProjectContext()): ?>
			<li<?php if ($tbg_response->getPage() == 'home'): ?> class="selected"<?php endif; ?>><div class="menuitem_container"><?php echo link_tag(make_url('home'), image_tag('tab_index.png').__('Frontpage')); ?></div></li>
		<?php elseif (TBGContext::isProjectContext()): ?>
			<li<?php if (in_array($tbg_response->getPage(), array('project_dashboard', 'project_planning', 'project_scrum', 'project_scrum_sprint_details', 'project_timeline', 'project_team', 'project_roadmap', 'project_statistics', 'vcs_commitspage'))): ?> class="selected"<?php endif; ?>>
				<div class="menuitem_container">
					<?php echo link_tag(make_url('project_dashboard', array('project_key' => TBGContext::getCurrentProject()->getKey())), image_tag('icon_dashboard_small.png').__('Summary')); ?>
					<?php echo javascript_link_tag(image_tag('tabmenu_dropdown.png', array('class' => 'menu_dropdown')), array('onmouseover' => "")); ?>
				</div>
				<div id="project_information_menu" class="tab_menu_dropdown">
					<?php include_template('project/projectinfolinks', array('submenu' => true)); ?>
				</div>
			</li>
		<?php endif; ?>
		<?php if (!$tbg_user->isThisGuest() && !TBGSettings::isSingleProjectTracker() && !TBGContext::isProjectContext()): ?>
			<li<?php if ($tbg_response->getPage() == 'dashboard'): ?> class="selected"<?php endif; ?>><div class="menuitem_container"><?php echo link_tag(make_url('dashboard'), image_tag('icon_dashboard_small.png').__('Dashboard')); ?></div></li>
		<?php endif; ?>
		<?php if (TBGContext::isProjectContext() && $tbg_user->canSearchForIssues()): ?>
			<li<?php if (in_array($tbg_response->getPage(), array('project_issues', 'viewissue'))): ?> class="selected"<?php endif; ?>>
				<div class="menuitem_container">
					<?php echo link_tag(make_url('project_issues', array('project_key' => TBGContext::getCurrentProject()->getKey())), image_tag('tab_search.png').__('Issues')); ?>
					<?php if (TBGContext::isProjectContext()): ?>
						<?php echo javascript_link_tag(image_tag('tabmenu_dropdown.png', array('class' => 'menu_dropdown')), array('onmouseover' => "")); ?>
					<?php endif; ?>
				</div>
				<div id="issues_menu" class="tab_menu_dropdown">
					<?php echo link_tag(make_url('project_open_issues', array('project_key' => TBGContext::getCurrentProject()->getKey())), image_tag('icon_savedsearch.png') . __('Open issues for this project')); ?>
					<?php echo link_tag(make_url('project_closed_issues', array('project_key' => TBGContext::getCurrentProject()->getKey())), image_tag('icon_savedsearch.png') . __('Closed issues for this project')); ?>
					<?php echo link_tag(make_url('project_wishlist_issues', array('project_key' => TBGContext::getCurrentProject()->getKey())), image_tag('icon_savedsearch.png') . __('Wishlist for this project')); ?>
					<?php echo link_tag(make_url('project_milestone_todo_list', array('project_key' => TBGContext::getCurrentProject()->getKey())), image_tag('icon_savedsearch.png') . __('Milestone todo-list for this project')); ?>
					<?php echo link_tag(make_url('project_most_voted_issues', array('project_key' => TBGContext::getCurrentProject()->getKey())), image_tag('icon_savedsearch.png') . __('Most voted for issues')); ?>
					<?php echo link_tag(make_url('project_month_issues', array('project_key' => TBGContext::getCurrentProject()->getKey())), image_tag('icon_savedsearch.png') . __('Issues reported this month')); ?>
					<?php echo link_tag(make_url('project_last_issues', array('project_key' => TBGContext::getCurrentProject()->getKey(), 'units' => 30, 'time_unit' => 'days')), image_tag('icon_savedsearch.png') . __('Issues reported last 30 days')); ?>
					<div class="header"><?php echo __('Recently watched issues'); ?></div>
					<?php if (array_key_exists('viewissue_list', $_SESSION) && is_array($_SESSION['viewissue_list'])): ?>
						<?php foreach ($_SESSION['viewissue_list'] as $k => $i_id): ?>
						<?php 
							try
							{
								$an_issue = TBGIssuesTable::getTable()->getIssueById($i_id);
								if (!$an_issue instanceof TBGIssue)
								{
									unset($_SESSION['viewissue_list'][$k]);
									continue;
								}
							}
							catch (Exception $e) { unset($_SESSION['viewissue_list'][$k]); }
							echo link_tag(make_url('viewissue', array('project_key' => $an_issue->getProject()->getKey(), 'issue_no' => $an_issue->getFormattedIssueNo())), $an_issue->getFormattedTitle(true, false), array('title' => $an_issue->getFormattedTitle(true, true)));
							?>
						<?php endforeach; ?>
					<?php endif; ?>
					<?php if (!isset($an_issue)): ?>
						<a href="javascript:void(0);"><?php echo __('No recent issues'); ?></a>
					<?php endif; ?>
				</div>
			</li>
		<?php endif; ?>
		<?php if (!TBGContext::isProjectContext() && ($tbg_user->hasPageAccess('teamlist') || count($tbg_user->getTeams())) && !is_null(TBGTeamsTable::getTable()->getAll())): ?>
			<li<?php if ($tbg_response->getPage() == 'team'): ?> class="selected"<?php endif; ?>>
				<div class="menuitem_container">
					<?php echo link_tag('javascript:void(0)', image_tag('tab_teams.png') . __('Teams'), array('class' => 'not_clickable')); ?>
					<?php echo javascript_link_tag(image_tag('tabmenu_dropdown.png', array('class' => 'menu_dropdown')), array('onmouseover' => "")); ?>
				</div>
				<div id="team_menu" class="tab_menu_dropdown">
					<?php foreach (TBGTeam::getAll() as $team): ?>
						<?php if (!$team->hasAccess()) continue; ?>
						<?php echo link_tag(make_url('team_dashboard', array('team_id' => $team->getID())), image_tag('tab_teams.png' ) . $team->getName()); ?>
					<?php endforeach;?>
				</div>
			</li>
		<?php endif; ?>
		<?php if (!TBGContext::isProjectContext() && $tbg_user->hasPageAccess('clientlist') && count($tbg_user->getClients()) && !is_null(TBGClient::getAll())): ?>
			<li<?php if ($tbg_response->getPage() == 'client'): ?> class="selected"<?php endif; ?>>
				<div class="menuitem_container">
					<?php echo link_tag('javascript:void(0)', image_tag('tab_clients.png') . __('Clients'), array('class' => 'not_clickable')); ?>
					<?php echo javascript_link_tag(image_tag('tabmenu_dropdown.png', array('class' => 'menu_dropdown')), array('onmouseover' => "")); ?>
				</div>
				<div id="client_menu" class="tab_menu_dropdown">
					<?php foreach (TBGClient::getAll() as $client): ?>
						<?php if (!$client->hasAccess()) continue; ?>
						<?php echo link_tag(make_url('client_dashboard', array('client_id' => $client->getID())), image_tag('tab_clients.png' ) . $client->getName()); ?>
					<?php endforeach;?>
				</div>
			</li>
		<?php endif; ?>
		<?php TBGEvent::createNew('core', 'menustrip_item_links', null, array('selected_tab' => $tbg_response->getPage()))->trigger(); ?>
	</ul>
	<?php if (TBGContext::isProjectContext() && !TBGContext::getCurrentProject()->isArchived() && !TBGContext::getCurrentProject()->isLocked() && ($tbg_user->canReportIssues() || $tbg_user->canReportIssues(TBGContext::getCurrentProject()->getID()))): ?>
		<div class="reportissue_button_container">
			<?php echo javascript_link_tag(image_tag('icon-mono-add.png').__('Report an issue'), array('onclick' => "TBG.Main.Helpers.Backdrop.show('".make_url('get_partial_for_backdrop', array('key' => 'reportissue', 'project_id' => TBGContext::getCurrentProject()->getId()))."');", 'class' => 'button button-lightblue')); ?>
		</div>
	<?php endif; ?>
	<?php TBGEvent::createNew('core', 'before_header_userinfo')->trigger(); ?>
</nav>
