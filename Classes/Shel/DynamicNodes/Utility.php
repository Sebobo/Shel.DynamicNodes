<?php
namespace Shel\DynamicNodes;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Shel.DynamicNodes".     *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

/**
 * Contains some helpers
 */
class Utility {

	/**
	 * Returns a camelcase version of $name with the first letter uppercase.
	 *
	 * @param string $name
	 * @return string
	 */
	static public function renderValidName($name) {
		return preg_replace('/\s+/', '', ucwords(str_replace('-', ' ', \TYPO3\TYPO3CR\Utility::renderValidNodeName($name))));
	}
}
