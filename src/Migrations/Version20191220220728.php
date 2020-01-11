<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191220220728 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mg_posts (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, user_id INT NOT NULL, status VARCHAR(10) NOT NULL, type VARCHAR(15) NOT NULL, mime_type VARCHAR(25) DEFAULT NULL, sizes VARCHAR(100) DEFAULT NULL, position INT NOT NULL, password VARCHAR(100) DEFAULT NULL, comment TINYINT(1) NOT NULL, filename VARCHAR(100) DEFAULT NULL, reserved VARCHAR(25) DEFAULT NULL, date_add DATETIME NOT NULL, date_publish DATETIME NOT NULL, date_expire DATETIME DEFAULT NULL, INDEX IDX_D37AEFAD727ACA70 (parent_id), INDEX IDX_D37AEFADA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_posts ADD CONSTRAINT FK_D37AEFAD727ACA70 FOREIGN KEY (parent_id) REFERENCES mg_posts (id)');
        $this->addSql('ALTER TABLE mg_posts ADD CONSTRAINT FK_D37AEFADA76ED395 FOREIGN KEY (user_id) REFERENCES mg_users (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_posts DROP FOREIGN KEY FK_D37AEFAD727ACA70');
        $this->addSql('DROP TABLE mg_posts');
    }
}
