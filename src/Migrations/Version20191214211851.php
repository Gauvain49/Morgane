<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191214211851 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mg_authors_mg_products (mg_authors_id INT NOT NULL, mg_products_id INT NOT NULL, INDEX IDX_F021BBFB7F4F398B (mg_authors_id), INDEX IDX_F021BBFBD0FEAF8B (mg_products_id), PRIMARY KEY(mg_authors_id, mg_products_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_authors_mg_products ADD CONSTRAINT FK_F021BBFB7F4F398B FOREIGN KEY (mg_authors_id) REFERENCES mg_authors (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mg_authors_mg_products ADD CONSTRAINT FK_F021BBFBD0FEAF8B FOREIGN KEY (mg_products_id) REFERENCES mg_products (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mg_authors_mg_products');
    }
}
