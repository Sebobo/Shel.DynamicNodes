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

use Shel\DynamicNodes\Domain\Repository\DynamicNodeTypeRepository;
use TYPO3\Flow\AOP\JoinPointInterface;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Configuration\ConfigurationManager;
use TYPO3\TYPO3CR\Domain\Service\NodeTypeManager;
use TYPO3\TYPO3CR\Utility;
use Shel\DynamicNodes\Domain\Model\DynamicNodeType;
use Shel\DynamicNodes\Domain\Model\DynamicProperty;

/**
 * @Flow\Scope("singleton")
 * @Flow\Aspect
 */
class DynamicNodesConfigurationInjectionAspect {

	/**
	 * @Flow\Inject
	 * @var DynamicNodeTypeRepository
	 */
	protected $dynamicNodeTypeRepository;

	/**
	 * @Flow\Inject
	 * @var ConfigurationManager
	 */
	protected $configurationManager;

	/**
	 * @Flow\Inject
	 * @var NodeTypeManager
	 */
	protected $nodeTypeManager;

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

		$dynamicNodeTypes = $this->dynamicNodeTypeRepository->findAll();
		/* @var $dynamicNodeType DynamicNodeType */
		foreach ($dynamicNodeTypes as $dynamicNodeType) {
			$dynamicNodeName = $this->renderValidNodeTypeName($dynamicNodeType->getUuid());
			if (!array_key_exists($dynamicNodeName, $completeNodeTypeConfiguration)) {
				$dynamicProperties = array();
				/* @var $dynamicProperty DynamicProperty */
				foreach ($dynamicNodeType->getDynamicProperties() as $dynamicProperty) {
					$dynamicPropertyName = $this->renderValidPropertyName($dynamicProperty->getUuid());
					$dynamicProperties[$dynamicPropertyName] = array(
						'type' => 'string',
						'defaultValue' => $dynamicProperty->getDefaultValue(),
						'ui' => array(
							'label' => $dynamicProperty->getLabel(),
							'reloadIfChanged' => TRUE,
							'inlineEditable' => TRUE,
							'aloha' => array(
								'placeholder' => $dynamicProperty->getPlaceholder()
							),
							'inspector' => array(
								'group' => 'dynamicProperties',
								'editorOptions' => array(
									'placeholder' => $dynamicProperty->getPlaceholder()
								)
							)
						)
					);
				}

				$newNodeConfiguration = array(
					'superTypes' => $this->settings['superTypes'],
					'abstract' => FALSE,
					'ui' => array(
						'label' => $dynamicNodeType->getLabel(),
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
	 * This aspect will reset the node type cache after changes to the dynamic node types
	 *
	 * @Flow\After("method(Shel\DynamicNodes\Controller\DynamicNodeTypeController->(update|create|delete|editProperty|createDynamicProperty|updateDynamicProperty|deleteDynamicProperty)Action())")
	 * @param JoinPointInterface $joinPoint The current join point
	 * @return void
	 */
	public function clearNodeTypeConfigurationCache(JoinPointInterface $joinPoint) {
		$this->nodeTypeManager->overrideNodeTypes(array());
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
