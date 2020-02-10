<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200128095103 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_orders_payments ADD payment_parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mg_orders_payments ADD CONSTRAINT FK_139809FE3153E558 FOREIGN KEY (payment_parent_id) REFERENCES mg_orders_payments (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_139809FE3153E558 ON mg_orders_payments (payment_parent_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_orders_payments DROP FOREIGN KEY FK_139809FE3153E558');
        $this->addSql('DROP INDEX UNIQ_139809FE3153E558 ON mg_orders_payments');
        $this->addSql('ALTER TABLE mg_orders_payments DROP payment_parent_id');
    }
}
