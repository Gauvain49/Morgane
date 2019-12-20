<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191220140836 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mg_customers (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, customer_group_id INT DEFAULT NULL, gender_id INT NOT NULL, compagny VARCHAR(100) DEFAULT NULL, birthday DATE DEFAULT NULL, notes LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_7882AF78A76ED395 (user_id), INDEX IDX_7882AF78D2919A68 (customer_group_id), INDEX IDX_7882AF78708A0E0 (gender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_customers ADD CONSTRAINT FK_7882AF78A76ED395 FOREIGN KEY (user_id) REFERENCES mg_users (id)');
        $this->addSql('ALTER TABLE mg_customers ADD CONSTRAINT FK_7882AF78D2919A68 FOREIGN KEY (customer_group_id) REFERENCES mg_customers_groups (id)');
        $this->addSql('ALTER TABLE mg_customers ADD CONSTRAINT FK_7882AF78708A0E0 FOREIGN KEY (gender_id) REFERENCES mg_civilities (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mg_customers');
    }
}
