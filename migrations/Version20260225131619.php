<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260225131619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pet (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, icad_id INT NOT NULL, pet_gender_id INT NOT NULL, breeder_id INT NOT NULL, affixe_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_E4529B8584348937 (icad_id), INDEX IDX_E4529B85E29469E4 (pet_gender_id), INDEX IDX_E4529B8533C95BB1 (breeder_id), INDEX IDX_E4529B852052BF25 (affixe_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE pet_gender (id INT AUTO_INCREMENT NOT NULL, gender VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE pet ADD CONSTRAINT FK_E4529B8584348937 FOREIGN KEY (icad_id) REFERENCES icad (id)');
        $this->addSql('ALTER TABLE pet ADD CONSTRAINT FK_E4529B85E29469E4 FOREIGN KEY (pet_gender_id) REFERENCES pet_gender (id)');
        $this->addSql('ALTER TABLE pet ADD CONSTRAINT FK_E4529B8533C95BB1 FOREIGN KEY (breeder_id) REFERENCES breeder (id)');
        $this->addSql('ALTER TABLE pet ADD CONSTRAINT FK_E4529B852052BF25 FOREIGN KEY (affixe_id) REFERENCES affixe (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pet DROP FOREIGN KEY FK_E4529B8584348937');
        $this->addSql('ALTER TABLE pet DROP FOREIGN KEY FK_E4529B85E29469E4');
        $this->addSql('ALTER TABLE pet DROP FOREIGN KEY FK_E4529B8533C95BB1');
        $this->addSql('ALTER TABLE pet DROP FOREIGN KEY FK_E4529B852052BF25');
        $this->addSql('DROP TABLE pet');
        $this->addSql('DROP TABLE pet_gender');
    }
}
