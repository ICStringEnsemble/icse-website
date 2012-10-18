<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20121017185436 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("ALTER TABLE Event ADD location_id INT DEFAULT NULL, DROP location, CHANGE description description LONGTEXT DEFAULT NULL");
        $this->addSql("ALTER TABLE Event ADD CONSTRAINT FK_FA6F25A364D218E FOREIGN KEY (location_id) REFERENCES Venue (id)");
        $this->addSql("CREATE INDEX IDX_FA6F25A364D218E ON Event (location_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("ALTER TABLE Event DROP FOREIGN KEY FK_FA6F25A364D218E");
        $this->addSql("DROP INDEX IDX_FA6F25A364D218E ON Event");
        $this->addSql("ALTER TABLE Event ADD location VARCHAR(255) NOT NULL, DROP location_id, CHANGE description description LONGTEXT NOT NULL");
    }
}
