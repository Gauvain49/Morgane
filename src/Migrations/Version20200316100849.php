<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200316100849 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mg_regions_french (id INT AUTO_INCREMENT NOT NULL, code_iso VARCHAR(10) NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mg_carriers_amount_departments DROP FOREIGN KEY FK_5CA877B8274138D4');
        $this->addSql('ALTER TABLE mg_carriers_amount_departments ADD CONSTRAINT FK_5CA877B8274138D4 FOREIGN KEY (carrier_step_id) REFERENCES mg_carriers_steps_dep (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE mg_regions_french');
        $this->addSql('ALTER TABLE mg_carriers_amount_departments DROP FOREIGN KEY FK_5CA877B8274138D4');
        $this->addSql('ALTER TABLE mg_carriers_amount_departments ADD CONSTRAINT FK_5CA877B8274138D4 FOREIGN KEY (carrier_step_id) REFERENCES mg_carriers_steps (id)');
    }
}
