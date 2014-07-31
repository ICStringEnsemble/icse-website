<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140731210043 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE PieceOfMusic CHANGE updated_by updated_by_id INT NOT NULL");
        $this->addSql("ALTER TABLE PieceOfMusic ADD CONSTRAINT FK_43FBD0896DBBDE FOREIGN KEY (updated_by_id) REFERENCES Member (id)");
        $this->addSql("CREATE INDEX IDX_43FBD0896DBBDE ON PieceOfMusic (updated_by_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE PieceOfMusic DROP FOREIGN KEY FK_43FBD0896DBBDE");
        $this->addSql("DROP INDEX IDX_43FBD0896DBBDE ON PieceOfMusic");
        $this->addSql("ALTER TABLE PieceOfMusic CHANGE updated_by_id updated_by INT NOT NULL");
    }
}
