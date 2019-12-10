<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191203163148 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mg_categories (id INT AUTO_INCREMENT NOT NULL, parent_id INT NOT NULL, position INT DEFAULT NULL, active TINYINT(1) NOT NULL, force_display TINYINT(1) NOT NULL, type VARCHAR(10) NOT NULL, date_add DATETIME NOT NULL, date_up DATETIME DEFAULT NULL, level SMALLINT DEFAULT NULL, INDEX IDX_285E7ED9727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mg_category_lang (id INT AUTO_INCREMENT NOT NULL, cat_id INT NOT NULL, lang_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_C1F609BEE6ADA943 (cat_id), INDEX IDX_C1F609BEB213FA4 (lang_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_categories ADD CONSTRAINT FK_285E7ED9727ACA70 FOREIGN KEY (parent_id) REFERENCES mg_categories (id)');
        $this->addSql('ALTER TABLE mg_category_lang ADD CONSTRAINT FK_C1F609BEE6ADA943 FOREIGN KEY (cat_id) REFERENCES mg_categories (id)');
        $this->addSql('ALTER TABLE mg_category_lang ADD CONSTRAINT FK_C1F609BEB213FA4 FOREIGN KEY (lang_id) REFERENCES mg_languages (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_categories DROP FOREIGN KEY FK_285E7ED9727ACA70');
        $this->addSql('ALTER TABLE mg_category_lang DROP FOREIGN KEY FK_C1F609BEE6ADA943');
        $this->addSql('DROP TABLE mg_categories');
        $this->addSql('DROP TABLE mg_category_lang');
    }
}
