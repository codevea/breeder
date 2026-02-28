<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260225001150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE breeder ADD slug VARCHAR(255) DEFAULT NULL, DROP slug_cat, DROP slug_dog');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE breeder ADD slug_dog VARCHAR(255) DEFAULT NULL, CHANGE slug slug_cat VARCHAR(255) DEFAULT NULL');
    }
}
