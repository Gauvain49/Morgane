<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191216113952 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mg_products_mg_authors (mg_products_id INT NOT NULL, mg_authors_id INT NOT NULL, INDEX IDX_D3FD6AA8D0FEAF8B (mg_products_id), INDEX IDX_D3FD6AA87F4F398B (mg_authors_id), PRIMARY KEY(mg_products_id, mg_authors_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_products_mg_authors ADD CONSTRAINT FK_D3FD6AA8D0FEAF8B FOREIGN KEY (mg_products_id) REFERENCES mg_products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mg_products_mg_authors ADD CONSTRAINT FK_D3FD6AA87F4F398B FOREIGN KEY (mg_authors_id) REFERENCES mg_authors (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mg_products_mg_authors');
    }
}
