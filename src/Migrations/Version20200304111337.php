<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200304111337 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mg_carriers_config (id INT AUTO_INCREMENT NOT NULL, carrier_id INT NOT NULL, billing_on VARCHAR(20) NOT NULL, out_of_range VARCHAR(20) NOT NULL, UNIQUE INDEX UNIQ_66FFD6A421DFC797 (carrier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mg_carriers_steps (id INT AUTO_INCREMENT NOT NULL, config_id INT NOT NULL, step_country_id INT NOT NULL, step_min DOUBLE PRECISION NOT NULL, step_max DOUBLE PRECISION NOT NULL, INDEX IDX_FCD0FACB24DB0683 (config_id), INDEX IDX_FCD0FACBF5441831 (step_country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_carriers_config ADD CONSTRAINT FK_66FFD6A421DFC797 FOREIGN KEY (carrier_id) REFERENCES mg_carriers (id)');
        $this->addSql('ALTER TABLE mg_carriers_steps ADD CONSTRAINT FK_FCD0FACB24DB0683 FOREIGN KEY (config_id) REFERENCES mg_carriers_config (id)');
        $this->addSql('ALTER TABLE mg_carriers_steps ADD CONSTRAINT FK_FCD0FACBF5441831 FOREIGN KEY (step_country_id) REFERENCES mg_countries (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_carriers_steps DROP FOREIGN KEY FK_FCD0FACB24DB0683');
        $this->addSql('DROP TABLE mg_carriers_config');
        $this->addSql('DROP TABLE mg_carriers_steps');
    }
}
