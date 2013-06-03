<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "tablecleaner".
 *
 * Auto generated 14-03-2013 20:55
 *
 * Manual updates:
 * Only the data in the array - everything else is removed by next
 * writing. "version" and "dependencies" must not be touched!
 ***************************************************************/

$EM_CONF[$_EXTKEY] = array(
	'title' => 'Table Cleaner',
	'description' => 'Removes [deleted/hidden] records older than [N] days from tables.',
	'category' => 'be',
	'shy' => 0,
	'version' => '1.0.0',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Michiel Roos',
	'author_email' => 'extensions@donationbasedhosting.org',
	'author_company' => 'Donation Based Hosting',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' =>
	array(
		'depends' =>
		array(
			'typo3' => '4.5.0-0.0.0',
		),
		'conflicts' =>
		array(),
		'suggests' =>
		array(),
	),
	'_md5_values_when_last_written' => 'a:11:{s:16:"ext_autoload.php";s:4:"3886";s:12:"ext_icon.gif";s:4:"62c8";s:17:"ext_localconf.php";s:4:"f573";s:14:"ext_tables.php";s:4:"8a8d";s:23:"Classes/Tasks/Deleted.php";s:4:"3031";s:46:"Classes/Tasks/CleanAdditionalFieldProvider.php";s:4:"ebee";s:26:"Classes/Tasks/Truncate.php";s:4:"54ec";s:49:"Classes/Tasks/TruncateAdditionalFieldProvider.php";s:4:"0f86";s:51:"Resources/Private/Language/ContextSensitiveHelp.xml";s:4:"b172";s:40:"Resources/Private/Language/locallang.xml";s:4:"2d25";s:14:"doc/manual.sxw";s:4:"d41d";}',
	'suggests' =>
	array(),
);

?>