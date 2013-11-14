<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

$columnArray = array(
	'pages' => array(
		'tx_tablecleaner_exclude' => array(
			'exclude' => TRUE,
			'label' => 'LLL:EXT:tablecleaner/Resources/Private/Language/locallang_db.xml:pages.tx_tablecleaner_exclude',
			'config' => array(
				'type' => 'check',
				'default' => 0,
				'items' => array(
					array('LLL:EXT:lang/locallang_core.xml:labels.enabled', 1)
				)
			)
		),
		'tx_tablecleaner_exclude_branch' => array(
			'exclude' => TRUE,
			'label' => 'LLL:EXT:tablecleaner/Resources/Private/Language/locallang_db.xml:pages.tx_tablecleaner_exclude_branch',
			'config' => array(
				'type' => 'check',
				'default' => 0,
				'items' => array(
					array('LLL:EXT:lang/locallang_core.xml:labels.enabled', 1)
				)
			)
		)
	)
);

t3lib_extMgm::addTCAcolumns('pages', $columnArray['pages']);

t3lib_extMgm::addFieldsToPalette('pages', 'visibility', 'tx_tablecleaner_exclude', 'after:nav_hide');
t3lib_extMgm::addFieldsToPalette('pages', 'visibility', 'tx_tablecleaner_exclude_branch', 'after:tx_tablecleaner_exclude');

t3lib_extMgm::addLLrefForTCAdescr('tablecleaner', 'EXT:tablecleaner/Resources/Private/Language/ContextSensitiveHelp.xml');
t3lib_extMgm::addLLrefForTCAdescr('pages', 'EXT:tablecleaner/Resources/Private/Language/ContextSensitiveHelpPages.xml');

?>