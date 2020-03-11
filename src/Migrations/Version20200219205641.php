<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200219205641 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mg_paypal (id INT AUTO_INCREMENT NOT NULL, mode_id INT NOT NULL, paypal_method VARCHAR(64) DEFAULT NULL, mode_test TINYINT(1) NOT NULL, user_test VARCHAR(100) DEFAULT NULL, password_test VARCHAR(100) DEFAULT NULL, signature_test VARCHAR(100) DEFAULT NULL, link_test VARCHAR(100) DEFAULT NULL, user VARCHAR(100) DEFAULT NULL, password VARCHAR(100) DEFAULT NULL, signature VARCHAR(100) DEFAULT NULL, link VARCHAR(100) DEFAULT NULL, UNIQUE INDEX UNIQ_91C2E14E77E5854A (mode_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_paypal ADD CONSTRAINT FK_91C2E14E77E5854A FOREIGN KEY (mode_id) REFERENCES mg_payments_modes (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mg_paypal');
    }
}
