<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Michiel Roos <extenstions@donationbasedhosting.org>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Base scheduler task
 *
 * @package TYPO3
 * @subpackage tablecleaner
 * @version $Id:$
 * @license http://opensource.org/licenses/gpl-license.php
 *    GNU Public License, version 2
 */
class tx_tablecleaner_tasks_Base extends tx_scheduler_Task {

	/**
	 * Array of tables
	 *
	 * @var array
	 */
	protected $tables;

	/**
	 * Days
	 *
	 * @var integer
	 */
	protected $dayLimit;

	/**
	 * Exclude page id's
	 *
	 * @var string
	 */
	protected $excludePages;

	/**
	 * Exclude page id's recursively
	 *
	 * @var boolean
	 */
	protected $excludePagesRecursive;

	/**
	 * Get the value of the protected property tables.
	 *
	 * @return array of tables
	 */
	public function getTables() {
		return $this->tables;
	}

	/**
	 * Set the value of the private property tables.
	 *
	 * @param array of tables
	 * @return void
	 */
	public function setTables($tables) {
		$this->tables = $tables;
	}

	/**
	 * Get the value of the protected property dayLimit.
	 *
	 * @return integer dayLimit
	 */
	public function getDayLimit() {
		return $this->dayLimit;
	}

	/**
	 * Set the value of the private property dayLimit.
	 *
	 * @param integer $dayLimit Number of days after which to remove the records
	 * @return void
	 */
	public function setDayLimit($dayLimit) {
		$this->dayLimit = $dayLimit;
	}

	/**
	 * Get a list of page id's to exclude from deletion
	 *
	 * @return string
	 */
	public function getExcludePages() {
		return $this->excludePages;
	}

	/**
	 * Set a list of page id's to exclude from deletion
	 *
	 * @param string $excludePages
	 * @return $this to allow for chaining
	 */
	public function setExcludePages($excludePages) {
		$this->excludePages = $excludePages;
		return $this;
	}

	/**
	 * Should the given page id's be excluded recursively?
	 *
	 * @return boolean
	 */
	public function getExcludePagesRecursive() {
		return $this->excludePagesRecursive;
	}

	/**
	 * Should the given page id's be excluded recursively?
	 *
	 * @param boolean $excludePagesRecursive
	 * @return $this to allow for chaining
	 */
	public function setExcludePagesRecursive($excludePagesRecursive) {
		$this->excludePagesRecursive = $excludePagesRecursive;
		return $this;
	}

	/**
	 * Get all tables with a parent id
	 *
	 * @return array $tables  The tables
	 */
	protected function getTablesWithPid() {
		$tables = array();
		$resource = $GLOBALS['TYPO3_DB']->sql_query(
			"SELECT TABLE_NAME
			FROM INFORMATION_SCHEMA.COLUMNS
			WHERE TABLE_NAME
			IN ('" . implode ("','", $this->tables) . "')
			AND COLUMN_NAME = 'pid'
			AND TABLE_SCHEMA =  '" . TYPO3_db . "'"
		);
		if (is_resource($resource)) {
			while ($result = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resource)) {
				$tables[] = $result['TABLE_NAME'];
			};
		}
		return $tables;
	}

	/**
	 * Fetch child pages
	 *
	 * @param integer $pageId
	 * @return array $pageIds
	 */
	protected function fetchChildPages($pageId) {
		$res = $GLOBALS['TYPO3_DB']->sql_query('SELECT uid FROM pages WHERE pid = ' . $pageId);
		$pageIds = array();
		$pageIds[] = $pageId;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$pageIds[] = $row['uid'];
			$pageIds = array_merge($pageIds, $this->fetchChildPages($row['uid']));
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($res);
		return $pageIds;
	}

	/**
	 * This is the main method that is called when a task is executed
	 * It MUST be implemented by all classes inheriting from this one
	 * Note that there is no error handling, errors and failures are expected
	 * to be handled and logged by the client implementations.
	 * Should return true on successful execution, false on error.
	 *
	 * @return boolean   Returns true on successful execution, false on error
	 */
	public function execute() {
		// TODO: Implement execute() method.
	}
}

if (defined('TYPO3_MODE')
	&& isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/tablecleaner/Classes/Tasks/Base.php'])) {
	require_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/tablecleaner/Classes/Tasks/Base.php']);
}

?>