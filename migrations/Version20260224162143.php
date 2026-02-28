<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260224162143 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE gender_pet (id INT AUTO_INCREMENT NOT NULL, gender VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE cheptel ADD icad_id INT NOT NULL, ADD affixe_id INT DEFAULT NULL, ADD gender_pet_id INT NOT NULL');
        $this->addSql('ALTER TABLE cheptel ADD CONSTRAINT FK_F60E2E8D84348937 FOREIGN KEY (icad_id) REFERENCES icad (id)');
        $this->addSql('ALTER TABLE cheptel ADD CONSTRAINT FK_F60E2E8D2052BF25 FOREIGN KEY (affixe_id) REFERENCES affixe (id)');
        $this->addSql('ALTER TABLE cheptel ADD CONSTRAINT FK_F60E2E8D1C1A8DE5 FOREIGN KEY (gender_pet_id) REFERENCES gender_pet (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F60E2E8D84348937 ON cheptel (icad_id)');
        $this->addSql('CREATE INDEX IDX_F60E2E8D2052BF25 ON cheptel (affixe_id)');
        $this->addSql('CREATE INDEX IDX_F60E2E8D1C1A8DE5 ON cheptel (gender_pet_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE gender_pet');
        $this->addSql('ALTER TABLE cheptel DROP FOREIGN KEY FK_F60E2E8D84348937');
        $this->addSql('ALTER TABLE cheptel DROP FOREIGN KEY FK_F60E2E8D2052BF25');
        $this->addSql('ALTER TABLE cheptel DROP FOREIGN KEY FK_F60E2E8D1C1A8DE5');
        $this->addSql('DROP INDEX UNIQ_F60E2E8D84348937 ON cheptel');
        $this->addSql('DROP INDEX IDX_F60E2E8D2052BF25 ON cheptel');
        $this->addSql('DROP INDEX IDX_F60E2E8D1C1A8DE5 ON cheptel');
        $this->addSql('ALTER TABLE cheptel DROP icad_id, DROP affixe_id, DROP gender_pet_id');
    }
}
