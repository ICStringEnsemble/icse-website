<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140804145003 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql("ALTER TABLE Venue DROP FOREIGN KEY FK_50503409896DBBDE");
        $this->addSql("ALTER TABLE NewsArticle DROP FOREIGN KEY FK_3E819CDA896DBBDE");
        $this->addSql("ALTER TABLE Event DROP FOREIGN KEY FK_FA6F25A3896DBBDE");
        $this->addSql("ALTER TABLE PerformanceOfAPiece DROP FOREIGN KEY FK_BC4033771F7E88B");
        $this->addSql("ALTER TABLE PerformanceOfAPiece DROP FOREIGN KEY FK_BC40337C40FCFA8");
        $this->addSql("ALTER TABLE Rehearsal DROP FOREIGN KEY FK_C9F7D89C896DBBDE");
        $this->addSql("ALTER TABLE SentNewsletter DROP FOREIGN KEY FK_C4C78EC5A45BB98C");
        $this->addSql("ALTER TABLE CommitteeRole DROP FOREIGN KEY FK_F5A5DC507597D3FE");

        $this->addSql("DROP INDEX IDX_50503409896DBBDE ON Venue");
        $this->addSql("DROP INDEX IDX_3E819CDA896DBBDE ON NewsArticle");
        $this->addSql("DROP INDEX IDX_FA6F25A3896DBBDE ON Event");
        $this->addSql("DROP INDEX IDX_BC4033771F7E88B ON PerformanceOfAPiece");
        $this->addSql("DROP INDEX IDX_BC40337C40FCFA8 ON PerformanceOfAPiece");
        $this->addSql("DROP INDEX IDX_C9F7D89C896DBBDE ON Rehearsal");
        $this->addSql("DROP INDEX IDX_C4C78EC5A45BB98C ON SentNewsletter");
        $this->addSql("DROP INDEX IDX_F5A5DC507597D3FE ON CommitteeRole");
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");
        
        $this->addSql("ALTER TABLE CommitteeRole ADD CONSTRAINT FK_F5A5DC507597D3FE FOREIGN KEY (member_id) REFERENCES Member (id)");
        $this->addSql("ALTER TABLE SentNewsletter ADD CONSTRAINT FK_C4C78EC5A45BB98C FOREIGN KEY (sent_by_id) REFERENCES Member (id)");
        $this->addSql("ALTER TABLE Rehearsal ADD CONSTRAINT FK_C9F7D89C896DBBDE FOREIGN KEY (updated_by_id) REFERENCES Member (id)");
        $this->addSql("ALTER TABLE PerformanceOfAPiece ADD CONSTRAINT FK_BC40337C40FCFA8 FOREIGN KEY (piece_id) REFERENCES PieceOfMusic (id)");
        $this->addSql("ALTER TABLE PerformanceOfAPiece ADD CONSTRAINT FK_BC4033771F7E88B FOREIGN KEY (event_id) REFERENCES Event (id)");
        $this->addSql("ALTER TABLE Event ADD CONSTRAINT FK_FA6F25A3896DBBDE FOREIGN KEY (updated_by_id) REFERENCES Member (id)");
        $this->addSql("ALTER TABLE NewsArticle ADD CONSTRAINT FK_3E819CDA896DBBDE FOREIGN KEY (updated_by_id) REFERENCES Member (id)");
        $this->addSql("ALTER TABLE Venue ADD CONSTRAINT FK_50503409896DBBDE FOREIGN KEY (updated_by_id) REFERENCES Member (id)");

        $this->addSql("CREATE INDEX IDX_F5A5DC507597D3FE ON CommitteeRole (member_id)");
        $this->addSql("CREATE INDEX IDX_C4C78EC5A45BB98C ON SentNewsletter (sent_by_id)");
        $this->addSql("CREATE INDEX IDX_C9F7D89C896DBBDE ON Rehearsal (updated_by_id)");
        $this->addSql("CREATE INDEX IDX_BC40337C40FCFA8 ON PerformanceOfAPiece (piece_id)");
        $this->addSql("CREATE INDEX IDX_BC4033771F7E88B ON PerformanceOfAPiece (event_id)");
        $this->addSql("CREATE INDEX IDX_FA6F25A3896DBBDE ON Event (updated_by_id)");
        $this->addSql("CREATE INDEX IDX_3E819CDA896DBBDE ON NewsArticle (updated_by_id)");
        $this->addSql("CREATE INDEX IDX_50503409896DBBDE ON Venue (updated_by_id)");
    }
}
