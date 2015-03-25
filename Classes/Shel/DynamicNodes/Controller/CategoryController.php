<?php
namespace Shel\DynamicNodes\Controller;

use Shel\DynamicNodes\Domain\Model\Category;
use Shel\DynamicNodes\Domain\Model\DynamicField;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Mvc\Controller\ActionController;

/**
 * The TYPO3 User Settings module controller
 *
 * @Flow\Scope("singleton")
 */
class CategoryController extends ActionController {

	/**
	 * @Flow\Inject
	 * @var \Shel\DynamicNodes\Domain\Repository\CategoryRepository
	 */
	protected $categoryRepository;

	/**
	 * @Flow\Inject
	 * @var \Shel\DynamicNodes\Domain\Repository\DynamicFieldRepository
	 */
	protected $dynamicFieldRepository;

	/**
	 * @return void
	 */
	public function indexAction() {
		$categories = $this->categoryRepository->findAll();

		$this->view->assignMultiple(array(
			'categories' => $categories,
		));
	}

	/**
	 * @param string $label
	 * @return void
	 * @Flow\Validate(argumentName="label", type="NotEmpty")
	 * @Flow\Validate(argumentName="label", type="Label")
	 */
	public function createAction($label) {
		$this->categoryRepository->add(new Category($label));
		$this->addFlashMessage(sprintf('Category "%s" has been created.', $label));
		$this->redirect('index');
	}

	/**
	 * @param Category $category
	 * @return void
	 */
	public function deleteAction(Category $category) {
		$this->categoryRepository->remove($category);
		$this->addFlashMessage(sprintf('Category "%s" has been deleted.', $category->getLabel()));
		$this->redirect('index');
	}

	/**
	 * @param Category $category
	 * @return void
	 */
	public function editAction(Category $category) {
		$dynamicFields = $this->dynamicFieldRepository->findByCategory($category);

		$this->view->assignMultiple(array(
			'category' => $category,
			'dynamicFields' => $dynamicFields,
		));
	}

	/**
	 * Update an asset
	 *
	 * @param Category $category
	 * @return void
	 */
	public function updateAction(Category $category) {
		$this->categoryRepository->update($category);
		$this->addFlashMessage('Category has been updated.');
		$this->redirect('index');
	}

	/**
	 * @param Category $category
	 * @param string $label
	 * @return void
	 */
	public function createDynamicFieldAction(Category $category, $label) {
		$dynamicFields = $category->getDynamicFields();
		$dynamicFields[]= new DynamicField($label, $category);
		$this->categoryRepository->update($category);
//		$this->dynamicFieldRepository->add(new DynamicField($label, $category));
		$this->redirect('index'); // should be index with category
	}
}
