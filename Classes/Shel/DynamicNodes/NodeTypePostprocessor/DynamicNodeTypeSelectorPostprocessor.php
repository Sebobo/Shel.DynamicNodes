<?php
namespace Shel\DynamicNodes\NodeTypePostprocessor;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Shel.DynamicNodes".     *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use Shel\DynamicNodes\Domain\Model\DynamicNodeType;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Configuration\ConfigurationManager;
use TYPO3\TYPO3CR\Domain\Model\NodeType;
use TYPO3\TYPO3CR\NodeTypePostprocessor\NodeTypePostprocessorInterface;

/**
 * This Processor updates the DynamicNodeTypeSelectorMixin NodeType with the existing
 * dynamic node types
 */
class DynamicNodeTypeSelectorPostprocessor implements NodeTypePostprocessorInterface {

	/**
	 * @var ConfigurationManager
	 * @Flow\Inject
	 */
	protected $configurationManager;

	/**
	 * @Flow\Inject
	 * @var \Shel\DynamicNodes\Domain\Repository\DynamicNodeTypeRepository
	 */
	protected $dynamicNodeTypeRepository;

	/**
	 * @Flow\Inject(setting="defaults")
	 * @var array
	 */
	protected $settings;

	/**
	 * Returns the processed Configuration
	 *
	 * @param \TYPO3\TYPO3CR\Domain\Model\NodeType $nodeType (uninitialized) The node type to process
	 * @param array $configuration input configuration
	 * @param array $options The processor options
	 * @return void
	 */
	public function process(NodeType $nodeType, array &$configuration, array $options) {
		$dynamicNodeTypes = $this->dynamicNodeTypeRepository->findAll();

		$typesList = array();
		/** @var DynamicNodeType $dynamicNodeType */
		foreach ($dynamicNodeTypes as $dynamicNodeType) {
			$typesList[$dynamicNodeType->getValidNodeTypeName($this->settings['nodeNamespace'])] = array(
				'label' => $dynamicNodeType->getLabel()
			);
		}

		$configuration['properties']['selectedNodeTypes']['ui']['inspector']['editorOptions']['values'] = $typesList;
	}
}
