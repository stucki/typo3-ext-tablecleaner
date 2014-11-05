<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_tablecleaner_tasks_Deleted'] = array(
	'extension'        => 'tablecleaner',
	'title'            => 'LLL:EXT:tablecleaner/Resources/Private/Language/locallang.xml:tasks.deleted.title',
	'description'      => 'LLL:EXT:tablecleaner/Resources/Private/Language/locallang.xml:tasks.deleted.description',
	'additionalFields' => 'tx_tablecleaner_tasks_DeletedAdditionalFieldProvider'
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_tablecleaner_tasks_MarkLostRecords'] = array(
	'extension'        => 'tablecleaner',
	'title'            => 'LLL:EXT:tablecleaner/Resources/Private/Language/locallang.xml:tasks.markLostRecords.title',
	'description'      => 'LLL:EXT:tablecleaner/Resources/Private/Language/locallang.xml:tasks.markLostRecords.description',
	'additionalFields' => 'tx_tablecleaner_tasks_MarkLostRecordsAdditionalFieldProvider'
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_tablecleaner_tasks_Hidden'] = array(
	'extension'        => 'tablecleaner',
	'title'            => 'LLL:EXT:tablecleaner/Resources/Private/Language/locallang.xml:tasks.hidden.title',
	'description'      => 'LLL:EXT:tablecleaner/Resources/Private/Language/locallang.xml:tasks.hidden.description',
	'additionalFields' => 'tx_tablecleaner_tasks_HiddenAdditionalFieldProvider'
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_tablecleaner_tasks_Expired'] = array(
	'extension'        => 'tablecleaner',
	'title'            => 'LLL:EXT:tablecleaner/Resources/Private/Language/locallang.xml:tasks.expired.title',
	'description'      => 'LLL:EXT:tablecleaner/Resources/Private/Language/locallang.xml:tasks.expired.description',
	'additionalFields' => 'tx_tablecleaner_tasks_ExpiredAdditionalFieldProvider'
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_tablecleaner_tasks_PastStopTime'] = array(
	'extension'        => 'tablecleaner',
	'title'            => 'LLL:EXT:tablecleaner/Resources/Private/Language/locallang.xml:tasks.pastStopTime.title',
	'description'      => 'LLL:EXT:tablecleaner/Resources/Private/Language/locallang.xml:tasks.pastStopTime.description',
	'additionalFields' => 'tx_tablecleaner_tasks_PastStopTimeAdditionalFieldProvider'
);

?>