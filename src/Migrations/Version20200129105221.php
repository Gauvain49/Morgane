<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200129105221 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_orders_content DROP FOREIGN KEY FK_68166CE94584665A');
        $this->addSql('DROP INDEX IDX_68166CE94584665A ON mg_orders_content');
        $this->addSql('ALTER TABLE mg_orders_content CHANGE product_id product INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_orders_content CHANGE product product_id INT NOT NULL');
        $this->addSql('ALTER TABLE mg_orders_content ADD CONSTRAINT FK_68166CE94584665A FOREIGN KEY (product_id) REFERENCES mg_products (id)');
        $this->addSql('CREATE INDEX IDX_68166CE94584665A ON mg_orders_content (product_id)');
    }
}
