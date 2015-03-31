<?php
namespace Shel\DynamicNodes\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "TYPO3.Media".           *
 *                                                                        *
 * It is free software; you can redistribute it and/or modify it under    *
 * the terms of the GNU General Public License, either version 3 of the   *
 * License, or (at your option) any later version.                        *
 *                                                                        *
 * The TYPO3 project - inspiring people to share!                         *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Persistence\QueryInterface;
use TYPO3\Flow\Persistence\Repository;

/**
 * A repository for dynamic node types
 *
 * @Flow\Scope("singleton")
 */
class DynamicNodeTypeRepository extends Repository {

	/**
	 * @var array
	 */
	protected $defaultOrderings = array('label' => QueryInterface::ORDER_ASCENDING);

	/**
	 * @param string $searchTerm
	 * @return \TYPO3\Flow\Persistence\QueryResultInterface
	 */
	public function findBySearchTerm($searchTerm) {
		$query = $this->createQuery();
		return $query->matching($query->like('label', '%' . $searchTerm . '%'))->execute();
	}

}
