<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260224122653 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cheptel (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE breeder ADD cheptel_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE breeder ADD CONSTRAINT FK_73DA3D7A6313693E FOREIGN KEY (cheptel_id) REFERENCES cheptel (id)');
        $this->addSql('CREATE INDEX IDX_73DA3D7A6313693E ON breeder (cheptel_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE cheptel');
        $this->addSql('ALTER TABLE breeder DROP FOREIGN KEY FK_73DA3D7A6313693E');
        $this->addSql('DROP INDEX IDX_73DA3D7A6313693E ON breeder');
        $this->addSql('ALTER TABLE breeder DROP cheptel_id');
    }
}
