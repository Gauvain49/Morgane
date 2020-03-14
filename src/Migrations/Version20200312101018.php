<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200312101018 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_carriers_config CHANGE texe_id taxe_id INT NOT NULL');
        $this->addSql('ALTER TABLE mg_carriers_config ADD CONSTRAINT FK_66FFD6A41AB947A4 FOREIGN KEY (taxe_id) REFERENCES mg_taxes (id)');
        $this->addSql('CREATE INDEX IDX_66FFD6A41AB947A4 ON mg_carriers_config (taxe_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_carriers_config DROP FOREIGN KEY FK_66FFD6A41AB947A4');
        $this->addSql('DROP INDEX IDX_66FFD6A41AB947A4 ON mg_carriers_config');
        $this->addSql('ALTER TABLE mg_carriers_config CHANGE taxe_id texe_id INT NOT NULL');
    }
}
