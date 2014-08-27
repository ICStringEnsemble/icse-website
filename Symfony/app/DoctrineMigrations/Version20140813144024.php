<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140813144024 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE PracticePart DROP FOREIGN KEY FK_9BD4B60FC40FCFA8");
        $this->addSql("DROP INDEX IDX_9BD4B60FC40FCFA8 ON PracticePart");
        $this->addSql("ALTER TABLE PracticePart ADD instrument VARCHAR(255) NOT NULL, DROP file, DROP type, DROP updated_at, CHANGE updated_by sort_index INT NOT NULL");

        $this->addSql("ALTER TABLE PracticePart CHANGE piece_id piece_id INT NOT NULL");
        $this->addSql("ALTER TABLE PracticePart ADD CONSTRAINT FK_9BD4B60FC40FCFA8 FOREIGN KEY (piece_id) REFERENCES PieceOfMusic (id)");
        $this->addSql("CREATE INDEX IDX_9BD4B60FC40FCFA8 ON PracticePart (piece_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE PracticePart DROP FOREIGN KEY FK_9BD4B60FC40FCFA8");
        $this->addSql("DROP INDEX IDX_9BD4B60FC40FCFA8 ON PracticePart");
        $this->addSql("ALTER TABLE PracticePart CHANGE piece_id piece_id INT DEFAULT NULL");

        $this->addSql("ALTER TABLE PracticePart ADD type VARCHAR(255) NOT NULL, ADD updated_at DATETIME NOT NULL, CHANGE instrument file VARCHAR(255) NOT NULL, CHANGE sort_index updated_by INT NOT NULL");
        $this->addSql("ALTER TABLE PracticePart ADD CONSTRAINT FK_9BD4B60FC40FCFA8 FOREIGN KEY (piece_id) REFERENCES PieceOfMusic (id)");
        $this->addSql("CREATE INDEX IDX_9BD4B60FC40FCFA8 ON PracticePart (piece_id)");
    }
}
