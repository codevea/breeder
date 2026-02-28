<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260223160631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE business_page_activity (business_page_id INT NOT NULL, activity_id INT NOT NULL, INDEX IDX_7EC0C4615175D8A9 (business_page_id), INDEX IDX_7EC0C46181C06096 (activity_id), PRIMARY KEY (business_page_id, activity_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE business_page_activity ADD CONSTRAINT FK_7EC0C4615175D8A9 FOREIGN KEY (business_page_id) REFERENCES business_page (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE business_page_activity ADD CONSTRAINT FK_7EC0C46181C06096 FOREIGN KEY (activity_id) REFERENCES activity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE business_page DROP FOREIGN KEY `FK_4491B42D81C06096`');
        $this->addSql('DROP INDEX IDX_4491B42D81C06096 ON business_page');
        $this->addSql('ALTER TABLE business_page DROP activity_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE business_page_activity DROP FOREIGN KEY FK_7EC0C4615175D8A9');
        $this->addSql('ALTER TABLE business_page_activity DROP FOREIGN KEY FK_7EC0C46181C06096');
        $this->addSql('DROP TABLE business_page_activity');
        $this->addSql('ALTER TABLE business_page ADD activity_id INT NOT NULL');
        $this->addSql('ALTER TABLE business_page ADD CONSTRAINT `FK_4491B42D81C06096` FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('CREATE INDEX IDX_4491B42D81C06096 ON business_page (activity_id)');
    }
}
