<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20131224005427 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE Event ADD updated_by_id INT DEFAULT NULL, DROP updated_by");
        $this->addSql("ALTER TABLE Event ADD CONSTRAINT FK_FA6F25A3896DBBDE FOREIGN KEY (updated_by_id) REFERENCES Member (id)");
        $this->addSql("CREATE INDEX IDX_FA6F25A3896DBBDE ON Event (updated_by_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE Event DROP FOREIGN KEY FK_FA6F25A3896DBBDE");
        $this->addSql("DROP INDEX IDX_FA6F25A3896DBBDE ON Event");
        $this->addSql("ALTER TABLE Event ADD updated_by INT NOT NULL, DROP updated_by_id");
    }
}
