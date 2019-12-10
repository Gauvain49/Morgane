<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191202154222 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mg_products (id INT AUTO_INCREMENT NOT NULL, taxe_id INT NOT NULL, user_id INT NOT NULL, supplier_id INT DEFAULT NULL, reference VARCHAR(255) DEFAULT NULL, purshasing_price DOUBLE PRECISION DEFAULT NULL, selling_price DOUBLE PRECISION NOT NULL, selling_price_all_taxes DOUBLE PRECISION NOT NULL, sales_unit INT NOT NULL, min_quantity INT NOT NULL, max_quantity INT DEFAULT NULL, bulk_quantity INT DEFAULT NULL, discount DOUBLE PRECISION DEFAULT NULL, discount_type VARCHAR(10) DEFAULT NULL, discount_on_taxe TINYINT(1) DEFAULT \'0\', stock_management TINYINT(1) DEFAULT \'0\' NOT NULL, quantity INT DEFAULT NULL, sell_out_of_stock TINYINT(1) DEFAULT \'0\', stock_alert INT DEFAULT NULL, pre_order TINYINT(1) DEFAULT \'0\', available_date DATETIME DEFAULT NULL, date_publish DATETIME DEFAULT NULL, offline TINYINT(1) DEFAULT \'0\' NOT NULL, type VARCHAR(50) NOT NULL, date_add DATETIME NOT NULL, date_up DATETIME DEFAULT NULL, INDEX IDX_B88F7E4E1AB947A4 (taxe_id), INDEX IDX_B88F7E4EA76ED395 (user_id), INDEX IDX_B88F7E4E2ADD6D8C (supplier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mg_products_mg_users (mg_products_id INT NOT NULL, mg_users_id INT NOT NULL, INDEX IDX_4629E5EAD0FEAF8B (mg_products_id), INDEX IDX_4629E5EA6C869029 (mg_users_id), PRIMARY KEY(mg_products_id, mg_users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_products ADD CONSTRAINT FK_B88F7E4E1AB947A4 FOREIGN KEY (taxe_id) REFERENCES mg_taxes (id)');
        $this->addSql('ALTER TABLE mg_products ADD CONSTRAINT FK_B88F7E4EA76ED395 FOREIGN KEY (user_id) REFERENCES mg_users (id)');
        $this->addSql('ALTER TABLE mg_products ADD CONSTRAINT FK_B88F7E4E2ADD6D8C FOREIGN KEY (supplier_id) REFERENCES mg_suppliers (id)');
        $this->addSql('ALTER TABLE mg_products_mg_users ADD CONSTRAINT FK_4629E5EAD0FEAF8B FOREIGN KEY (mg_products_id) REFERENCES mg_products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mg_products_mg_users ADD CONSTRAINT FK_4629E5EA6C869029 FOREIGN KEY (mg_users_id) REFERENCES mg_users (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_products_mg_users DROP FOREIGN KEY FK_4629E5EAD0FEAF8B');
        $this->addSql('DROP TABLE mg_products');
        $this->addSql('DROP TABLE mg_products_mg_users');
    }
}
