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
 * Clean scheduler task
 *
 * @package TYPO3
 * @subpackage tablecleaner
 * @version $Id:$
 * @license http://opensource.org/licenses/gpl-license.php
 *    GNU Public License, version 2
 */
class tx_tablecleaner_tasks_Clean extends tx_scheduler_Task {

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
	 * Get the value of the protected property tables.
	 *
	 * @return string  Comma separated list of tables
	 */
	public function getTables() {
		return $this->tables;
	}

	/**
	 * Set the value of the private property tables.
	 *
	 * @param string $tables Comma separated list of tables
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
	 * Function executed from the Scheduler.
	 *
	 * @return   boolean
	 */
	public function execute() {
		$successfullyExecuted = TRUE;
		foreach ($this->tables as $table) {
			$deleteTimestamp = strtotime('-' . intval($this->dayLimit) . 'days');
			$where = 'deleted = 1 AND tstamp < ' . $deleteTimestamp;
			$GLOBALS['TYPO3_DB']->exec_DELETEquery($table, $where);
			$error = $GLOBALS['TYPO3_DB']->sql_error();
			if ($error) {
				$successfullyExecuted = FALSE;
			}
		}
		return $successfullyExecuted;
	}

	/**
	 * Returns some additional information about indexing progress, shown in
	 * the scheduler's task overview list.
	 *
	 * @return   string   Information to display
	 */
	public function getAdditionalInformation() {
		$string = $GLOBALS['LANG']->sL('LLL:EXT:tablecleaner/Resources/Private/Language/locallang.xml:tasks.clean.additionalInformation');
		return sprintf($string, intval($this->dayLimit), implode(', ', $this->tables));
	}

}

if (defined('TYPO3_MODE')
	&& isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/tablecleaner/Classes/Tasks/Clean.php'])) {
	require_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/tablecleaner/Classes/Tasks/Clean.php']);
}

?>
