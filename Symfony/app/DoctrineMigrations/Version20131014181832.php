<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20131014181832 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE Venue ADD updated_by_id INT DEFAULT NULL, DROP manyToOne");
        $this->addSql("ALTER TABLE Venue ADD CONSTRAINT FK_50503409896DBBDE FOREIGN KEY (updated_by_id) REFERENCES Member (id)");
        $this->addSql("CREATE INDEX IDX_50503409896DBBDE ON Venue (updated_by_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE Venue DROP FOREIGN KEY FK_50503409896DBBDE");
        $this->addSql("DROP INDEX IDX_50503409896DBBDE ON Venue");
        $this->addSql("ALTER TABLE Venue ADD manyToOne VARCHAR(255) NOT NULL, DROP updated_by_id");
    }
}
