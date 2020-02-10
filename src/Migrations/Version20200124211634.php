<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200124211634 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_customers DROP FOREIGN KEY FK_7882AF78708A0E0');
        $this->addSql('DROP INDEX IDX_7882AF78708A0E0 ON mg_customers');
        $this->addSql('ALTER TABLE mg_customers DROP gender_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_customers ADD gender_id INT NOT NULL');
        $this->addSql('ALTER TABLE mg_customers ADD CONSTRAINT FK_7882AF78708A0E0 FOREIGN KEY (gender_id) REFERENCES mg_genders (id)');
        $this->addSql('CREATE INDEX IDX_7882AF78708A0E0 ON mg_customers (gender_id)');
    }
}
