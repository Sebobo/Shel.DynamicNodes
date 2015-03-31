<?php
namespace TYPO3\Flow\Persistence\Doctrine\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
	Doctrine\DBAL\Schema\Schema;

/**
 * Create basic structure for dynamic node types and properties
 */
class Version20150401001641 extends AbstractMigration {

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function up(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
		
		$this->addSql("CREATE TABLE shel_dynamicnodes_domain_model_dynamicnodetype (persistence_object_identifier VARCHAR(40) NOT NULL, uuid VARCHAR(255) NOT NULL, `label` VARCHAR(255) NOT NULL, PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("CREATE TABLE shel_dynamicnodes_domain_model_dynamicproperty (persistence_object_identifier VARCHAR(40) NOT NULL, dynamicnodetype VARCHAR(40) DEFAULT NULL, uuid VARCHAR(255) NOT NULL, `label` VARCHAR(255) NOT NULL, placeholder VARCHAR(255) NOT NULL, defaultvalue VARCHAR(255) NOT NULL, INDEX IDX_9D3C1C2418A463CE (dynamicnodetype), PRIMARY KEY(persistence_object_identifier)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
		$this->addSql("ALTER TABLE shel_dynamicnodes_domain_model_dynamicproperty ADD CONSTRAINT FK_9D3C1C2418A463CE FOREIGN KEY (dynamicnodetype) REFERENCES shel_dynamicnodes_domain_model_dynamicnodetype (persistence_object_identifier)");
	}

	/**
	 * @param Schema $schema
	 * @return void
	 */
	public function down(Schema $schema) {
		$this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
		
		$this->addSql("ALTER TABLE shel_dynamicnodes_domain_model_dynamicproperty DROP FOREIGN KEY FK_9D3C1C2418A463CE");
		$this->addSql("DROP TABLE shel_dynamicnodes_domain_model_dynamicnodetype");
		$this->addSql("DROP TABLE shel_dynamicnodes_domain_model_dynamicproperty");
	}
}
