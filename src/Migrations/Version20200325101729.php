<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200325101729 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mg_orders_carriers (id INT AUTO_INCREMENT NOT NULL, get_order_id INT NOT NULL, carrier_id INT NOT NULL, shipping_cost_tax_excl DOUBLE PRECISION NOT NULL, shipping_cost_taxes DOUBLE PRECISION NOT NULL, date_add DATE NOT NULL, tracking_number VARCHAR(50) DEFAULT NULL, INDEX IDX_79023879BC686086 (get_order_id), INDEX IDX_7902387921DFC797 (carrier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_orders_carriers ADD CONSTRAINT FK_79023879BC686086 FOREIGN KEY (get_order_id) REFERENCES mg_orders (id)');
        $this->addSql('ALTER TABLE mg_orders_carriers ADD CONSTRAINT FK_7902387921DFC797 FOREIGN KEY (carrier_id) REFERENCES mg_carriers (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mg_orders_carriers');
    }
}
