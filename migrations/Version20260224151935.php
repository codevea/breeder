<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260224151935 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE breeder (id INT AUTO_INCREMENT NOT NULL, slug_cat VARCHAR(255) DEFAULT NULL, slug_dog VARCHAR(255) DEFAULT NULL, race_cat_id INT DEFAULT NULL, race_dog_id INT DEFAULT NULL, affixe_id INT DEFAULT NULL, business_page_id INT NOT NULL, cheptel_id INT DEFAULT NULL, INDEX IDX_73DA3D7A8CFBB790 (race_cat_id), INDEX IDX_73DA3D7A6C62C138 (race_dog_id), INDEX IDX_73DA3D7A2052BF25 (affixe_id), INDEX IDX_73DA3D7A5175D8A9 (business_page_id), INDEX IDX_73DA3D7A6313693E (cheptel_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE race_cat (id INT AUTO_INCREMENT NOT NULL, race VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8B550E52DA6FBBAF (race), UNIQUE INDEX UNIQ_8B550E52989D9B62 (slug), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE race_dog (id INT AUTO_INCREMENT NOT NULL, race VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_94277487DA6FBBAF (race), UNIQUE INDEX UNIQ_94277487989D9B62 (slug), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE breeder ADD CONSTRAINT FK_73DA3D7A8CFBB790 FOREIGN KEY (race_cat_id) REFERENCES race_cat (id)');
        $this->addSql('ALTER TABLE breeder ADD CONSTRAINT FK_73DA3D7A6C62C138 FOREIGN KEY (race_dog_id) REFERENCES race_dog (id)');
        $this->addSql('ALTER TABLE breeder ADD CONSTRAINT FK_73DA3D7A2052BF25 FOREIGN KEY (affixe_id) REFERENCES affixe (id)');
        $this->addSql('ALTER TABLE breeder ADD CONSTRAINT FK_73DA3D7A5175D8A9 FOREIGN KEY (business_page_id) REFERENCES business_page (id)');
        $this->addSql('ALTER TABLE breeder ADD CONSTRAINT FK_73DA3D7A6313693E FOREIGN KEY (cheptel_id) REFERENCES cheptel (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE breeder DROP FOREIGN KEY FK_73DA3D7A8CFBB790');
        $this->addSql('ALTER TABLE breeder DROP FOREIGN KEY FK_73DA3D7A6C62C138');
        $this->addSql('ALTER TABLE breeder DROP FOREIGN KEY FK_73DA3D7A2052BF25');
        $this->addSql('ALTER TABLE breeder DROP FOREIGN KEY FK_73DA3D7A5175D8A9');
        $this->addSql('ALTER TABLE breeder DROP FOREIGN KEY FK_73DA3D7A6313693E');
        $this->addSql('DROP TABLE breeder');
        $this->addSql('DROP TABLE race_cat');
        $this->addSql('DROP TABLE race_dog');
    }
}
