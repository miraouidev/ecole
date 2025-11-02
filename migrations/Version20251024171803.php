<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251024171803 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE groupe_mini (id INT AUTO_INCREMENT NOT NULL, groupe_id INT NOT NULL, nom_ar VARCHAR(150) NOT NULL, nom_fr VARCHAR(150) DEFAULT NULL, INDEX IDX_C8F6E00F7A45358C (groupe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveau_matiere (id INT AUTO_INCREMENT NOT NULL, matiere_id INT NOT NULL, niveau_id INT NOT NULL, is_active TINYINT(1) DEFAULT 1 NOT NULL, INDEX IDX_F000ED3CF46CD258 (matiere_id), INDEX IDX_F000ED3CB3E9C81 (niveau_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE groupe_mini ADD CONSTRAINT FK_C8F6E00F7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
        $this->addSql('ALTER TABLE niveau_matiere ADD CONSTRAINT FK_F000ED3CF46CD258 FOREIGN KEY (matiere_id) REFERENCES matieres (id)');
        $this->addSql('ALTER TABLE niveau_matiere ADD CONSTRAINT FK_F000ED3CB3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id)');
        $this->addSql('ALTER TABLE eleve ADD groupe_mini_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE eleve ADD CONSTRAINT FK_ECA105F767BD2600 FOREIGN KEY (groupe_mini_id) REFERENCES groupe_mini (id)');
        $this->addSql('CREATE INDEX IDX_ECA105F767BD2600 ON eleve (groupe_mini_id)');
        $this->addSql('ALTER TABLE enseignant ADD matiere_id INT NOT NULL');
        $this->addSql('ALTER TABLE enseignant ADD CONSTRAINT FK_81A72FA1F46CD258 FOREIGN KEY (matiere_id) REFERENCES matieres (id)');
        $this->addSql('CREATE INDEX IDX_81A72FA1F46CD258 ON enseignant (matiere_id)');
        $this->addSql('ALTER TABLE matieres DROP FOREIGN KEY FK_8D9773D2B3E9C81');
        $this->addSql('DROP INDEX IDX_8D9773D2B3E9C81 ON matieres');
        $this->addSql('ALTER TABLE matieres DROP niveau_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE eleve DROP FOREIGN KEY FK_ECA105F767BD2600');
        $this->addSql('ALTER TABLE groupe_mini DROP FOREIGN KEY FK_C8F6E00F7A45358C');
        $this->addSql('ALTER TABLE niveau_matiere DROP FOREIGN KEY FK_F000ED3CF46CD258');
        $this->addSql('ALTER TABLE niveau_matiere DROP FOREIGN KEY FK_F000ED3CB3E9C81');
        $this->addSql('DROP TABLE groupe_mini');
        $this->addSql('DROP TABLE niveau_matiere');
        $this->addSql('DROP INDEX IDX_ECA105F767BD2600 ON eleve');
        $this->addSql('ALTER TABLE eleve DROP groupe_mini_id');
        $this->addSql('ALTER TABLE enseignant DROP FOREIGN KEY FK_81A72FA1F46CD258');
        $this->addSql('DROP INDEX IDX_81A72FA1F46CD258 ON enseignant');
        $this->addSql('ALTER TABLE enseignant DROP matiere_id');
        $this->addSql('ALTER TABLE matieres ADD niveau_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE matieres ADD CONSTRAINT FK_8D9773D2B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id)');
        $this->addSql('CREATE INDEX IDX_8D9773D2B3E9C81 ON matieres (niveau_id)');
    }
}
