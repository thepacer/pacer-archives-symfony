<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190109062738 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE volume ADD cover_issue_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE volume ADD CONSTRAINT FK_B99ACDDE867F63D7 FOREIGN KEY (cover_issue_id) REFERENCES issue (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B99ACDDE867F63D7 ON volume (cover_issue_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE volume DROP FOREIGN KEY FK_B99ACDDE867F63D7');
        $this->addSql('DROP INDEX UNIQ_B99ACDDE867F63D7 ON volume');
        $this->addSql('ALTER TABLE volume DROP cover_issue_id');
    }
}
