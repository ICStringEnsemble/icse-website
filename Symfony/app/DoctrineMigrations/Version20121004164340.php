<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20121004164340 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("CREATE TABLE event_image (event_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_8426B57371F7E88B (event_id), INDEX IDX_8426B5733DA5256D (image_id), PRIMARY KEY(event_id, image_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE event_image ADD CONSTRAINT FK_8426B57371F7E88B FOREIGN KEY (event_id) REFERENCES Event (id) ON DELETE CASCADE");
        $this->addSql("ALTER TABLE event_image ADD CONSTRAINT FK_8426B5733DA5256D FOREIGN KEY (image_id) REFERENCES Image (id) ON DELETE CASCADE");
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("DROP TABLE event_image");
    }
}
