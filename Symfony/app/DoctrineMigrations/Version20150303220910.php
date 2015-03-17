<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20150303220910 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE NewsArticle ADD posted_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE NewsArticle ADD CONSTRAINT FK_3E819CDA5A6D2235 FOREIGN KEY (posted_by_id) REFERENCES Member (id)');
        $this->addSql('CREATE INDEX IDX_3E819CDA5A6D2235 ON NewsArticle (posted_by_id)');
        $this->addSql('ALTER TABLE NewsArticle DROP featured_until');
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE NewsArticle ADD featured_until DATETIME NOT NULL');
        $this->addSql('ALTER TABLE NewsArticle DROP FOREIGN KEY FK_3E819CDA5A6D2235');
        $this->addSql('DROP INDEX IDX_3E819CDA5A6D2235 ON NewsArticle');
        $this->addSql('ALTER TABLE NewsArticle DROP posted_by_id');
    }
}
