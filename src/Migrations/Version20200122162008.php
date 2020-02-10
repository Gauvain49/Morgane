<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200122162008 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_customers_addresses ADD country_id INT NOT NULL, ADD phone VARCHAR(20) DEFAULT NULL, ADD mobile VARCHAR(20) DEFAULT NULL, ADD type_address SMALLINT NOT NULL, ADD name_address VARCHAR(64) DEFAULT NULL');
        $this->addSql('ALTER TABLE mg_customers_addresses ADD CONSTRAINT FK_324C40B1F92F3E70 FOREIGN KEY (country_id) REFERENCES mg_countries (id)');
        $this->addSql('CREATE INDEX IDX_324C40B1F92F3E70 ON mg_customers_addresses (country_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_customers_addresses DROP FOREIGN KEY FK_324C40B1F92F3E70');
        $this->addSql('DROP INDEX IDX_324C40B1F92F3E70 ON mg_customers_addresses');
        $this->addSql('ALTER TABLE mg_customers_addresses DROP country_id, DROP phone, DROP mobile, DROP type_address, DROP name_address');
    }
}
