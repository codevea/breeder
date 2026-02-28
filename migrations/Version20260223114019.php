<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260223114019 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE breeder DROP INDEX UNIQ_73DA3D7A5175D8A9, ADD INDEX IDX_73DA3D7A5175D8A9 (business_page_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE breeder DROP INDEX IDX_73DA3D7A5175D8A9, ADD UNIQUE INDEX UNIQ_73DA3D7A5175D8A9 (business_page_id)');
    }
}
