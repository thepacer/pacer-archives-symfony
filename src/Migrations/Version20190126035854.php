<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Base Schema
 */
final class Version20190126035854 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE article (id INT AUTO_INCREMENT NOT NULL, issue_id INT DEFAULT NULL, print_column VARCHAR(255) DEFAULT NULL, print_page VARCHAR(255) DEFAULT NULL, print_section VARCHAR(255) DEFAULT NULL, article_body LONGTEXT NOT NULL, headline VARCHAR(255) NOT NULL, alternative_headline VARCHAR(255) DEFAULT NULL, author_byline VARCHAR(255) NOT NULL, contributor_byline VARCHAR(255) DEFAULT NULL, date_created DATETIME NOT NULL, date_published DATETIME NOT NULL, date_modified DATETIME NOT NULL, keywords LONGTEXT NOT NULL, modified_by VARCHAR(255) NOT NULL, legacy_id INT DEFAULT NULL, slug VARCHAR(128) NOT NULL, UNIQUE INDEX UNIQ_23A0E66989D9B62 (slug), INDEX IDX_23A0E665E7AA58C (issue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE volume (id INT AUTO_INCREMENT NOT NULL, cover_issue_id INT DEFAULT NULL, volume_number VARCHAR(4) NOT NULL, volume_start_date DATE NOT NULL, volume_end_date DATE NOT NULL, nameplate_key VARCHAR(10) NOT NULL, UNIQUE INDEX UNIQ_B99ACDDE867F63D7 (cover_issue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE issue (id INT AUTO_INCREMENT NOT NULL, volume_id INT DEFAULT NULL, issue_date DATE NOT NULL, issue_number VARCHAR(255) NOT NULL, page_count INT NOT NULL, archive_key VARCHAR(255) DEFAULT NULL, archive_notes LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_12AD233E1AB9D946 (archive_key), INDEX IDX_12AD233E8FD80EEA (volume_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE legacy_article (article_id INT AUTO_INCREMENT NOT NULL, issue_id VARCHAR(255) NOT NULL, section_id VARCHAR(15) NOT NULL, author_id VARCHAR(40) DEFAULT NULL, co_author_id VARCHAR(40) DEFAULT NULL, author_title VARCHAR(40) DEFAULT NULL, co_author_title VARCHAR(40) DEFAULT NULL, title VARCHAR(200) DEFAULT NULL, subtitle VARCHAR(200) DEFAULT NULL, summary TEXT DEFAULT NULL, full_text TEXT DEFAULT NULL, photo_src VARCHAR(150) DEFAULT NULL, photo_align VARCHAR(15) DEFAULT NULL, photo_border CHAR(2) DEFAULT NULL, priority INT DEFAULT NULL, photo_credit VARCHAR(40) DEFAULT NULL, photo_caption TEXT DEFAULT NULL, keywords TEXT DEFAULT NULL, last_edited VARCHAR(200) DEFAULT \'Unknown\' NOT NULL, PRIMARY KEY(article_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, article_id INT NOT NULL, caption LONGTEXT NOT NULL, credit VARCHAR(255) NOT NULL, path VARCHAR(255) NOT NULL, INDEX IDX_C53D045F7294869C (article_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E665E7AA58C FOREIGN KEY (issue_id) REFERENCES issue (id)');
        $this->addSql('ALTER TABLE volume ADD CONSTRAINT FK_B99ACDDE867F63D7 FOREIGN KEY (cover_issue_id) REFERENCES issue (id)');
        $this->addSql('ALTER TABLE issue ADD CONSTRAINT FK_12AD233E8FD80EEA FOREIGN KEY (volume_id) REFERENCES volume (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F7294869C FOREIGN KEY (article_id) REFERENCES article (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F7294869C');
        $this->addSql('ALTER TABLE issue DROP FOREIGN KEY FK_12AD233E8FD80EEA');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E665E7AA58C');
        $this->addSql('ALTER TABLE volume DROP FOREIGN KEY FK_B99ACDDE867F63D7');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE volume');
        $this->addSql('DROP TABLE issue');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE legacy_article');
        $this->addSql('DROP TABLE image');
    }
}
