<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260223121903 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE business_page DROP INDEX IDX_4491B42D81C06096, ADD UNIQUE INDEX UNIQ_4491B42D81C06096 (activity_id)');
        $this->addSql('ALTER TABLE business_page DROP INDEX IDX_4491B42DA76ED395, ADD UNIQUE INDEX UNIQ_4491B42DA76ED395 (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE business_page DROP INDEX UNIQ_4491B42D81C06096, ADD INDEX IDX_4491B42D81C06096 (activity_id)');
        $this->addSql('ALTER TABLE business_page DROP INDEX UNIQ_4491B42DA76ED395, ADD INDEX IDX_4491B42DA76ED395 (user_id)');
    }
}
