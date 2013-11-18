<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

if (TYPO3_MODE === 'BE') {

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

	if (isset($GLOBALS['TCA']['pages']['palettes']['visibility'])) {
		t3lib_extMgm::addFieldsToPalette('pages', 'visibility', 'tx_tablecleaner_exclude', 'after:nav_hide');
		t3lib_extMgm::addFieldsToPalette('pages', 'visibility', 'tx_tablecleaner_exclude_branch', 'after:tx_tablecleaner_exclude');
	} else {
		t3lib_extMgm::addToAllTCAtypes('pages', 'tx_tablecleaner_exclude', '', 'after:nav_hide');
		t3lib_extMgm::addToAllTCAtypes('pages', 'tx_tablecleaner_exclude_branch', '', 'after:tx_tablecleaner_exclude');
	}

	t3lib_extMgm::addLLrefForTCAdescr('tablecleaner', 'EXT:tablecleaner/Resources/Private/Language/ContextSensitiveHelp.xml');
	t3lib_extMgm::addLLrefForTCAdescr('pages', 'EXT:tablecleaner/Resources/Private/Language/ContextSensitiveHelpPages.xml');

	/**
	 * Register the Backend Module
	 */
	Tx_Extbase_Utility_Extension::registerModule (
			// Extension name
		'tablecleaner',
			// Place in section
		'web',
			// Module name
		'Tx_Tablecleaner_InfoModule',
			// Position
		'after:info',
			// An array holding the controller-action-combinations that are accessible
			// The first controller and its first action will be the default
		array(
			'InfoModule' => 'index'
		),
		array(
			'access' => 'user,group',
			'icon'   => 'EXT:tablecleaner/ext_icon.gif',
			'labels' => 'LLL:EXT:tablecleaner/Resources/Private/Language/locallang.xml',
		)
	);

}
?>