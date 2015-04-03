<?php
namespace Shel\DynamicNodes\TypoScript\FlowQueryOperations;

use TYPO3\Eel\FlowQuery\FlowQuery;
use TYPO3\Eel\FlowQuery\Operations\AbstractOperation;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Reflection\ObjectAccess;
use TYPO3\TYPO3CR\Domain\Model\NodeInterface;

/**
 * This operation retrieves all dynamic properties of a dynamic node type as array.
 * The result looks like this:
 *
 *     array(
 *         'dynamicPropertyFoo' => array(
 *             'label' => 'foo',
 *             'value' => 'bar'
 *         ),
 *         ...
 *     )
 *
 * Class DynamicPropertiesOperation
 * @package Shel\DynamicNodes\TypoScript\FlowQueryOperations
 */
class DynamicPropertiesOperation extends AbstractOperation {

	/**
	 * {@inheritdoc}
	 *
	 * @var string
	 */
	static protected $shortName = 'dynamicProperties';

	/**
	 * @Flow\Inject(setting="defaults")
	 * @var array
	 */
	protected $settings;

	/**
	 * {@inheritdoc}
	 *
	 * @param array (or array-like object) $context onto which this operation should be applied
	 * @return boolean TRUE if the operation can be applied onto the $context, FALSE otherwise
	 */
	public function canEvaluate($context) {
		return TRUE;
	}

	/**
	 * @param FlowQuery $flowQuery the FlowQuery object
	 * @param array $arguments the arguments for this operation
	 * @return void
	 */
	public function evaluate(FlowQuery $flowQuery, array $arguments) {
		$context = $flowQuery->getContext();
		if (!isset($context[0])) {
			return NULL;
		}

		$output = array();
		/* @var $element NodeInterface */
		$element = $context[0];
		$properties = ObjectAccess::getPropertyPath($element, 'properties');
		$dynamicPropertyPrefix = $this->settings['propertyPrefix'];
		$propertyConfiguration = $element->getNodeType()->getProperties();

		// Retrieve human readable label for each dynamic property from the properties configuration.
		// Don't show properties which don't exist anymore in the node types configuration.
		foreach ($properties as $propertyName => $value) {
			if (strpos($propertyName, $dynamicPropertyPrefix) === 0 && array_key_exists($propertyName, $propertyConfiguration)) {
				$output[$propertyName] = array(
					'label' => $propertyConfiguration[$propertyName]['ui']['label'],
					'value' => $value,
				);
			}
		}

		$flowQuery->setContext($output);
	}
}
