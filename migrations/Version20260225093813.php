<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260225093813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cheptel ADD breeder_id INT NOT NULL');
        $this->addSql('ALTER TABLE cheptel ADD CONSTRAINT FK_F60E2E8D33C95BB1 FOREIGN KEY (breeder_id) REFERENCES breeder (id)');
        $this->addSql('CREATE INDEX IDX_F60E2E8D33C95BB1 ON cheptel (breeder_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cheptel DROP FOREIGN KEY FK_F60E2E8D33C95BB1');
        $this->addSql('DROP INDEX IDX_F60E2E8D33C95BB1 ON cheptel');
        $this->addSql('ALTER TABLE cheptel DROP breeder_id');
    }
}
