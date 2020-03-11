<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200302154809 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mg_carriers_lang (id INT AUTO_INCREMENT NOT NULL, carrier_id INT NOT NULL, lang_id INT NOT NULL, delay VARCHAR(255) DEFAULT NULL, INDEX IDX_14A8BD5021DFC797 (carrier_id), INDEX IDX_14A8BD50B213FA4 (lang_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_carriers_lang ADD CONSTRAINT FK_14A8BD5021DFC797 FOREIGN KEY (carrier_id) REFERENCES mg_carriers (id)');
        $this->addSql('ALTER TABLE mg_carriers_lang ADD CONSTRAINT FK_14A8BD50B213FA4 FOREIGN KEY (lang_id) REFERENCES mg_languages (id)');
        $this->addSql('ALTER TABLE mg_orders DROP INDEX IDX_107B1EECA76ED395, ADD UNIQUE INDEX UNIQ_107B1EECA76ED395 (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mg_carriers_lang');
        $this->addSql('ALTER TABLE mg_orders DROP INDEX UNIQ_107B1EECA76ED395, ADD INDEX IDX_107B1EECA76ED395 (user_id)');
    }
}
