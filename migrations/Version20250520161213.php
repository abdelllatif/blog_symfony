<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250520161213 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE post_category DROP FOREIGN KEY FK_B9A190604B89032C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE post_category DROP FOREIGN KEY FK_B9A1906012469DE2
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE post_category
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user ADD roles JSON NOT NULL, CHANGE work work VARCHAR(60) DEFAULT NULL, CHANGE studied_at studied_at VARCHAR(60) DEFAULT NULL, CHANGE profile_image profile_image VARCHAR(70) DEFAULT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE post_category (post_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_B9A190604B89032C (post_id), INDEX IDX_B9A1906012469DE2 (category_id), PRIMARY KEY(post_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE post_category ADD CONSTRAINT FK_B9A190604B89032C FOREIGN KEY (post_id) REFERENCES post (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE post_category ADD CONSTRAINT FK_B9A1906012469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user DROP roles, CHANGE work work VARCHAR(60) DEFAULT 'NULL', CHANGE studied_at studied_at VARCHAR(60) DEFAULT 'NULL', CHANGE profile_image profile_image VARCHAR(70) DEFAULT 'NULL'
        SQL);
    }
}
