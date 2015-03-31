<?php
namespace Shel\DynamicNodes\Controller;

use Shel\DynamicNodes\Domain\Model\DynamicNodeType;
use Shel\DynamicNodes\Domain\Model\DynamicProperty;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;

/**
 * Controller for managing dynamic node types
 *
 * @Flow\Scope("singleton")
 */
class DynamicNodeTypeController extends ActionController {

	/**
	 * @Flow\Inject
	 * @var \Shel\DynamicNodes\Domain\Repository\DynamicNodeTypeRepository
	 */
	protected $dynamicNodeTypeRepository;

	/**
	 * @Flow\Inject
	 * @var \Shel\DynamicNodes\Domain\Repository\DynamicPropertyRepository
	 */
	protected $dynamicPropertyRepository;

	/**
	 * @return void
	 */
	public function indexAction() {
		$dynamicNodeTypes = $this->dynamicNodeTypeRepository->findAll();

		$this->view->assignMultiple(array(
			'dynamicNodeTypes' => $dynamicNodeTypes,
		));
	}

	/**
	 * @param string $label
	 * @return void
	 * @Flow\Validate(argumentName="label", type="NotEmpty")
	 * @Flow\Validate(argumentName="label", type="Label")
	 */
	public function createAction($label) {
		$this->dynamicNodeTypeRepository->add(new DynamicNodeType($label));
		$this->addFlashMessage(sprintf('Dynamic node type "%s" has been created.', $label));
		$this->redirect('index');
	}

	/**
	 * @param DynamicNodeType $dynamicNodeType
	 * @return void
	 */
	public function deleteAction(DynamicNodeType $dynamicNodeType) {
		$this->dynamicNodeTypeRepository->remove($dynamicNodeType);
		$this->addFlashMessage(sprintf('Dynamic node type "%s" has been deleted.', $dynamicNodeType->getLabel()));
		$this->redirect('index');
	}

	/**
	 * @param DynamicNodeType $dynamicNodeType
	 * @return void
	 */
	public function editAction(DynamicNodeType $dynamicNodeType) {
		$dynamicProperties = $this->dynamicPropertyRepository->findByDynamicNodeType($dynamicNodeType);

		$this->view->assignMultiple(array(
			'dynamicNodeType' => $dynamicNodeType,
			'dynamicProperties' => $dynamicProperties,
		));
	}

	/**
	 * Update a dynamic node type
	 *
	 * @param DynamicNodeType $dynamicNodeType
	 * @return void
	 */
	public function updateAction(DynamicNodeType $dynamicNodeType) {
		$this->dynamicNodeTypeRepository->update($dynamicNodeType);
		$this->addFlashMessage('Dynamic node type ' . $dynamicNodeType->getLabel() . ' has been updated.');
		$this->redirect('index');
	}

	/**
	 * @param DynamicNodeType $dynamicNodeType
	 * @param string $label
	 * @param string $placeholder
	 * @param string $defaultValue
	 * @throws \TYPO3\Flow\Persistence\Exception\IllegalObjectTypeException
	 */
	public function createDynamicPropertyAction(DynamicNodeType $dynamicNodeType, $label, $placeholder='', $defaultValue='') {
		$dynamicProperties = $dynamicNodeType->getDynamicProperties();
		$dynamicProperties[]= new DynamicProperty($label, $dynamicNodeType, $placeholder, $defaultValue);
		$this->dynamicNodeTypeRepository->update($dynamicNodeType);
		$this->redirect('edit', 'DynamicNodeType', 'Shel.DynamicNodes', array('dynamicNodeType' => $dynamicNodeType));
	}

	/**
	 * @param DynamicProperty $dynamicProperty
	 * @return void
	 */
	public function editDynamicPropertyAction(DynamicProperty $dynamicProperty) {
		$this->view->assignMultiple(array(
			'dynamicProperty' => $dynamicProperty,
		));
	}

	/**
	 * Update a dynamic property
	 *
	 * @param DynamicProperty $dynamicProperty
	 * @return void
	 */
	public function updateDynamicPropertyAction(DynamicProperty $dynamicProperty) {
		$this->dynamicPropertyRepository->update($dynamicProperty);
		$this->addFlashMessage('Property ' . $dynamicProperty->getLabel() . ' has been updated.');
		$this->redirect('edit', 'DynamicNodeType', 'Shel.DynamicNodes', array('dynamicNodeType' => $dynamicProperty->getDynamicNodeType()));
	}

	/**
	 * @param DynamicProperty $dynamicProperty
	 * @return void
	 */
	public function deleteDynamicPropertyAction(DynamicProperty $dynamicProperty) {
		$this->dynamicPropertyRepository->remove($dynamicProperty);
		$this->addFlashMessage(sprintf('Property "%s" has been deleted.', $dynamicProperty->getLabel()));
		$this->redirect('edit', 'DynamicNodeType', 'Shel.DynamicNodes', array('dynamicNodeType' => $dynamicProperty->getDynamicNodeType()));
	}
}
