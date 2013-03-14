<?php
/* $Id$ */

if (!defined ('TYPO3_MODE')) {
	die ('Access denied.');
}

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_tablecleaner_tasks_Clean'] = array(
	'extension'        => 'tablecleaner',
	'title'            => 'LLL:EXT:tablecleaner/Resources/Private/Language/locallang.xml:tasks.clean.title',
	'description'      => 'LLL:EXT:tablecleaner/Resources/Private/Language/locallang.xml:tasks.clean.description',
	'additionalFields' => 'tx_tablecleaner_tasks_CleanAdditionalFieldProvider'
);

$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['scheduler']['tasks']['tx_tablecleaner_tasks_Truncate'] = array(
	'extension'        => 'tabletruncater',
	'title'            => 'LLL:EXT:tablecleaner/Resources/Private/Language/locallang.xml:tasks.truncate.title',
	'description'      => 'LLL:EXT:tablecleaner/Resources/Private/Language/locallang.xml:tasks.truncate.description',
	'additionalFields' => 'tx_tablecleaner_tasks_TruncateAdditionalFieldProvider'
);

?>
