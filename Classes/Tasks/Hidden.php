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
 * Hidden scheduler task
 *
 * @package TYPO3
 * @subpackage tablecleaner
 * @version $Id:$
 * @license http://opensource.org/licenses/gpl-license.php
 *    GNU Public License, version 2
 */
class tx_tablecleaner_tasks_Hidden extends tx_tablecleaner_tasks_Base {

	/**
	 * Function executed from the Scheduler.
	 *
	 * @return   boolean
	 */
	public function execute() {
		$successfullyExecuted = TRUE;
		$timestamp = strtotime('-' . (int)$this->dayLimit . 'days');
		$markAsDeleted = $this->markAsDeleted;
		$excludePages = Tx_Tablecleaner_Utility_Base::fetchExcludedPages();
		$tablesWithPid = Tx_Tablecleaner_Utility_Base::getTablesWithPid();
		$tablesWithDeleted = Tx_Tablecleaner_Utility_Base::getTablesWithDeletedAndTstamp();

		foreach ($this->tables as $table) {
			$where = 'hidden = 1 AND tstamp < ' . $timestamp;
			if (!empty($excludePages) && in_array($table, $tablesWithPid)) {
				if ($table === 'pages') {
					$where .= ' AND NOT uid IN(' . implode(',', $excludePages) . ')';
				} else {
					$where .= ' AND NOT pid IN(' . implode(',', $excludePages) . ')';
				}
			}
			if ($markAsDeleted && in_array($table, $tablesWithDeleted)) {
				$fieldValues = array (
					'tstamp' => $_SERVER['REQUEST_TIME'],
					'deleted' => 1
				);
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery($table, $where, $fieldValues);
			} else {
				$GLOBALS['TYPO3_DB']->exec_DELETEquery($table, $where);
				$error = $GLOBALS['TYPO3_DB']->sql_error();
				if (!$error && $this->optimizeOption) {
					$GLOBALS['TYPO3_DB']->sql_query('OPTIMIZE TABLE ' . $table);
				}
			}
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
			'LLL:EXT:tablecleaner/Resources/Private/Language/locallang.xml:tasks.hidden.additionalInformation'
		);
		$message = sprintf($string, (int)$this->dayLimit, implode(', ', $this->tables));
		return $message;
	}
}

if (defined('TYPO3_MODE')
	&& isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/tablecleaner/Classes/Tasks/Hidden.php'])
) {
	require_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/tablecleaner/Classes/Tasks/Hidden.php']);
}
?>