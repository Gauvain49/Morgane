<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191204164410 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mg_products_mg_categories (mg_products_id INT NOT NULL, mg_categories_id INT NOT NULL, INDEX IDX_BA2583E5D0FEAF8B (mg_products_id), INDEX IDX_BA2583E5D976E32C (mg_categories_id), PRIMARY KEY(mg_products_id, mg_categories_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_products_mg_categories ADD CONSTRAINT FK_BA2583E5D0FEAF8B FOREIGN KEY (mg_products_id) REFERENCES mg_products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mg_products_mg_categories ADD CONSTRAINT FK_BA2583E5D976E32C FOREIGN KEY (mg_categories_id) REFERENCES mg_categories (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mg_products_mg_categories');
    }
}
