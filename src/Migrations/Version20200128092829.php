<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200128092829 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mg_orders_content (id INT AUTO_INCREMENT NOT NULL, get_order_id INT NOT NULL, product_id INT NOT NULL, format VARCHAR(64) DEFAULT NULL, reference VARCHAR(255) DEFAULT NULL, designation LONGTEXT DEFAULT NULL, quantity INT NOT NULL, gross_unit_price DOUBLE PRECISION NOT NULL, gross_unit_tax DOUBLE PRECISION NOT NULL, gross_unit_price_all_taxes DOUBLE PRECISION NOT NULL, amount_discount DOUBLE PRECISION NOT NULL, nature_discount LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', net_unit_price DOUBLE PRECISION NOT NULL, net_unit_tax DOUBLE PRECISION NOT NULL, net_unit_price_all_taxes DOUBLE PRECISION NOT NULL, total_net_price DOUBLE PRECISION NOT NULL, total_net_tax DOUBLE PRECISION NOT NULL, total_price_all_taxes DOUBLE PRECISION NOT NULL, INDEX IDX_68166CE9BC686086 (get_order_id), INDEX IDX_68166CE94584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_orders_content ADD CONSTRAINT FK_68166CE9BC686086 FOREIGN KEY (get_order_id) REFERENCES mg_orders (id)');
        $this->addSql('ALTER TABLE mg_orders_content ADD CONSTRAINT FK_68166CE94584665A FOREIGN KEY (product_id) REFERENCES mg_products (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mg_orders_content');
    }
}
