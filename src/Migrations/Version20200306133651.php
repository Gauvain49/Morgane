<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200306133651 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_carriers_steps DROP FOREIGN KEY FK_FCD0FACBF5441831');
        $this->addSql('DROP INDEX IDX_FCD0FACBF5441831 ON mg_carriers_steps');
        $this->addSql('ALTER TABLE mg_carriers_steps DROP step_country_id, DROP amount');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_carriers_steps ADD step_country_id INT NOT NULL, ADD amount DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE mg_carriers_steps ADD CONSTRAINT FK_FCD0FACBF5441831 FOREIGN KEY (step_country_id) REFERENCES mg_countries (id)');
        $this->addSql('CREATE INDEX IDX_FCD0FACBF5441831 ON mg_carriers_steps (step_country_id)');
    }
}
