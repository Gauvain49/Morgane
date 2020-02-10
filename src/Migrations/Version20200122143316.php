<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200122143316 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_users ADD gender_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mg_users ADD CONSTRAINT FK_4FA4F0BE708A0E0 FOREIGN KEY (gender_id) REFERENCES mg_civilities (id)');
        $this->addSql('CREATE INDEX IDX_4FA4F0BE708A0E0 ON mg_users (gender_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_users DROP FOREIGN KEY FK_4FA4F0BE708A0E0');
        $this->addSql('DROP INDEX IDX_4FA4F0BE708A0E0 ON mg_users');
        $this->addSql('ALTER TABLE mg_users DROP gender_id');
    }
}
