<?php
/*****************************************************************************
 *  Copyright notice
 *
 *  (c) 2015 Michael Stucki <michael.stucki@typo3.org>
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
 * Mark records as deleted if their pid is a number (>0) and does not point to a deleted page
 *
 * @package TYPO3
 * @subpackage tablecleaner
 * @version $Id:$
 * @license http://opensource.org/licenses/gpl-license.php
 *    GNU Public License, version 2
 */
class tx_tablecleaner_tasks_MarkLostRecords extends tx_tablecleaner_tasks_Base {

	/**
	 * Function executed from the Scheduler.
	 *
	 * @return boolean
	 */
	public function execute() {
		$successfullyExecuted = TRUE;

		$maximumLoops = 1000;
		$numberOfAffectedRecords_lastRun = 0;

		// Mark all pages as deleted=1 if their pid is deleted or does not exist
		// This will run recursive by repeating the same process up to $maximumLoops times.
		$loopCount = 0;
		while (true) {
			$numberOfAffectedRecords = $this->markRecordsAsDeletedIfPidIsDeletedOrMissing('pages');

			if ($numberOfAffectedRecords === 0 || $numberOfAffectedRecords === $numberOfAffectedRecords_lastRun) {
				// We're done
				echo 'Recursion completed. Leaving loop...' . chr(10);
				break;
			}
			$numberOfAffectedRecords_lastRun = $numberOfAffectedRecords;

			if ($loopCount >= $maximumLoops) {
				echo 'WARNING: Maximum number of loops reached. Aborting loop...' . chr(10);
				return FALSE;
			}

			$loopCount++;
		}

		foreach ($this->tables as $table) {
			if ($table === 'pages') {
				// Skip pages, they were already processed above
				continue;
			}

			// Mark all records as deleted=1 if their pid is deleted or does not exist
			$this->markRecordsAsDeletedIfPidIsDeletedOrMissing($table);
		}
		return $successfullyExecuted;
	}

	/**
	 * Mark records as deleted if their pid is deleted or does not exist.
	 *
	 * @param string Table name
	 * @return int Number of affected records
	 */
	function markRecordsAsDeletedIfPidIsDeletedOrMissing($table) {
		$uidList = array();
		$affectedRows = 0;

		// Fetch all records whose parent page is missing
		$res = $GLOBALS['TYPO3_DB']->sql_query('
			SELECT
				' . $table . '.uid uid
			FROM
				' . $table . '
			LEFT JOIN
				pages parentpage ON ' . $table . '.pid=parentpage.uid
			WHERE
				' . $table . '.pid>0 AND (parentpage.deleted=1 OR parentpage.uid IS NULL);
			');

		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$uidList[] = $row['uid'];
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($res);

		if (count($uidList)) {
			$where = 'uid IN (' . implode(',', $uidList) . ')';
			$GLOBALS['TYPO3_DB']->exec_UPDATEquery($table, $where, array('deleted' => 1));

			$affectedRows = $GLOBALS['TYPO3_DB']->sql_affected_rows();
			echo 'FINISHED ' . $table . ': Affected records: ' . $affectedRows . chr(10);
		}

		return $affectedRows;
	}

	/**
	 * Returns some additional information about indexing progress, shown in
	 * the scheduler's task overview list.
	 *
	 * @return string Information to display
	 */
	public function getAdditionalInformation() {
		$string = $GLOBALS['LANG']->sL(
			'LLL:EXT:tablecleaner/Resources/Private/Language/locallang.xml:tasks.markLostRecords.additionalInformation'
		);
		return sprintf($string, (int)$this->dayLimit, implode(', ', $this->tables));
	}
}

if (defined('TYPO3_MODE')
	&& isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/tablecleaner/Classes/Tasks/MarkLostRecords.php'])
) {
	require_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/tablecleaner/Classes/Tasks/MarkLostRecords.php']);
}

?>