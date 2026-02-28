<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260225125739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pet (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, icad_id INT NOT NULL, gender_pet_id INT NOT NULL, breeder_id INT NOT NULL, affixe_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_E4529B8584348937 (icad_id), INDEX IDX_E4529B851C1A8DE5 (gender_pet_id), INDEX IDX_E4529B8533C95BB1 (breeder_id), INDEX IDX_E4529B852052BF25 (affixe_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE pet ADD CONSTRAINT FK_E4529B8584348937 FOREIGN KEY (icad_id) REFERENCES icad (id)');
        $this->addSql('ALTER TABLE pet ADD CONSTRAINT FK_E4529B851C1A8DE5 FOREIGN KEY (gender_pet_id) REFERENCES gender_pet (id)');
        $this->addSql('ALTER TABLE pet ADD CONSTRAINT FK_E4529B8533C95BB1 FOREIGN KEY (breeder_id) REFERENCES breeder (id)');
        $this->addSql('ALTER TABLE pet ADD CONSTRAINT FK_E4529B852052BF25 FOREIGN KEY (affixe_id) REFERENCES affixe (id)');
        $this->addSql('ALTER TABLE cheptel DROP FOREIGN KEY `FK_F60E2E8D1C1A8DE5`');
        $this->addSql('ALTER TABLE cheptel DROP FOREIGN KEY `FK_F60E2E8D2052BF25`');
        $this->addSql('ALTER TABLE cheptel DROP FOREIGN KEY `FK_F60E2E8D33C95BB1`');
        $this->addSql('ALTER TABLE cheptel DROP FOREIGN KEY `FK_F60E2E8D84348937`');
        $this->addSql('DROP TABLE cheptel');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cheptel (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_uca1400_ai_ci`, icad_id INT NOT NULL, affixe_id INT DEFAULT NULL, gender_pet_id INT NOT NULL, breeder_id INT NOT NULL, INDEX IDX_F60E2E8D2052BF25 (affixe_id), INDEX IDX_F60E2E8D1C1A8DE5 (gender_pet_id), INDEX IDX_F60E2E8D33C95BB1 (breeder_id), UNIQUE INDEX UNIQ_F60E2E8D84348937 (icad_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_uca1400_ai_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE cheptel ADD CONSTRAINT `FK_F60E2E8D1C1A8DE5` FOREIGN KEY (gender_pet_id) REFERENCES gender_pet (id)');
        $this->addSql('ALTER TABLE cheptel ADD CONSTRAINT `FK_F60E2E8D2052BF25` FOREIGN KEY (affixe_id) REFERENCES affixe (id)');
        $this->addSql('ALTER TABLE cheptel ADD CONSTRAINT `FK_F60E2E8D33C95BB1` FOREIGN KEY (breeder_id) REFERENCES breeder (id)');
        $this->addSql('ALTER TABLE cheptel ADD CONSTRAINT `FK_F60E2E8D84348937` FOREIGN KEY (icad_id) REFERENCES icad (id)');
        $this->addSql('ALTER TABLE pet DROP FOREIGN KEY FK_E4529B8584348937');
        $this->addSql('ALTER TABLE pet DROP FOREIGN KEY FK_E4529B851C1A8DE5');
        $this->addSql('ALTER TABLE pet DROP FOREIGN KEY FK_E4529B8533C95BB1');
        $this->addSql('ALTER TABLE pet DROP FOREIGN KEY FK_E4529B852052BF25');
        $this->addSql('DROP TABLE pet');
    }
}
