<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140722160455 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE CommitteeRole ADD member_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE CommitteeRole ADD CONSTRAINT FK_F5A5DC507597D3FE FOREIGN KEY (member_id) REFERENCES Member (id)");
        $this->addSql("CREATE INDEX IDX_F5A5DC507597D3FE ON CommitteeRole (member_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE CommitteeRole DROP FOREIGN KEY FK_F5A5DC507597D3FE");
        $this->addSql("DROP INDEX IDX_F5A5DC507597D3FE ON CommitteeRole");
        $this->addSql("ALTER TABLE CommitteeRole DROP member_id");
    }
}
