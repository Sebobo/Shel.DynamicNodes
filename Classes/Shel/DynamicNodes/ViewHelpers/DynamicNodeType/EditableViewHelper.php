<?php
namespace Shel\DynamicNodes\ViewHelpers\DynamicNodeType;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Shel.DynamicNodes".     *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Reflection\ObjectAccess;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;
use TYPO3\Fluid\Core\ViewHelper\Exception as ViewHelperException;

/**
 * Renders a wrapper around the inner contents of the tag to enable frontend editing of dynamic properties.
 *
 * The wrapper contains the property name which should be made editable, and is by default
 * a "div" tag. The tag to use can be given as `tag` argument to the ViewHelper.
 *
 * In live workspace this just renders a tag with the specified $tag-name containing the value of the given $property.
 * For logged in users with access to the Backend this also adds required attributes for the RTE to work.
 *
 * Note: when passing a node you have to make sure a metadata wrapper is used around this that matches the given node
 * (see contentElement.wrap - i.e. the WrapViewHelper).
 */
class EditableViewHelper extends \TYPO3\Neos\ViewHelpers\ContentElement\EditableViewHelper {

	/**
	 * @Flow\Inject(setting="defaults")
	 * @var array
	 */
	protected $settings;

	/**
	 * {@inheritdoc}
	 */
	public function render($property, $tag = 'div', NodeInterface $node = NULL) {
		if ($node === NULL) {
			$node = $this->getNodeFromTypoScriptContext();
		}

		$properties = ObjectAccess::getPropertyPath($node, 'properties');
		$dynamicPropertyPrefix = $this->settings['propertyPrefix'];

		// Add dynamic properties to context which are missing
		$addedProperties = array();
		foreach ($properties as $propertyName => $value) {
			if (strpos($propertyName, $dynamicPropertyPrefix) === 0 && !$this->templateVariableContainer->exists($propertyName)) {
				$this->templateVariableContainer->add($propertyName, $value);
				$addedProperties[]= $propertyName;
			}
		}

		$output = parent::render($property, $tag, $node);

		// Remove dynamic properties again
		foreach ($addedProperties as $propertyName) {
			$this->templateVariableContainer->remove($propertyName);
		}

		return $output;
	}
}
