<?php
/*****************************************************************************
 *  Copyright notice
 *
 *  ⓒ 2013 Michiel Roos <michiel@maxserv.nl>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is free
 *  software; you can redistribute it and/or modify it under the terms of the
 *  GNU General Public License as published by the Free Software Foundation;
 *  either version 2 of the License, or (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful, but
 *  WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY
 *  or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
 *  more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ****************************************************************************/

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
	 * Optimize table option
	 *
	 * @var boolean
	 */
	protected $optimizeOption;

	/**
	 * Mark as deleted
	 *
	 * @var boolean
	 */
	protected $markAsDeleted;

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
	 * @param array $tables
	 *
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
	 *
	 * @return void
	 */
	public function setDayLimit($dayLimit) {
		$this->dayLimit = $dayLimit;
	}

	/**
	 * Get the value of the protected property optimizeOption.
	 *
	 * @return integer optimizeOption
	 */
	public function getOptimizeOption() {
		return $this->optimizeOption;
	}

	/**
	 * Set the value of the private property optimizeOption.
	 *
	 * @param integer $optimizeOption Number of days after which to remove the records
	 *
	 * @return void
	 */
	public function setOptimizeOption($optimizeOption) {
		$this->optimizeOption = $optimizeOption;
	}

	/**
	 * @param boolean $markAsDeleted
	 *
	 * @return $this to allow for chaining
	 */
	public function setMarkAsDeleted($markAsDeleted) {
		$this->markAsDeleted = $markAsDeleted;
		return $this;
	}

	/**
	 * @return boolean
	 */
	public function getMarkAsDeleted() {
		return $this->markAsDeleted;
	}

	/**
	 * Get the where clause
	 *
	 * @param $table
	 *
	 * @return string
	 */
	public function getWhereClause($table) {
		$excludePages = Tx_Tablecleaner_Utility_Base::fetchExcludedPages();
		$where = ' tstamp < ' . strtotime('-' . (int)$this->dayLimit . 'days');
		if (!empty($excludePages) && in_array($table, Tx_Tablecleaner_Utility_Base::getTablesWithPid())) {
			if ($table === 'pages') {
				$where .= ' AND NOT uid IN(' . implode(',', $excludePages) . ')';
			} else {
				$where .= ' AND NOT pid IN(' . implode(',', $excludePages) . ')';
			}
		}
		return $where;
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
	&& isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/tablecleaner/Classes/Tasks/Base.php'])
) {
	require_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/tablecleaner/Classes/Tasks/Base.php']);
}

?>