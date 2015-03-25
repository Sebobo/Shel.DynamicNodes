<?php
namespace Shel\DynamicNodes\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * Category for configuring dynamic nodes
 *
 * @Flow\Entity
 */
class DynamicField {

	/**
	 * @var string
	 * @Flow\Validate(type="StringLength", options={ "maximum"=50 })
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $label;

	/**
	 * @ORM\ManyToOne(inversedBy="dynamicNodes")
	 * @Flow\Validate(type="NotEmpty", cascade={"persist"})
	 * @var Category
	 */
	protected $category;

	/**
	 * Constructs dynamic field
	 *
	 * @param string
	 */
	public function __construct($label, $category) {
		$this->label = $label;
		$this->category = $category;
	}

	/**
	 * Sets the label of this category
	 *
	 * @param string $label
	 * @return void
	 */
	public function setLabel($label) {
		$this->label = $label;
	}

	/**
	 * The label of this category
	 *
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * @return Category
	 */
	public function getCategory() {
		return $this->category;
	}
}
