<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200316110553 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mg_carriers_amount_regions (id INT AUTO_INCREMENT NOT NULL, carrier_step_id INT NOT NULL, step_region_id INT NOT NULL, carrier_config_id INT NOT NULL, region_amount DOUBLE PRECISION DEFAULT NULL, INDEX IDX_989F9428274138D4 (carrier_step_id), INDEX IDX_989F9428451B789E (step_region_id), INDEX IDX_989F9428CE761280 (carrier_config_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_carriers_amount_regions ADD CONSTRAINT FK_989F9428274138D4 FOREIGN KEY (carrier_step_id) REFERENCES mg_carriers_steps_regions (id)');
        $this->addSql('ALTER TABLE mg_carriers_amount_regions ADD CONSTRAINT FK_989F9428451B789E FOREIGN KEY (step_region_id) REFERENCES mg_regions_french (id)');
        $this->addSql('ALTER TABLE mg_carriers_amount_regions ADD CONSTRAINT FK_989F9428CE761280 FOREIGN KEY (carrier_config_id) REFERENCES mg_carriers_config (id)');
        $this->addSql('ALTER TABLE mg_carriers_amount_departments CHANGE department_amount department_amount DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mg_carriers_amount_regions');
        $this->addSql('ALTER TABLE mg_carriers_amount_departments CHANGE department_amount department_amount DOUBLE PRECISION NOT NULL');
    }
}
