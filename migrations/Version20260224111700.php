<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260224111700 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE breeder ADD slug_cat VARCHAR(255) NOT NULL, ADD slug_dog VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_73DA3D7A8967DDEA ON breeder (slug_cat)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_73DA3D7A9615A73F ON breeder (slug_dog)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_73DA3D7A8967DDEA ON breeder');
        $this->addSql('DROP INDEX UNIQ_73DA3D7A9615A73F ON breeder');
        $this->addSql('ALTER TABLE breeder DROP slug_cat, DROP slug_dog');
    }
}
