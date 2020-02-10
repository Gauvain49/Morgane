<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200128094637 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mg_orders_payments (id INT AUTO_INCREMENT NOT NULL, payment_order_id INT NOT NULL, payment_mode_id INT NOT NULL, payment_amount DOUBLE PRECISION NOT NULL, payment_date DATETIME NOT NULL, info_transaction LONGTEXT DEFAULT NULL, INDEX IDX_139809FECD592F50 (payment_order_id), INDEX IDX_139809FE6EAC8BA0 (payment_mode_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_orders_payments ADD CONSTRAINT FK_139809FECD592F50 FOREIGN KEY (payment_order_id) REFERENCES mg_orders (id)');
        $this->addSql('ALTER TABLE mg_orders_payments ADD CONSTRAINT FK_139809FE6EAC8BA0 FOREIGN KEY (payment_mode_id) REFERENCES mg_payments_modes (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mg_orders_payments');
    }
}
