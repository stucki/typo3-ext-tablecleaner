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
 * Does a simple search and replace on a string
 *
 * = Examples =
 *
 * <code title="Child nodes">
 * <namespace:replace search="stringA" replace="stringB">
 *    {string}
 * </namespace:replace>
 * </code>
 * <output>
 * (Content of {string} with 'search' replaced by 'replace')
 * </output>
 *
 * <code title="Value attribute">
 * <namespace:replace search="stringA" replace="stringB" value="{string}" />
 * </code>
 * <output>
 * (Content of {string} with 'search' replaced by 'replace')
 * </output>
 *
 * @api
 */
namespace Stucki\TableCleaner\ViewHelpers;

class Format\ReplaceViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Do a simple search and replace on a string
	 *
	 * @param string $search
	 * @param string $replace
	 *
	 * @return string
	 */
	public function render($search = '', $replace = '') {
		$subject = $this->renderChildren();
		return str_replace($search, $replace, $subject);
	}
}
?>
