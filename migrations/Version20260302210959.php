<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260302210959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE web_site (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE breeder DROP FOREIGN KEY `FK_73DA3D7AF6BD1646`');
        $this->addSql('DROP INDEX IDX_73DA3D7AF6BD1646 ON breeder');
        $this->addSql('ALTER TABLE breeder CHANGE site_id web_site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE breeder ADD CONSTRAINT FK_73DA3D7A1E12B8D8 FOREIGN KEY (web_site_id) REFERENCES web_site (id)');
        $this->addSql('CREATE INDEX IDX_73DA3D7A1E12B8D8 ON breeder (web_site_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE web_site');
        $this->addSql('ALTER TABLE breeder DROP FOREIGN KEY FK_73DA3D7A1E12B8D8');
        $this->addSql('DROP INDEX IDX_73DA3D7A1E12B8D8 ON breeder');
        $this->addSql('ALTER TABLE breeder CHANGE web_site_id site_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE breeder ADD CONSTRAINT `FK_73DA3D7AF6BD1646` FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('CREATE INDEX IDX_73DA3D7AF6BD1646 ON breeder (site_id)');
    }
}
