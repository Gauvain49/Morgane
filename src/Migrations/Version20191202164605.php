<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191202164605 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mg_gammes (id INT AUTO_INCREMENT NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mg_products_lang (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, lang_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, summary LONGTEXT DEFAULT NULL, description LONGTEXT DEFAULT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_C67ABDF34584665A (product_id), INDEX IDX_C67ABDF3B213FA4 (lang_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_products_lang ADD CONSTRAINT FK_C67ABDF34584665A FOREIGN KEY (product_id) REFERENCES mg_products (id)');
        $this->addSql('ALTER TABLE mg_products_lang ADD CONSTRAINT FK_C67ABDF3B213FA4 FOREIGN KEY (lang_id) REFERENCES mg_languages (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mg_gammes');
        $this->addSql('DROP TABLE mg_products_lang');
    }
}
