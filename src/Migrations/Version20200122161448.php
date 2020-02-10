<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200122161448 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mg_countries (id INT AUTO_INCREMENT NOT NULL, active TINYINT(1) NOT NULL, country_default TINYINT(1) NOT NULL, iso_code VARCHAR(3) NOT NULL, zip_code_format VARCHAR(12) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mg_customers_addresses (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, gender_id INT NOT NULL, address_lastname VARCHAR(50) NOT NULL, address_firstname VARCHAR(50) NOT NULL, address_compagny VARCHAR(50) DEFAULT NULL, address LONGTEXT NOT NULL, zipcode VARCHAR(5) NOT NULL, town VARCHAR(100) NOT NULL, INDEX IDX_324C40B19395C3F3 (customer_id), INDEX IDX_324C40B1708A0E0 (gender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_customers_addresses ADD CONSTRAINT FK_324C40B19395C3F3 FOREIGN KEY (customer_id) REFERENCES mg_customers (id)');
        $this->addSql('ALTER TABLE mg_customers_addresses ADD CONSTRAINT FK_324C40B1708A0E0 FOREIGN KEY (gender_id) REFERENCES mg_genders (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mg_countries');
        $this->addSql('DROP TABLE mg_customers_addresses');
    }
}
