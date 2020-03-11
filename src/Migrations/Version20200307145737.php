<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200307145737 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_carriers_amount_countries ADD carrier_config_id INT NOT NULL');
        $this->addSql('ALTER TABLE mg_carriers_amount_countries ADD CONSTRAINT FK_E1115344CE761280 FOREIGN KEY (carrier_config_id) REFERENCES mg_carriers_config (id)');
        $this->addSql('CREATE INDEX IDX_E1115344CE761280 ON mg_carriers_amount_countries (carrier_config_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_carriers_amount_countries DROP FOREIGN KEY FK_E1115344CE761280');
        $this->addSql('DROP INDEX IDX_E1115344CE761280 ON mg_carriers_amount_countries');
        $this->addSql('ALTER TABLE mg_carriers_amount_countries DROP carrier_config_id');
    }
}
