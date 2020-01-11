<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191228160144 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mg_posts_mg_categories (mg_posts_id INT NOT NULL, mg_categories_id INT NOT NULL, INDEX IDX_AFCA14C5DED77CD1 (mg_posts_id), INDEX IDX_AFCA14C5D976E32C (mg_categories_id), PRIMARY KEY(mg_posts_id, mg_categories_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_posts_mg_categories ADD CONSTRAINT FK_AFCA14C5DED77CD1 FOREIGN KEY (mg_posts_id) REFERENCES mg_posts (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mg_posts_mg_categories ADD CONSTRAINT FK_AFCA14C5D976E32C FOREIGN KEY (mg_categories_id) REFERENCES mg_categories (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mg_posts_mg_categories');
    }
}
