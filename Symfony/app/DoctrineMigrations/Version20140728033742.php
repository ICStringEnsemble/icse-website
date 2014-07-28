<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140728033742 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE MemberProfile CHANGE instrument instrument VARCHAR(100) DEFAULT NULL, CHANGE join_year join_year INT DEFAULT NULL, CHANGE study_subject study_subject VARCHAR(100) DEFAULT NULL, CHANGE favourite_snack favourite_snack VARCHAR(100) DEFAULT NULL, CHANGE memorable_moment memorable_moment LONGTEXT DEFAULT NULL");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE MemberProfile CHANGE instrument instrument VARCHAR(100) NOT NULL, CHANGE join_year join_year INT NOT NULL, CHANGE study_subject study_subject VARCHAR(100) NOT NULL, CHANGE favourite_snack favourite_snack VARCHAR(100) NOT NULL, CHANGE memorable_moment memorable_moment LONGTEXT NOT NULL");
    }
}
