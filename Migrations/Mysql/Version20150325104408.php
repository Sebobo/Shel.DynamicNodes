<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Add dynamic fields and the relation to categories
 */
class Version20150325104408 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
		
		$this->addSql("CREATE TABLE shel_dynamicnodes_domain_model_dynamicfield (persistence_object_identifier VARCHAR(40) NOT NULL, category VARCHAR(40) DEFAULT NULL, `label` VARCHAR(255) NOT NULL, INDEX IDX_F28733F564C19C1 (category), PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("ALTER TABLE shel_dynamicnodes_domain_model_dynamicfield ADD CONSTRAINT FK_F28733F564C19C1 FOREIGN KEY (category) REFERENCES shel_dynamicnodes_domain_model_category (persistence_object_identifier)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
		
		$this->addSql("DROP TABLE shel_dynamicnodes_domain_model_dynamicfield");
	}
}
