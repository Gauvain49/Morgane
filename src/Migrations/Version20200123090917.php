<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200123090917 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mg_countries_lang (id INT AUTO_INCREMENT NOT NULL, lang_id INT NOT NULL, country_id INT NOT NULL, country_name VARCHAR(64) NOT NULL, INDEX IDX_C10CD3D7B213FA4 (lang_id), INDEX IDX_C10CD3D7F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_countries_lang ADD CONSTRAINT FK_C10CD3D7B213FA4 FOREIGN KEY (lang_id) REFERENCES mg_languages (id)');
        $this->addSql('ALTER TABLE mg_countries_lang ADD CONSTRAINT FK_C10CD3D7F92F3E70 FOREIGN KEY (country_id) REFERENCES mg_countries (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mg_countries_lang');
    }
}
