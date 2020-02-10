<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200122144555 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_customers DROP FOREIGN KEY FK_7882AF78708A0E0');
        $this->addSql('ALTER TABLE mg_users DROP FOREIGN KEY FK_4FA4F0BE708A0E0');
        $this->addSql('CREATE TABLE mg_genders (id INT AUTO_INCREMENT NOT NULL, lang_id INT NOT NULL, short_civility VARCHAR(5) NOT NULL, name_civility VARCHAR(30) NOT NULL, INDEX IDX_6DE09D72B213FA4 (lang_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_genders ADD CONSTRAINT FK_6DE09D72B213FA4 FOREIGN KEY (lang_id) REFERENCES mg_languages (id)');
        $this->addSql('DROP TABLE mg_civilities');
        $this->addSql('ALTER TABLE mg_customers DROP FOREIGN KEY FK_7882AF78708A0E0');
        $this->addSql('ALTER TABLE mg_customers ADD CONSTRAINT FK_7882AF78708A0E0 FOREIGN KEY (gender_id) REFERENCES mg_genders (id)');
        $this->addSql('ALTER TABLE mg_users DROP FOREIGN KEY FK_4FA4F0BE708A0E0');
        $this->addSql('ALTER TABLE mg_users ADD CONSTRAINT FK_4FA4F0BE708A0E0 FOREIGN KEY (gender_id) REFERENCES mg_genders (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_customers DROP FOREIGN KEY FK_7882AF78708A0E0');
        $this->addSql('ALTER TABLE mg_users DROP FOREIGN KEY FK_4FA4F0BE708A0E0');
        $this->addSql('CREATE TABLE mg_civilities (id INT AUTO_INCREMENT NOT NULL, lang_id INT NOT NULL, short_civility VARCHAR(5) NOT NULL COLLATE utf8mb4_unicode_ci, name_civility VARCHAR(30) NOT NULL COLLATE utf8mb4_unicode_ci, INDEX IDX_D6EFEB77B213FA4 (lang_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE mg_civilities ADD CONSTRAINT FK_D6EFEB77B213FA4 FOREIGN KEY (lang_id) REFERENCES mg_languages (id)');
        $this->addSql('DROP TABLE mg_genders');
        $this->addSql('ALTER TABLE mg_customers DROP FOREIGN KEY FK_7882AF78708A0E0');
        $this->addSql('ALTER TABLE mg_customers ADD CONSTRAINT FK_7882AF78708A0E0 FOREIGN KEY (gender_id) REFERENCES mg_civilities (id)');
        $this->addSql('ALTER TABLE mg_users DROP FOREIGN KEY FK_4FA4F0BE708A0E0');
        $this->addSql('ALTER TABLE mg_users ADD CONSTRAINT FK_4FA4F0BE708A0E0 FOREIGN KEY (gender_id) REFERENCES mg_civilities (id)');
    }
}
