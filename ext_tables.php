<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

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

	ExtensionManagementUtility::addTCAcolumns('pages', $columnArray['pages']);

	if (isset($GLOBALS['TCA']['pages']['palettes']['visibility'])) {
		ExtensionManagementUtility::addFieldsToPalette('pages', 'visibility', 'tx_tablecleaner_exclude', 'after:nav_hide');
		ExtensionManagementUtility::addFieldsToPalette('pages', 'visibility', 'tx_tablecleaner_exclude_branch', 'after:tx_tablecleaner_exclude');
	} else {
		ExtensionManagementUtility::addToAllTCAtypes('pages', 'tx_tablecleaner_exclude', '', 'after:nav_hide');
		ExtensionManagementUtility::addToAllTCAtypes('pages', 'tx_tablecleaner_exclude_branch', '', 'after:tx_tablecleaner_exclude');
	}

	ExtensionManagementUtility::addLLrefForTCAdescr('tablecleaner', 'EXT:tablecleaner/Resources/Private/Language/ContextSensitiveHelp.xml');
	ExtensionManagementUtility::addLLrefForTCAdescr('pages', 'EXT:tablecleaner/Resources/Private/Language/ContextSensitiveHelpPages.xml');

	/**
	 * Register the Backend Module
	 */
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
			// Extension name
		'tablecleaner',
			// Place in section
		'web',
			// Module name
		'mod1',
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