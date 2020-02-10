<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200128091834 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mg_orders (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, current_status_id INT NOT NULL, num VARCHAR(15) NOT NULL, billing_name VARCHAR(100) NOT NULL, billing_address LONGTEXT NOT NULL, delivery_name VARCHAR(100) NOT NULL, delivery_address LONGTEXT NOT NULL, total_price DOUBLE PRECISION NOT NULL, total_taxes DOUBLE PRECISION NOT NULL, total_price_all_taxes DOUBLE PRECISION NOT NULL, total_shipping_price DOUBLE PRECISION NOT NULL, total_shipping_taxes DOUBLE PRECISION NOT NULL, uniq_key VARCHAR(32) NOT NULL, date_add DATETIME NOT NULL, INDEX IDX_107B1EECA76ED395 (user_id), INDEX IDX_107B1EECB0D1B111 (current_status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_orders ADD CONSTRAINT FK_107B1EECA76ED395 FOREIGN KEY (user_id) REFERENCES mg_users (id)');
        $this->addSql('ALTER TABLE mg_orders ADD CONSTRAINT FK_107B1EECB0D1B111 FOREIGN KEY (current_status_id) REFERENCES mg_orders_status_lang (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mg_orders');
    }
}
