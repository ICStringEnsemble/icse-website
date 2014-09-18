<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140909224756 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE Image CHANGE category category VARCHAR(255) DEFAULT NULL, CHANGE updated_by updated_by_id INT NOT NULL");
        $this->addSql("ALTER TABLE Image ADD CONSTRAINT FK_4FC2B5B896DBBDE FOREIGN KEY (updated_by_id) REFERENCES Member (id)");
        $this->addSql("CREATE INDEX IDX_4FC2B5B896DBBDE ON Image (updated_by_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE Image DROP FOREIGN KEY FK_4FC2B5B896DBBDE");
        $this->addSql("DROP INDEX IDX_4FC2B5B896DBBDE ON Image");
        $this->addSql("ALTER TABLE Image CHANGE category category VARCHAR(255) NOT NULL, CHANGE updated_by_id updated_by INT NOT NULL");
    }
}
