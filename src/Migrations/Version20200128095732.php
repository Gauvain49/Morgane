<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200128095732 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mg_orders_status (id INT AUTO_INCREMENT NOT NULL, status_order_id INT NOT NULL, status_id INT NOT NULL, date_add DATETIME NOT NULL, INDEX IDX_A1F4AB561045CAE0 (status_order_id), INDEX IDX_A1F4AB566BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_orders_status ADD CONSTRAINT FK_A1F4AB561045CAE0 FOREIGN KEY (status_order_id) REFERENCES mg_orders (id)');
        $this->addSql('ALTER TABLE mg_orders_status ADD CONSTRAINT FK_A1F4AB566BF700BD FOREIGN KEY (status_id) REFERENCES mg_orders_status_lang (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mg_orders_status');
    }
}
