<?php

	/**
	 * Visible milestones table
	 *
	 * @author Daniel Andre Eikeland <zegenie@zegeniestudios.net>
	 * @version 2.0
	 * @license http://www.opensource.org/licenses/mozilla1.1.php Mozilla Public License 1.1 (MPL 1.1)
	 * @package thebuggenie
	 * @subpackage tables
	 */

	/**
	 * Visible milestones table
	 *
	 * @package thebuggenie
	 * @subpackage tables
	 */
	class B2tVisibleMilestones extends B2DBTable 
	{

		const B2DBNAME = 'visible_milestones';
		const ID = 'visible_milestones.id';
		const SCOPE = 'visible_milestones.scope';
		const PROJECT_ID = 'visible_milestones.project_id';
		const MILESTONE_ID = 'visible_milestones.milestone_id';
		
		public function __construct()
		{
			parent::__construct(self::B2DBNAME, self::ID);
			parent::_addForeignKeyColumn(self::MILESTONE_ID, B2DB::getTable('B2tMilestones'), B2tMilestones::ID);
			parent::_addForeignKeyColumn(self::PROJECT_ID, B2DB::getTable('B2tProjects'), B2tProjects::ID);
			parent::_addForeignKeyColumn(self::SCOPE, B2DB::getTable('B2tScopes'), B2tScopes::ID);
		}
		
		public function getAllByProjectID($project_id)
		{
			$milestones = array();
			$crit = $this->getCriteria();
			$crit->addWhere(self::PROJECT_ID, $project_id);
			$crit->addOrderBy(B2tMilestones::SCHEDULED, B2DBCriteria::SORT_ASC);
			$res = $this->doSelect($crit);
			return $res;
		}
		
		public function clearByProjectID($project_id)
		{
			$crit = $this->getCriteria();
			$crit->addWhere(self::PROJECT_ID, $project_id);
			$this->doDelete($crit);
			return true;
		}
		
		public function addByProjectIDAndMilestoneID($project_id, $milestone_id)
		{
			$crit = $this->getCriteria();
			$crit->addInsert(self::PROJECT_ID, $project_id);
			$crit->addInsert(self::MILESTONE_ID, $milestone_id);
			$crit->addInsert(self::SCOPE, TBGContext::getScope()->getID());
			$res = $this->doInsert($crit);
			return true;
		}
		
	}
