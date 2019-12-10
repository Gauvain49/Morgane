<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191202165353 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_products ADD gamme_id INT DEFAULT NULL, ADD parent_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mg_products ADD CONSTRAINT FK_B88F7E4ED2FD85F1 FOREIGN KEY (gamme_id) REFERENCES mg_gammes (id)');
        $this->addSql('ALTER TABLE mg_products ADD CONSTRAINT FK_B88F7E4E727ACA70 FOREIGN KEY (parent_id) REFERENCES mg_products (id)');
        $this->addSql('CREATE INDEX IDX_B88F7E4ED2FD85F1 ON mg_products (gamme_id)');
        $this->addSql('CREATE INDEX IDX_B88F7E4E727ACA70 ON mg_products (parent_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mg_products DROP FOREIGN KEY FK_B88F7E4ED2FD85F1');
        $this->addSql('ALTER TABLE mg_products DROP FOREIGN KEY FK_B88F7E4E727ACA70');
        $this->addSql('DROP INDEX IDX_B88F7E4ED2FD85F1 ON mg_products');
        $this->addSql('DROP INDEX IDX_B88F7E4E727ACA70 ON mg_products');
        $this->addSql('ALTER TABLE mg_products DROP gamme_id, DROP parent_id');
    }
}
