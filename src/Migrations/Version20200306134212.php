<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200306134212 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mg_carriers_amount_contries (id INT AUTO_INCREMENT NOT NULL, carrier_step_id INT NOT NULL, step_country_id INT NOT NULL, country_amount DOUBLE PRECISION DEFAULT NULL, INDEX IDX_3E99B875274138D4 (carrier_step_id), INDEX IDX_3E99B875F5441831 (step_country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_carriers_amount_contries ADD CONSTRAINT FK_3E99B875274138D4 FOREIGN KEY (carrier_step_id) REFERENCES mg_carriers_steps (id)');
        $this->addSql('ALTER TABLE mg_carriers_amount_contries ADD CONSTRAINT FK_3E99B875F5441831 FOREIGN KEY (step_country_id) REFERENCES mg_countries (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mg_carriers_amount_contries');
    }
}
