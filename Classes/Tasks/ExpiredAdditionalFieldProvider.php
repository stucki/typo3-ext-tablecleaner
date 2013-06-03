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
 * Additional field provider for the Expired scheduler task
 *
 * @package TYPO3
 * @subpackage tablecleaner
 * @version $Id:$
 * @license http://opensource.org/licenses/gpl-license.php
 *    GNU Public License, version 2
 */
class tx_tablecleaner_tasks_ExpiredAdditionalFieldProvider implements tx_scheduler_AdditionalFieldProvider {

	/**
	 * Render additional information fields within the scheduler backend.
	 *
	 * @param  array  $taskInfo
	 * @param  task  $task: task object
	 * @param  tx_scheduler_Module  $schedulerModule: reference to the calling object (BE module of the Scheduler)
	 * @internal  param array $taksInfo : array information of task to return
	 * @return  array      additional fields
	 * @see interfaces/tx_scheduler_AdditionalFieldProvider#getAdditionalFields($taskInfo, $task, $schedulerModule)
	 */
	public function getAdditionalFields(array &$taskInfo, $task, tx_scheduler_Module $schedulerModule) {
		$additionalFields = array();

			// Initialize selected fields
		if (empty($taskInfo['scheduler_tableCleanerExpired_tables'])) {
			$taskInfo['scheduler_tableCleanerExpired_tables'] = array();
			if ($schedulerModule->CMD == 'add') {
					// In case of new task, set to dbBackend if it's available
				if (in_array('sys_log', $this->getTables())) {
					$taskInfo['scheduler_tableCleanerExpired_tables'][] = 'sys_log';
				}
				if (in_array('sys_history', $this->getTables())) {
					$taskInfo['scheduler_tableCleanerExpired_tables'][] = 'sys_history';
				}
			} elseif ($schedulerModule->CMD == 'edit') {
					// In case of editing the task, set to currently selected value
				$taskInfo['scheduler_tableCleanerExpired_tables'] = $task->getTables();
			}
		}

		$fieldName = 'tx_scheduler[scheduler_tableCleanerExpired_tables][]';
		$fieldId = 'task_tableCleanerExpired_tables';
		$fieldOptions = $this->getTableOptions($taskInfo['scheduler_tableCleanerExpired_tables']);
		$fieldHtml =
			'<select name="' . $fieldName . '" id="' . $fieldId . '" class="wide" size="10" multiple="multiple">' .
				$fieldOptions .
			'</select>';

		$additionalFields[$fieldId] = array(
			'code' => $fieldHtml,
			'label' => 'LLL:EXT:tablecleaner/Resources/Private/Language/locallang.xml:tasks.general.tables',
			'cshKey' => 'tablecleaner',
			'cshLabel' => $fieldId,
		);

		if (empty($taskInfo['scheduler_tableCleanerExpired_dayLimit'])) {
			if ($schedulerModule->CMD == 'add') {
				$taskInfo['scheduler_tableCleanerExpired_dayLimit'] = '31';
			} elseif ($schedulerModule->CMD == 'edit') {
				$taskInfo['scheduler_tableCleanerExpired_dayLimit'] = $task->getDayLimit();
			} else {
				$taskInfo['scheduler_tableCleanerExpired_dayLimit'] = $task->getDayLimit();
			}
		}

		$fieldId = 'task_tableCleanerExpired_dayLimit';
		$fieldCode = '<input type="text" name="tx_scheduler[scheduler_tableCleanerExpired_dayLimit]"  id="' . $fieldId . '" value="' . htmlspecialchars($taskInfo['scheduler_tableCleanerExpired_dayLimit']) . '"/>';
		$additionalFields[$fieldId] = array(
			'code' => $fieldCode,
			'label' => 'LLL:EXT:tablecleaner/Resources/Private/Language/locallang.xml:tasks.expired.dayLimit',
			'cshKey' => 'tablecleaner',
			'cshLabel' => $fieldId,
		);

		return $additionalFields;
	}

	/**
	 * Build select options of available tables and set currently selected tables
	 *
	 * @param array $selectedTables Selected tables
	 * @return string HTML of selectbox options
	 */
	protected function getTableOptions(array $selectedTables) {
		$options = array();

		$availableTables = $this->getTables();
		foreach ($availableTables as $tableName) {
			if (in_array($tableName, $selectedTables)) {
				$selected = ' selected="selected"';
			} else {
				$selected = '';
			}
			$options[] =
				'<option value="' . $tableName .  '"' . $selected . '>' .
					$tableName .
				'</option>';
		}

		return implode('', $options);
	}

	/**
	 * Get all tables
	 *
	 * @return array Registered backends
	 */
	protected function getTables() {
		$tables = array();
		$resource = $GLOBALS['TYPO3_DB']->sql_query(
			"SELECT DISTINCT TABLE_NAME
			FROM INFORMATION_SCHEMA.COLUMNS
			WHERE (
				(TABLE_NAME LIKE '%log%' AND NOT TABLE_NAME LIKE '%blog%')
				OR TABLE_NAME LIKE '%hist%'
				OR TABLE_NAME LIKE '%error%'
			)
			AND COLUMN_NAME = 'tstamp'
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
	 * This method checks any additional data that is relevant to the specific task.
	 * If the task class is not relevant, the method is expected to return TRUE.
	 *
	 * @param   array $submittedData: reference to the array containing the data submitted by the user
	 * @param \tx_scheduler_Module|\tx_scheduler_module1 $schedulerModule : reference to the calling object (BE module of the Scheduler)
	 * @return   boolean      True if validation was ok (or selected class is not relevant), FALSE otherwise
	 */
	public function validateAdditionalFields(array &$submittedData, tx_scheduler_Module $schedulerModule) {
		$isValid = TRUE;

		if (is_array($submittedData['scheduler_tableCleanerExpired_tables'])) {
			$tables = $this->getTables();
			foreach ($submittedData['scheduler_tableCleanerExpired_tables'] as $table) {
				if (!in_array($table, $tables)) {
					$isValid = FALSE;
					$schedulerModule->addMessage(
						$GLOBALS['LANG']->sL('LLL:EXT:tablecleaner/Resources/Private/Language/locallang.xml:tasks.general.invalidTables'),
						t3lib_FlashMessage::ERROR
					);
				}
			}
		} else {
			$isValid = FALSE;
			$schedulerModule->addMessage(
				$GLOBALS['LANG']->sL('LLL:EXT:tablecleaner/Resources/Private/Language/locallang.xml:tasks.general.noTables'),
				t3lib_FlashMessage::ERROR
			);
		}

		if ($submittedData['scheduler_tableCleanerExpired_dayLimit'] <= 0) {
			$isValid = FALSE;
			$schedulerModule->addMessage(
				$GLOBALS['LANG']->sL('LLL:EXT:tablecleaner/Resources/Private/Language/locallang.xml:tasks.general.invalidNumberOfDays'),
				t3lib_FlashMessage::ERROR
			);
		}

		return $isValid;
	}

	/**
	 * This method is used to save any additional input into the current task object
	 * if the task class matches.
	 *
	 * @param	array		$submittedData: array containing the data submitted by the user
	 * @param	tx_scheduler_Task		$task: reference to the current task object
	 * @return	void
	 */
	public function saveAdditionalFields(array $submittedData, tx_scheduler_Task $task) {
		$task->setDayLimit(intval($submittedData['scheduler_tableCleanerExpired_dayLimit']));
		$task->setTables($submittedData['scheduler_tableCleanerExpired_tables']);
	}

}

if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/tablecleaner/Classes/Tasks/ExpiredAdditionalFieldProvider.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/tablecleaner/Classes/Tasks/ExpiredAdditionalFieldProvider.php']);
}

?>
