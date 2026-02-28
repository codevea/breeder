<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260224112212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_73DA3D7A8967DDEA ON breeder');
        $this->addSql('DROP INDEX UNIQ_73DA3D7A9615A73F ON breeder');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_73DA3D7A8967DDEA ON breeder (slug_cat)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_73DA3D7A9615A73F ON breeder (slug_dog)');
    }
}
