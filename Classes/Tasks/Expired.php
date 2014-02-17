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
 * Expired scheduler task
 *
 * @package TYPO3
 * @subpackage tablecleaner
 * @version $Id:$
 * @license http://opensource.org/licenses/gpl-license.php
 *    GNU Public License, version 2
 */
class tx_tablecleaner_tasks_Expired extends tx_tablecleaner_tasks_Base {

	/**
	 * Function executed from the Scheduler.
	 *
	 * @return   boolean
	 */
	public function execute() {
		$successfullyExecuted = TRUE;
		$timestamp = strtotime('-' . intval($this->dayLimit) . 'days');
		$excludePages = Tx_Tablecleaner_Utility_Base::fetchExcludedPages();
		$tablesWithPid = Tx_Tablecleaner_Utility_Base::getTablesWithPid();

		foreach ($this->tables as $table) {
			if (in_array($table, $tablesWithPid) AND count($excludePages)) {
				if ($table == 'pages') {
					$where = 'tstamp < ' . $timestamp .
						' AND NOT uid IN(' . implode(',', $excludePages) . ')';
				} else {
					$where = 'tstamp < ' . $timestamp .
						' AND NOT pid IN(' . implode(',', $excludePages) . ')';
				}
			} else {
				$where = ' tstamp < ' . $timestamp;
			}
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
		$string = $GLOBALS['LANG']->sL(
			'LLL:EXT:tablecleaner/Resources/Private/Language/locallang.xml:tasks.expired.additionalInformation'
		);
		$message = sprintf($string, intval($this->dayLimit), implode(', ', $this->tables));
		return $message;
	}
}

if (defined('TYPO3_MODE')
	&& isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/tablecleaner/Classes/Tasks/Expired.php'])
) {
	require_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/tablecleaner/Classes/Tasks/Expired.php']);
}

?>