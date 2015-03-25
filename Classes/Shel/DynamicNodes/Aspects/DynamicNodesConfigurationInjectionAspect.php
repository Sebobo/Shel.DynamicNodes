<?php
namespace Shel\DynamicNodes\Aspects;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Shel.DynamicNodes".     *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU Lesser General Public License, either version 3   *
 * of the License, or (at your option) any later version.                 *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\AOP\JoinPointInterface;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Configuration\ConfigurationManager;
use TYPO3\TYPO3CR\Domain\Service\NodeTypeManager;
use TYPO3\TYPO3CR\Utility;
use Shel\DynamicNodes\Domain\Model\Category;
use Shel\DynamicNodes\Domain\Model\DynamicField;

/**
 * @Flow\Scope("singleton")
 * @Flow\Aspect
 */
class DynamicNodesConfigurationInjectionAspect {

	/**
	 * @Flow\Inject
	 * @var \Shel\DynamicNodes\Domain\Repository\CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * @Flow\Inject
	 * @var ConfigurationManager
	 */
	protected $configurationManager;

	/**
	 * @Flow\Inject(setting="defaults")
	 * @var array
	 */
	protected $settings;

	/**
	 * This aspect will block loadNodeTypes from being called and will instead
	 * call overrideNodeTypes with a modified configuration.
	 *
	 * @Flow\Around("method(TYPO3\TYPO3CR\Domain\Service\NodeTypeManager->loadNodeTypes())")
	 * @param JoinPointInterface $joinPoint The current join point
	 * @return void
	 */
	public function addDynamicNodeConfiguration(JoinPointInterface $joinPoint) {
		$completeNodeTypeConfiguration = $this->configurationManager->getConfiguration('NodeTypes');

		$categories = $this->categoryRepository->findAll();
		/* @var $category Category */
		foreach ($categories as $category) {
			$dynamicNodeName = $this->renderValidNodeTypeName($category->getLabel());
			if (!array_key_exists($dynamicNodeName, $completeNodeTypeConfiguration)) {

				$dynamicProperties = array();
				/* @var $dynamicField DynamicField */
				foreach ($category->getDynamicFields() as $dynamicField) {
					$dynamicPropertyName = $this->renderValidPropertyName($dynamicField->getLabel());
					$dynamicProperties[$dynamicPropertyName] = array(
						'type' => 'string',
						'ui' => array(
							'label' => $dynamicField->getLabel(),
							'reloadIfChanged' => TRUE,
							'inspector' => array(
								'group' => 'dynamicFields'
							)
						)
					);
				}

				$newNodeConfiguration = array(
					'superTypes' => $this->settings['superTypes'],
					'abstract' => FALSE,
					'ui' => array(
						'label' => $category->getLabel(),
					),
					'properties' => $dynamicProperties
				);
				$completeNodeTypeConfiguration[$dynamicNodeName] = $newNodeConfiguration;
			}
		}

		/* @var $nodeTypeManager NodeTypeManager */
		$nodeTypeManager = $joinPoint->getProxy();
		$nodeTypeManager->overrideNodeTypes($completeNodeTypeConfiguration);
	}

	/**
	 * Returns a camelcase version of $name with the first letter uppercase.
	 *
	 * @param string $name
	 * @return string
	 */
	protected function renderValidName($name) {
		return preg_replace('/\s+/', '', ucwords(str_replace('-', ' ', Utility::renderValidNodeName($name))));
	}

	/**
	 * @param string $name
	 * @return string
	 */
	protected function renderValidNodeTypeName($name) {
		return $this->settings['nodeNamespace'] . ':' . $this->renderValidName($name);
	}

	/**
	 * @param string $name
	 * @return string
	 */
	protected function renderValidPropertyName($name) {
		return $this->settings['propertyPrefix'] . $this->renderValidName($name);
	}
}
