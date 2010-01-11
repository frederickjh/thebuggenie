<?php

	/**
	 * Issue affects edition table
	 *
	 * @author Daniel Andre Eikeland <zegenie@zegeniestudios.net>
	 * @version 2.0
	 * @license http://www.opensource.org/licenses/mozilla1.1.php Mozilla Public License 1.1 (MPL 1.1)
	 * @package thebuggenie
	 * @subpackage tables
	 */

	/**
	 * Issue affects edition table
	 *
	 * @package thebuggenie
	 * @subpackage tables
	 */
	class B2tIssueAffectsEdition extends B2DBTable 
	{

		const B2DBNAME = 'issueaffectsedition';
		const ID = 'issueaffectsedition.id';
		const SCOPE = 'issueaffectsedition.scope';
		const ISSUE = 'issueaffectsedition.issue';
		const EDITION = 'issueaffectsedition.edition';
		const CONFIRMED = 'issueaffectsedition.confirmed';
		const STATUS = 'issueaffectsedition.status';
		
		public function __construct()
		{
			parent::__construct(self::B2DBNAME, self::ID);
			parent::_addBoolean(self::CONFIRMED);
			parent::_addForeignKeyColumn(self::EDITION, B2DB::getTable('B2tEditions'), B2tEditions::ID);
			parent::_addForeignKeyColumn(self::ISSUE, B2DB::getTable('B2tIssues'), B2tIssues::ID);
			parent::_addForeignKeyColumn(self::SCOPE, B2DB::getTable('B2tScopes'), B2tScopes::ID);
			parent::_addForeignKeyColumn(self::STATUS, B2DB::getTable('B2tListTypes'), B2tListTypes::ID);
		}
		
		public function getOpenAffectedIssuesByEditionID($edition_id, $limit_status, $limit_category, $limit_issuetype)
		{
			$crit = $this->getCriteria();
			if ($limit_status)
			{
				$crit->addWhere(B2tIssues::STATUS, $limit_status);
			}
			if ($limit_category)
			{
				$crit->addWhere(B2tIssues::CATEGORY, $limit_category);
			}
			if ($limit_issuetype)
			{
				$crit->addWhere(B2tIssues::ISSUE_TYPE, $limit_issuetype);
			}
			$crit->addWhere(B2tIssues::STATE, TBGIssue::STATE_OPEN);
			$crit->addWhere(self::EDITION, $edition_id);
			$res = $this->doSelect($crit);
			return $res;
		}
		
		public function getByIssueID($issue_id)
		{
			$crit = $this->getCriteria();
			$crit->addWhere(self::ISSUE, $issue_id);
			$res = $this->doSelect($crit);
			return $res;
		}
		
		public function getByIssueIDandEditionID($issue_id, $edition_id)
		{
			$crit = $this->getCriteria();
			$crit->addWhere(self::EDITION, $edition_id);
			$crit->addWhere(self::ISSUE, $issue_id);
			$res = $this->doSelectOne($crit);
			return $res;
		}
		
		public function setIssueAffected($issue_id, $edition_id)
		{
			if (!$this->getByIssueIDandEditionID($issue_id, $edition_id))
			{
				$crit = $this->getCriteria();
				$crit->addInsert(self::ISSUE, $issue_id);
				$crit->addInsert(self::EDITION, $edition_id);
				$crit->addInsert(self::SCOPE, TBGContext::getScope()->getID());
				$this->doInsert($crit);
				return true;
			}
			else
			{
				return false;
			}
		}

		public function deleteByIssueIDandEditionID($issue_id, $edition_id)
		{
			if (!$this->getByIssueIDandEditionID($issue_id, $edition_id))
			{
				return false;
			}
			else
			{
				$crit = $this->getCriteria();
				$crit->addWhere(self::ISSUE, $issue_id);
				$crit->addWhere(self::EDITION, $edition_id);
				$this->doDelete($crit);
				return true;
			}
		}
		
		public function confirmByIssueIDandEditionID($issue_id, $edition_id, $confirmed = true)
		{
			if (!$this->getByIssueIDandEditionID($issue_id, $edition_id))
			{
				return false;
			}
			else
			{
				$crit = $this->getCriteria();
				$crit->addUpdate(self::CONFIRMED, $confirmed);
				$this->doUpdateById($crit, $res->get(self::ID));
			}				
		}
		
		public function setStatusByIssueIDandEditionID($issue_id, $edition_id, $status_id)
		{
			if (!$this->getByIssueIDandEditionID($issue_id, $edition_id))
			{
				return false;
			}
			else
			{
				$crit = $this->getCriteria();
				$crit->addUpdate(self::STATUS, $status);
				$this->doUpdateById($crit, $res->get(self::ID));
			}				
		}
		
	}
