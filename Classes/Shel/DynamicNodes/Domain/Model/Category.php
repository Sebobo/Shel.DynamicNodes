<?php
namespace Shel\DynamicNodes\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use TYPO3\Flow\Annotations as Flow;

/**
 * Category for configuring dynamic nodes
 *
 * @Flow\Entity
 */
class Category {

	/**
	 * @var string
	 * @Flow\Validate(type="StringLength", options={ "maximum"=50 })
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $label;

	/**
	 * Preferences of this user
	 *
	 * @var Collection<DynamicField>
	 * @ORM\OneToMany(mappedBy="category", cascade={"persist"})
	 * @Flow\Lazy
	 */
	protected $dynamicFields;

	/**
	 * Constructs category
	 *
	 * @param string
	 */
	public function __construct($label) {
		$this->label = $label;
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
	 * @param Collection<DynamicField> $dynamicFields
	 */
	public function setDomains($dynamicFields) {
		$this->dynamicFields = $dynamicFields;
	}

	/**
	 * @return Collection<DynamicField>
	 */
	public function getDynamicFields() {
		return $this->dynamicFields;
	}
}
