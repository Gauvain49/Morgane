<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200128100530 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mg_orders_taxes (id INT AUTO_INCREMENT NOT NULL, order_content_id INT NOT NULL, taxe_id INT NOT NULL, base_price DOUBLE PRECISION NOT NULL, unit_tax DOUBLE PRECISION NOT NULL, quantity INT NOT NULL, total_tax DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_56F83D93377A5EB8 (order_content_id), INDEX IDX_56F83D931AB947A4 (taxe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_orders_taxes ADD CONSTRAINT FK_56F83D93377A5EB8 FOREIGN KEY (order_content_id) REFERENCES mg_orders_content (id)');
        $this->addSql('ALTER TABLE mg_orders_taxes ADD CONSTRAINT FK_56F83D931AB947A4 FOREIGN KEY (taxe_id) REFERENCES mg_taxes (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mg_orders_taxes');
    }
}
