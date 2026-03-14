<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260302183554 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE business_page DROP FOREIGN KEY `FK_4491B42D2052BF25`');
        $this->addSql('DROP INDEX IDX_4491B42D2052BF25 ON business_page');
        $this->addSql('ALTER TABLE business_page DROP affixe_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE business_page ADD affixe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE business_page ADD CONSTRAINT `FK_4491B42D2052BF25` FOREIGN KEY (affixe_id) REFERENCES affixe (id)');
        $this->addSql('CREATE INDEX IDX_4491B42D2052BF25 ON business_page (affixe_id)');
    }
}
