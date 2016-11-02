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

namespace Stucki\Tablecleaner\Domain\Model;

/**
 * A page
 *
 * @author   Michiel Roos <michiel@maxserv.nl>
 * @package TYPO3
 * @subpackage tablecleaner
 */
class Page extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * @var boolean
	 */
	protected $exclude;

	/**
	 * @var boolean
	 */
	protected $excludeBranch;

	/**
	 * @var string
	 */
	protected $title;

	/**
	 * @return boolean
	 */
	public function getExclude() {
		return $this->exclude;
	}

	/**
	 * @return boolean
	 */
	public function getExcludeBranch() {
		return $this->excludeBranch;
	}

	/**
	 * @return mixed
	 */
	public function getTitle() {
		return $this->title;
	}

}

?>