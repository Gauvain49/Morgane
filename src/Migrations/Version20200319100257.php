<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200319100257 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_departments_french ADD region_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mg_departments_french ADD CONSTRAINT FK_7B444DF998260155 FOREIGN KEY (region_id) REFERENCES mg_regions_french (id)');
        $this->addSql('CREATE INDEX IDX_7B444DF998260155 ON mg_departments_french (region_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_departments_french DROP FOREIGN KEY FK_7B444DF998260155');
        $this->addSql('DROP INDEX IDX_7B444DF998260155 ON mg_departments_french');
        $this->addSql('ALTER TABLE mg_departments_french DROP region_id');
    }
}
