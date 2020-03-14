<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200313111902 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mg_carriers_amount_departments (id INT AUTO_INCREMENT NOT NULL, carrier_step_id INT NOT NULL, step_department_id INT NOT NULL, carrier_config_id INT NOT NULL, department_amount DOUBLE PRECISION NOT NULL, INDEX IDX_5CA877B8274138D4 (carrier_step_id), INDEX IDX_5CA877B8C66629D0 (step_department_id), INDEX IDX_5CA877B8CE761280 (carrier_config_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_carriers_amount_departments ADD CONSTRAINT FK_5CA877B8274138D4 FOREIGN KEY (carrier_step_id) REFERENCES mg_carriers_steps (id)');
        $this->addSql('ALTER TABLE mg_carriers_amount_departments ADD CONSTRAINT FK_5CA877B8C66629D0 FOREIGN KEY (step_department_id) REFERENCES mg_departments_french (id)');
        $this->addSql('ALTER TABLE mg_carriers_amount_departments ADD CONSTRAINT FK_5CA877B8CE761280 FOREIGN KEY (carrier_config_id) REFERENCES mg_carriers_config (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mg_carriers_amount_departments');
    }
}
