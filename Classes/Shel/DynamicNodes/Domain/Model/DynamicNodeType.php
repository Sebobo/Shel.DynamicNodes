<?php
namespace Shel\DynamicNodes\Domain\Model;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Shel\DynamicNodes\Utility;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Utility\Algorithms;

/**
 * Dynamic node type model
 *
 * @Flow\Entity
 */
class DynamicNodeType {

	/**
	 * The unique identifier used for generating the node type name
	 *
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $uuid;

	/**
	 * @var string
	 * @Flow\Validate(type="StringLength", options={"maximum"=30})
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $label;

	/**
	 * Properties of this node type
	 *
	 * @var Collection<DynamicProperty>
	 * @ORM\OneToMany(mappedBy="dynamicNodeType", cascade={"persist", "remove"})
	 * @Flow\Lazy
	 */
	protected $dynamicProperties;

	/**
	 * Constructs dynamic node type
	 *
	 * @param string
	 */
	public function __construct($label) {
		$this->label = $label;
		if (empty($this->uuid)) {
			$this->uuid = $label . Algorithms::generateRandomString(6);
		}
	}

	/**
	 * Sets the label of this dynamic node type
	 *
	 * @param string $label
	 * @return void
	 */
	public function setLabel($label) {
		$this->label = $label;
	}

	/**
	 * The label of this dynamic node type
	 *
	 * @return string
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * @param Collection<DynamicProperty> $dynamicProperties
	 */
	public function setDomains($dynamicProperties) {
		$this->dynamicProperties = $dynamicProperties;
	}

	/**
	 * @return Collection<DynamicProperty>
	 */
	public function getDynamicProperties() {
		return $this->dynamicProperties;
	}

	/**
	 * @return string
	 */
	public function getUuid() {
		return $this->uuid;
	}

	/**
	 * Creates a valid unique node type name which can be used in the node types configuration
	 *
	 * @param string $namespace
	 * @return string
	 */
	public function getValidNodeTypeName($namespace) {
		return $namespace . ':' . Utility::renderValidName($this->uuid);
	}
}
