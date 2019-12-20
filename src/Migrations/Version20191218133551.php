<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191218133551 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mg_property_lang (id INT AUTO_INCREMENT NOT NULL, property_id INT NOT NULL, lang_id INT NOT NULL, name VARCHAR(100) NOT NULL, url VARCHAR(100) NOT NULL, INDEX IDX_5575CCC3549213EC (property_id), INDEX IDX_5575CCC3B213FA4 (lang_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_property_lang ADD CONSTRAINT FK_5575CCC3549213EC FOREIGN KEY (property_id) REFERENCES mg_properties (id)');
        $this->addSql('ALTER TABLE mg_property_lang ADD CONSTRAINT FK_5575CCC3B213FA4 FOREIGN KEY (lang_id) REFERENCES mg_languages (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mg_property_lang');
    }
}
