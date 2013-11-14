<?php

/***************************************************************
 * Extension Manager/Repository config file for ext "tablecleaner".
 *
 * Auto generated 21-10-2013 09:42
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
	'version' => '2.2.0',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => 'pages',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Michiel Roos',
	'author_email' => 'extensions@donationbasedhosting.org',
	'author_company' => 'Donation Based Hosting',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
			'typo3' => '4.5.0-0.0.0',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:14:{s:16:"ext_autoload.php";s:4:"a5a3";s:12:"ext_icon.gif";s:4:"62c8";s:17:"ext_localconf.php";s:4:"b130";s:14:"ext_tables.php";s:4:"8a8d";s:22:"Classes/Tasks/Base.php";s:4:"b509";s:25:"Classes/Tasks/Deleted.php";s:4:"0d04";s:48:"Classes/Tasks/DeletedAdditionalFieldProvider.php";s:4:"3b14";s:25:"Classes/Tasks/Expired.php";s:4:"f6db";s:48:"Classes/Tasks/ExpiredAdditionalFieldProvider.php";s:4:"0415";s:24:"Classes/Tasks/Hidden.php";s:4:"15b3";s:47:"Classes/Tasks/HiddenAdditionalFieldProvider.php";s:4:"ddfb";s:51:"Resources/Private/Language/ContextSensitiveHelp.xml";s:4:"10d3";s:40:"Resources/Private/Language/locallang.xml";s:4:"eebe";s:14:"doc/manual.sxw";s:4:"4263";}',
	'suggests' => array(
	),
);

?>