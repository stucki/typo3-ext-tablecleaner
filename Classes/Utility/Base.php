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
 * Base utility methods
 *
 * @package TYPO3
 * @subpackage tablecleaner
 * @version $Id:$
 * @license http://opensource.org/licenses/gpl-license.php
 *    GNU Public License, version 2
 */
class Tx_Tablecleaner_Utility_Base {

	/**
	 * Get tables with deleted and tstamp fields
	 *
	 * @return array $tables  The tables
	 */
	public static function getTablesWithDeletedAndTstamp() {
		$tables = array();
		$resource = $GLOBALS['TYPO3_DB']->sql_query(
			"SELECT TABLE_NAME
			FROM INFORMATION_SCHEMA.COLUMNS
			WHERE TABLE_NAME
			IN (
				SELECT TABLE_NAME
				FROM INFORMATION_SCHEMA.COLUMNS
				WHERE COLUMN_NAME = 'deleted'
				AND TABLE_SCHEMA =  '" . TYPO3_db . "'
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
	 * Get all tables with hidden and tstamp fields
	 *
	 * @return array $tables  The tables
	 */
	public static function getTablesWithHiddenAndTstamp() {
		$tables = array();
		$resource = $GLOBALS['TYPO3_DB']->sql_query(
			"SELECT TABLE_NAME
			FROM INFORMATION_SCHEMA.COLUMNS
			WHERE TABLE_NAME
			IN (
				SELECT TABLE_NAME
				FROM INFORMATION_SCHEMA.COLUMNS
				WHERE COLUMN_NAME = 'hidden'
				AND TABLE_SCHEMA =  '" . TYPO3_db . "'
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
	 * Get all tables with a parent id
	 *
	 * @return array $tables  The tables
	 */
	public static function getTablesWithPid() {
		$tables = array();
		$resource = $GLOBALS['TYPO3_DB']->sql_query(
			"SELECT TABLE_NAME
			FROM INFORMATION_SCHEMA.COLUMNS
			WHERE TABLE_NAME
			AND COLUMN_NAME = 'pid'
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
	 * Fetch child pages
	 *
	 * @param integer $pageId
	 * @return array $pageIds
	 */
	public static function fetchChildPages($pageId) {
		$res = $GLOBALS['TYPO3_DB']->sql_query('SELECT uid FROM pages WHERE pid = ' . $pageId);
		$pageIds = array();
		$pageIds[] = $pageId;
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$pageIds[] = $row['uid'];
			$pageIds = array_merge($pageIds, self::fetchChildPages($row['uid']));
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($res);
		return $pageIds;
	}

	/**
	 * Fetch pages that have 'tx_tablecleaner_exclude' or
	 * 'tx_tablecleaner_exclude_branch'set. If 'tx_tablecleaner_exclude_branch'
	 * is set, also recursively fetch the children of that page.
	 *
	 * @return array $pageIds
	 */
	public static function fetchExcludedPages() {
		$pageIds = array();

			// First fetch the pages that have 'tx_tablecleaner_exclude' set
		$res = $GLOBALS['TYPO3_DB']->sql_query('
			SELECT
				uid
			FROM
				pages
			WHERE
				tx_tablecleaner_exclude = 1;
			');
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$pageIds[] = $row['uid'];
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($res);

			// Then recursively fetch the pages that have 'tx_tablecleaner_exclude_branch' set
		$res = $GLOBALS['TYPO3_DB']->sql_query('
			SELECT
				uid
			FROM
				pages
			WHERE
				tx_tablecleaner_exclude_branch = 1;
			');
		while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($res)) {
			$pageIds = array_merge($pageIds, self::fetchChildPages($row['uid']));
		}
		$GLOBALS['TYPO3_DB']->sql_free_result($res);

		$pageIds = array_unique($pageIds);

		return $pageIds;
	}

}

if (defined('TYPO3_MODE')
	&& isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/tablecleaner/Classes/Utility/Base.php'])) {
	require_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/tablecleaner/Classes/Utility/Base.php']);
}

?>