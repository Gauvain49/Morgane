<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191220222959 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mg_posts_lang (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, lang_id INT NOT NULL, reviser_id INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, slug VARCHAR(255) NOT NULL, date_up DATETIME NOT NULL, revision LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_99F4856A4B89032C (post_id), INDEX IDX_99F4856AB213FA4 (lang_id), INDEX IDX_99F4856AF7819B7E (reviser_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_posts_lang ADD CONSTRAINT FK_99F4856A4B89032C FOREIGN KEY (post_id) REFERENCES mg_posts (id)');
        $this->addSql('ALTER TABLE mg_posts_lang ADD CONSTRAINT FK_99F4856AB213FA4 FOREIGN KEY (lang_id) REFERENCES mg_languages (id)');
        $this->addSql('ALTER TABLE mg_posts_lang ADD CONSTRAINT FK_99F4856AF7819B7E FOREIGN KEY (reviser_id) REFERENCES mg_users (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mg_posts_lang');
    }
}
