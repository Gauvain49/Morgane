<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191202164848 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mg_gammes_lang (id INT AUTO_INCREMENT NOT NULL, gamme_id INT NOT NULL, lang_id INT NOT NULL, gamme_name VARCHAR(255) NOT NULL, gamme_description LONGTEXT DEFAULT NULL, INDEX IDX_88CDBFEBD2FD85F1 (gamme_id), INDEX IDX_88CDBFEBB213FA4 (lang_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_gammes_lang ADD CONSTRAINT FK_88CDBFEBD2FD85F1 FOREIGN KEY (gamme_id) REFERENCES mg_gammes (id)');
        $this->addSql('ALTER TABLE mg_gammes_lang ADD CONSTRAINT FK_88CDBFEBB213FA4 FOREIGN KEY (lang_id) REFERENCES mg_languages (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mg_gammes_lang');
    }
}
