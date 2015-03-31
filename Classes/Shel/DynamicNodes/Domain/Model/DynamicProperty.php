<?php
namespace Shel\DynamicNodes\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Utility\Algorithms;

/**
 * Dynamic property for dynamic node types
 *
 * @Flow\Entity
 */
class DynamicProperty {

	/**
	 * The unique identifier used for generating the property name for this property
	 *
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $uuid;

	/**
	 * @var string
	 * @Flow\Validate(type="StringLength", options={ "maximum"=50 })
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $label;

	/**
	 * @var string
	 * @Flow\Validate(type="StringLength", options={ "maximum"=80 })
	 */
	protected $placeholder;

	/**
	 * @var string
	 * @Flow\Validate(type="StringLength", options={ "maximum"=255 })
	 */
	protected $defaultValue;

	/**
	 * @ORM\ManyToOne(inversedBy="dynamicNodes")
	 * @Flow\Validate(type="NotEmpty", cascade={"persist"})
	 * @var DynamicNodeType
	 */
	protected $dynamicNodeType;

	/**
	 * Constructs dynamic property
	 *
	 * @param string $label
	 * @param DynamicNodeType $dynamicNodeType
	 * @param string $placeholder
	 * @param string $defaultValue
	 */
	public function __construct($label, $dynamicNodeType, $placeholder, $defaultValue) {
		$this->label = $label;
		$this->dynamicNodeType = $dynamicNodeType;
		$this->placeholder = $placeholder;
		$this->defaultValue = $defaultValue;
		if (empty($this->uuid)) {
			$this->uuid = $label . Algorithms::generateRandomString(6);
		}
	}

	/**
	 * Sets the label of this dynamic property
	 *
	 * @param string $label
	 * @return void
	 */
	public function setLabel($label) {
		$this->label = $label;
	}

	/**
	 * The label of this dynamic property
	 *
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * @return DynamicNodeType
	 */
	public function getDynamicNodeType() {
		return $this->dynamicNodeType;
	}

	/**
	 * @return string
	 */
	public function getUuid() {
		return $this->uuid;
	}

	/**
	 * @return mixed
	 */
	public function getPlaceholder() {
		return $this->placeholder;
	}

	/**
	 * @param mixed $placeholder
	 */
	public function setPlaceholder($placeholder) {
		$this->placeholder = $placeholder;
	}

	/**
	 * @return mixed
	 */
	public function getDefaultValue() {
		return $this->defaultValue;
	}

	/**
	 * @param mixed $defaultValue
	 */
	public function setDefaultValue($defaultValue) {
		$this->defaultValue = $defaultValue;
	}
}
