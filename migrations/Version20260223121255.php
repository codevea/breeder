<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260223121255 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9E5E43A8989D9B62 ON cat (slug)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_812C397D989D9B62 ON dog (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_9E5E43A8989D9B62 ON cat');
        $this->addSql('DROP INDEX UNIQ_812C397D989D9B62 ON dog');
    }
}
