<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200311153350 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_products ADD carrier_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mg_products ADD CONSTRAINT FK_B88F7E4E21DFC797 FOREIGN KEY (carrier_id) REFERENCES mg_carriers (id)');
        $this->addSql('CREATE INDEX IDX_B88F7E4E21DFC797 ON mg_products (carrier_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_products DROP FOREIGN KEY FK_B88F7E4E21DFC797');
        $this->addSql('DROP INDEX IDX_B88F7E4E21DFC797 ON mg_products');
        $this->addSql('ALTER TABLE mg_products DROP carrier_id');
    }
}
