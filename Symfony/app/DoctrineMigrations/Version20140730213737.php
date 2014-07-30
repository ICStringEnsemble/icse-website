<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140730213737 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("CREATE TABLE SentNewsletter (id INT AUTO_INCREMENT NOT NULL, sent_by_id INT DEFAULT NULL, type INT NOT NULL, subject VARCHAR(255) NOT NULL, body LONGTEXT NOT NULL, sent_at DATETIME NOT NULL, INDEX IDX_C4C78EC5A45BB98C (sent_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB");
        $this->addSql("ALTER TABLE SentNewsletter ADD CONSTRAINT FK_C4C78EC5A45BB98C FOREIGN KEY (sent_by_id) REFERENCES Member (id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("DROP TABLE SentNewsletter");
    }
}
