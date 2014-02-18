<?php
/*****************************************************************************
 *  Copyright notice
 *
 *  ⓒ 2014 Michiel Roos <michiel@maxserv.nl>
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
 * Created by PhpStorm.
 * Author: Michiel Roos <michiel@maxserv.nl>
 * Date: 15/11/13
 * Time: 10:29
 */

/**
 * InfoModule controller
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License,
 * version 3 or later
 */
class Tx_Tablecleaner_Controller_InfoModuleController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * @var t3lib_DB
	 */
	protected $databaseHandle;

	/**
	 * @var Tx_Tablecleaner_Domain_Repository_PageRepository
	 */
	protected $pageRepository;

	/**
	 * inject Page repository
	 *
	 * @param Tx_Tablecleaner_Domain_Repository_PageRepository $pageRepository
	 *
	 * @return void
	 */
	public function injectPageRepository(Tx_Tablecleaner_Domain_Repository_PageRepository $pageRepository) {
		$this->pageRepository = $pageRepository;
	}

	/**
	 * Index action
	 *
	 * @return void
	 */
	public function indexAction() {
		$this->databaseHandle = $GLOBALS['TYPO3_DB'];

		$uid = abs(t3lib_div::_GP('id'));
		$values['startingPage'] = $this->pageRepository->findOneByUid($uid);

		// Initialize tree object:
		/** @var t3lib_browsetree $tree */
		$tree = t3lib_div::makeInstance('t3lib_browsetree');
		// Also store tree prefix markup:
		$tree->expandFirst = TRUE;
		$tree->addField('tx_tablecleaner_exclude', TRUE);
		$tree->addField('tx_tablecleaner_exclude_branch', TRUE);
		$tree->makeHTML = 2;
		$tree->table = 'pages';
		// Set starting page id of the tree (overrides webmounts):
		$tree->setTreeName('tablecleaner_' . $uid);
		$this->MOUNTS = $GLOBALS['WEBMOUNTS'];

		$tree->init();
		$treeData = $this->getTreeData($uid, $tree->subLevelID);
		$tree->setDataFromArray($treeData);

		$tree->getTree($uid);
		$tree->ext_IconMode = TRUE;
		$tree->ext_showPageId = $GLOBALS['BE_USER']->getTSConfigVal('options.pageTree.showPageIdWithTitle');
		$tree->showDefaultTitleAttribute = TRUE;
		/**
		 * Hmmm . . . need php_http module for this, can't count on that :-(
		 * parse_str($parsedUrl['query'], $urlQuery);
		 * unset($urlQuery['PM']);
		 * $parsedUrl = http_build_query($urlQuery);
		 * $tree->thisScript = http_build_url($parsedUrl);
		 */
		// Remove the PM parameter to avoid adding multiple of those to the url
		$tree->thisScript = preg_replace('/&PM=[^#$]*/', '', t3lib_div::getIndpEnv('REQUEST_URI'));

		$tree->getBrowsableTree();

		$values['titleLength'] = intval($GLOBALS['BE_USER']->uc['titleLen']);
		$values['tree'] = $tree->tree;

		$this->view->assignMultiple($values);
	}

	/**
	 * Get tree data
	 *
	 * @param integer $uid
	 * @param string $subLevelId
	 *
	 * @return array
	 */
	protected function getTreeData($uid, $subLevelId) {

		// Filter the results by preference and access
		$clauseExludePidList = '';
		if ($pidList = $GLOBALS['BE_USER']->getTSConfigVal('options.hideRecords.pages')) {
			if ($pidList = $this->databaseHandle->cleanIntList($pidList)) {
				$clauseExludePidList = ' AND pages.uid NOT IN (' . $pidList . ')';
			}
		}
		$clause = ' AND ' . $GLOBALS['BE_USER']->getPagePermsClause(1) . ' ' . $clauseExludePidList;

		/**
		 * We want a page tree with all the excluded pages in there. This means
		 * all pages that have the exclude flag set and also all pages that have the
		 * excludeBranch flag set, including their children.
		 *
		 * 1). First fetch the page id's that have any exclusion options set
		 */
		$result = $this->databaseHandle->sql_query('
			SELECT GROUP_CONCAT(uid) AS uids
			FROM pages
			WHERE
				tx_tablecleaner_exclude = 1 AND
				deleted = 0 ' . $clause . ';
		');
		$row = $this->databaseHandle->sql_fetch_assoc($result);
		$excludePages = array();
		if ($row['uids'] !== NULL) {
			$excludePages = explode(',', $row['uids']);
		}
		$this->databaseHandle->sql_free_result($result);

		$result = $this->databaseHandle->sql_query('
			SELECT GROUP_CONCAT(uid) AS uids
			FROM pages
			WHERE
				tx_tablecleaner_exclude_branch = 1 AND
				deleted = 0 ' . $clause . ';
		');
		$row = $this->databaseHandle->sql_fetch_assoc($result);
		$excludeBranchPages = array();
		if ($row['uids'] !== NULL) {
			$excludeBranchPages = explode(',', $row['uids']);
		}
		$this->databaseHandle->sql_free_result($result);

		/**
		 * 2). Fetch the id's up to the 'current root' page.
		 * To build a complete page tree, we also need the parents of the
		 * excluded pages. So we merge the found pages and fetch the rootlines for
		 * all those pages.
		 */
		$allExcludedPages = array_merge($excludePages, $excludeBranchPages);
		$allExcludedPages = array_unique($allExcludedPages);

		$allUids = array();
		foreach ($allExcludedPages as $pageId) {
			// Don't fetch the rootline if the pageId is already in the list
			if (!in_array($pageId, $allUids)) {
				// Get the rootline up to the starting uid
				$rootLine = t3lib_BEfunc::BEgetRootLine($pageId, ' AND NOT uid = ' . $uid . $clause);
				foreach ($rootLine as $record) {
					$allUids[] = $record['uid'];
				}
			}
		}

		/**
		 * 3). Include self
		 */
		$allUids[] = $uid;

		/**
		 * 4). Fetch all the children of the pages that have exclude_branch set.
		 */
		foreach ($excludeBranchPages as $pageId) {
			$allUids = array_merge($allUids, Tx_Tablecleaner_Utility_Base::fetchChildPages($pageId));
		}
		$allUids = array_unique($allUids);

		$foundPages = $this->pageRepository->findByUids($allUids);
		$allPages = array();
		foreach ($foundPages as $page) {
			$allPages[$page['uid']] = $page;
		}

		$tree = $this->reassembleTree($allPages, $uid, $subLevelId);
		$rootElement[$uid] = $allPages[$uid];
		$rootElement[$uid][$subLevelId] = $tree;

		return $rootElement;
	}

	/**
	 * Assemble tree
	 *
	 * @param array $records
	 * @param integer $parentId
	 * @param string $subLevelId
	 *
	 * @return array
	 */
	protected function reassembleTree($records, $parentId, $subLevelId) {
		$branches = array();
		// Check if there are any children of the $parentId
		foreach ($records as $record) {
			if ($record['pid'] == $parentId) {
				$children = $this->reassembleTree($records, $record['uid'], $subLevelId);
				if ($children) {
					$branches[$record['uid']] = $record;
					$branches[$record['uid']][$subLevelId] = $children;
				} else {
					$branches[$record['uid']] = $record;
				}
				unset($records[$record['uid']]);
			}
		}
		return $branches;
	}

}

?>