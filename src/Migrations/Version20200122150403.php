<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200122150403 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_customers ADD CONSTRAINT FK_7882AF78708A0E0 FOREIGN KEY (gender_id) REFERENCES mg_genders (id)');
        $this->addSql('ALTER TABLE mg_genders CHANGE short_civility short_gender VARCHAR(5) NOT NULL, CHANGE name_civility name_gender VARCHAR(30) NOT NULL');
        $this->addSql('ALTER TABLE mg_users ADD CONSTRAINT FK_4FA4F0BE708A0E0 FOREIGN KEY (gender_id) REFERENCES mg_genders (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_customers DROP FOREIGN KEY FK_7882AF78708A0E0');
        $this->addSql('ALTER TABLE mg_genders CHANGE short_gender short_civility VARCHAR(5) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE name_gender name_civility VARCHAR(30) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE mg_users DROP FOREIGN KEY FK_4FA4F0BE708A0E0');
    }
}
