<?php

	/**
	 * Issue <-> custom fields relations table
	 *
	 * @author Daniel Andre Eikeland <zegenie@zegeniestudios.net>
	 * @version 2.0
	 * @license http://www.opensource.org/licenses/mozilla1.1.php Mozilla Public License 1.1 (MPL 1.1)
	 * @package thebuggenie
	 * @subpackage tables
	 */

	/**
	 * Issue <-> custom fields relations table
	 *
	 * @package thebuggenie
	 * @subpackage tables
	 */
	class B2tIssueCustomFields extends B2DBTable
	{

		const B2DBNAME = 'issuecustomfields';
		const ID = 'issuecustomfields.id';
		const SCOPE = 'issuecustomfields.scope';
		const ISSUE_ID = 'issuecustomfields.issue_id';
		const OPTION_VALUE = 'issuecustomfields.option_value';
		const CUSTOMFIELDS_ID = 'issuecustomfields.customfields_id';

		public function __construct()
		{
			parent::__construct(self::B2DBNAME, self::ID);
			parent::_addForeignKeyColumn(self::ISSUE_ID, B2DB::getTable('B2tIssues'), B2tIssues::ID);
			parent::_addForeignKeyColumn(self::CUSTOMFIELDS_ID, B2DB::getTable('B2tCustomFields'), B2tCustomFields::ID);
			parent::_addForeignKeyColumn(self::OPTION_VALUE, B2DB::getTable('B2tCustomFieldOptions'), B2tCustomFieldOptions::ID);
			parent::_addForeignKeyColumn(self::SCOPE, B2DB::getTable('B2tScopes'), B2tScopes::ID);
		}

		public function getAllValuesByIssueID($issue_id)
		{
			$crit = $this->getCriteria();
			$crit->addWhere(self::ISSUE_ID, $issue_id);
			$crit->addWhere(self::SCOPE, TBGContext::getScope()->getID());

			$res = $this->doSelect($crit);

			return $res;
		}

		public function getRowByCustomFieldIDandIssueID($customdatatype_id, $issue_id)
		{
			$crit = $this->getCriteria();
			$crit->addWhere(self::ISSUE_ID, $issue_id);
			$crit->addWhere(self::CUSTOMFIELDS_ID, $customdatatype_id);
			$crit->addWhere(self::SCOPE, TBGContext::getScope()->getID());

			$row = $this->doSelectOne($crit);

			return $row;
		}

		public function saveIssueCustomFieldValue($option_id, $customdatatype_id, $issue_id)
		{
			$crit = $this->getCriteria();
			if ($row = $this->getRowByCustomFieldIDandIssueID($customdatatype_id, $issue_id))
			{
				if ($option_id === null)
				{
					$this->doDeleteById($row->get(self::ID));
				}
				else
				{
					$crit->addUpdate(self::OPTION_VALUE, $option_id);
					$res = $this->doUpdateById($crit, $row->get(self::ID));
				}
			}
			elseif ($option_id !== null)
			{
				$crit->addInsert(self::ISSUE_ID, $issue_id);
				$crit->addInsert(self::OPTION_VALUE, $option_id);
				$crit->addInsert(self::CUSTOMFIELDS_ID, $customdatatype_id);
				$crit->addInsert(self::SCOPE, TBGContext::getScope()->getID());
				$res = $this->doInsert($crit);
			}
		}

	}
