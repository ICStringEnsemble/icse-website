<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140726203835 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE MemberProfile ADD picture_id INT DEFAULT NULL, ADD join_year INT NOT NULL, ADD study_subject VARCHAR(100) NOT NULL, ADD favourite_snack VARCHAR(100) NOT NULL, ADD memorable_moment LONGTEXT NOT NULL, CHANGE instrument instrument VARCHAR(100) NOT NULL");
        $this->addSql("ALTER TABLE MemberProfile ADD CONSTRAINT FK_2071BDD6EE45BDBF FOREIGN KEY (picture_id) REFERENCES Image (id)");
        $this->addSql("CREATE INDEX IDX_2071BDD6EE45BDBF ON MemberProfile (picture_id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE MemberProfile DROP FOREIGN KEY FK_2071BDD6EE45BDBF");
        $this->addSql("DROP INDEX IDX_2071BDD6EE45BDBF ON MemberProfile");
        $this->addSql("ALTER TABLE MemberProfile DROP picture_id, DROP join_year, DROP study_subject, DROP favourite_snack, DROP memorable_moment, CHANGE instrument instrument VARCHAR(100) NOT NULL");
    }
}
