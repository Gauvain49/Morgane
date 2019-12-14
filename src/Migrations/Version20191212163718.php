<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191212163718 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mg_products_numerical (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, filename VARCHAR(255) NOT NULL, use_filename VARCHAR(255) NOT NULL, exclusive TINYINT(1) NOT NULL, date_expire DATETIME DEFAULT NULL, nb_days_accessibles INT DEFAULT NULL, nb_downloadable INT DEFAULT NULL, INDEX IDX_FF0073924584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_products_numerical ADD CONSTRAINT FK_FF0073924584665A FOREIGN KEY (product_id) REFERENCES mg_products (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mg_products_numerical');
    }
}
