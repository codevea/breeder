<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260223113329 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE breeder DROP INDEX UNIQ_73DA3D7A634DFEB, ADD INDEX IDX_73DA3D7A634DFEB (dog_id)');
        $this->addSql('ALTER TABLE breeder DROP INDEX UNIQ_73DA3D7AE6ADA943, ADD INDEX IDX_73DA3D7AE6ADA943 (cat_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE breeder DROP INDEX IDX_73DA3D7AE6ADA943, ADD UNIQUE INDEX UNIQ_73DA3D7AE6ADA943 (cat_id)');
        $this->addSql('ALTER TABLE breeder DROP INDEX IDX_73DA3D7A634DFEB, ADD UNIQUE INDEX UNIQ_73DA3D7A634DFEB (dog_id)');
    }
}
