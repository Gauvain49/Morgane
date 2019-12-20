<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191218134254 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mg_properties_contents (id INT AUTO_INCREMENT NOT NULL, property_id INT NOT NULL, lang_id INT NOT NULL, product_id INT NOT NULL, value VARCHAR(255) NOT NULL, INDEX IDX_90EE452B549213EC (property_id), INDEX IDX_90EE452BB213FA4 (lang_id), INDEX IDX_90EE452B4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_properties_contents ADD CONSTRAINT FK_90EE452B549213EC FOREIGN KEY (property_id) REFERENCES mg_properties (id)');
        $this->addSql('ALTER TABLE mg_properties_contents ADD CONSTRAINT FK_90EE452BB213FA4 FOREIGN KEY (lang_id) REFERENCES mg_languages (id)');
        $this->addSql('ALTER TABLE mg_properties_contents ADD CONSTRAINT FK_90EE452B4584665A FOREIGN KEY (product_id) REFERENCES mg_products (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mg_properties_contents');
    }
}
