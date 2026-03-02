<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260228213806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE business_page_phone (business_page_id INT NOT NULL, phone_id INT NOT NULL, INDEX IDX_E26D60A05175D8A9 (business_page_id), INDEX IDX_E26D60A03B7323CB (phone_id), PRIMARY KEY (business_page_id, phone_id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE business_page_phone ADD CONSTRAINT FK_E26D60A05175D8A9 FOREIGN KEY (business_page_id) REFERENCES business_page (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE business_page_phone ADD CONSTRAINT FK_E26D60A03B7323CB FOREIGN KEY (phone_id) REFERENCES phone (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE business_page_phone DROP FOREIGN KEY FK_E26D60A05175D8A9');
        $this->addSql('ALTER TABLE business_page_phone DROP FOREIGN KEY FK_E26D60A03B7323CB');
        $this->addSql('DROP TABLE business_page_phone');
    }
}
