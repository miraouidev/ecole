<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251025153737 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE configuration (id INT AUTO_INCREMENT NOT NULL, is_modifier_type_note TINYINT(1) DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matieres_type_note (id INT AUTO_INCREMENT NOT NULL, matiere_id INT NOT NULL, type_note_id INT NOT NULL, semestre_id INT NOT NULL, is_valide_prof TINYINT(1) NOT NULL, is_valide_admin TINYINT(1) NOT NULL, date_affiche DATETIME DEFAULT NULL, INDEX IDX_B6008BFF46CD258 (matiere_id), INDEX IDX_B6008BFECC67A0 (type_note_id), INDEX IDX_B6008BF5577AFDB (semestre_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE note_eleve (id INT AUTO_INCREMENT NOT NULL, eleve_id INT NOT NULL, type_note_id INT NOT NULL, valeur DOUBLE PRECISION DEFAULT NULL, coefficient DOUBLE PRECISION DEFAULT NULL, INDEX IDX_89B1A620A6CC7B2 (eleve_id), INDEX IDX_89B1A620ECC67A0 (type_note_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resultat (id INT AUTO_INCREMENT NOT NULL, eleve_id INT DEFAULT NULL, is_admis TINYINT(1) DEFAULT NULL, moyenne_semestre1 DOUBLE PRECISION DEFAULT NULL, moyenne_semestre2 DOUBLE PRECISION DEFAULT NULL, moyenne_semestre3 DOUBLE PRECISION DEFAULT NULL, INDEX IDX_E7DB5DE2A6CC7B2 (eleve_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE semestre (id INT AUTO_INCREMENT NOT NULL, annee_scolaire_id INT NOT NULL, number INT NOT NULL, nom_ar VARCHAR(255) NOT NULL, nom_fr VARCHAR(255) NOT NULL, is_remplie TINYINT(1) DEFAULT 1 NOT NULL, is_affiche TINYINT(1) DEFAULT 1 NOT NULL, INDEX IDX_71688FBC9331C741 (annee_scolaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_note (id INT AUTO_INCREMENT NOT NULL, nom_ar VARCHAR(100) NOT NULL, nom_fr VARCHAR(100) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE matieres_type_note ADD CONSTRAINT FK_B6008BFF46CD258 FOREIGN KEY (matiere_id) REFERENCES matieres (id)');
        $this->addSql('ALTER TABLE matieres_type_note ADD CONSTRAINT FK_B6008BFECC67A0 FOREIGN KEY (type_note_id) REFERENCES type_note (id)');
        $this->addSql('ALTER TABLE matieres_type_note ADD CONSTRAINT FK_B6008BF5577AFDB FOREIGN KEY (semestre_id) REFERENCES semestre (id)');
        $this->addSql('ALTER TABLE note_eleve ADD CONSTRAINT FK_89B1A620A6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleve (id)');
        $this->addSql('ALTER TABLE note_eleve ADD CONSTRAINT FK_89B1A620ECC67A0 FOREIGN KEY (type_note_id) REFERENCES matieres_type_note (id)');
        $this->addSql('ALTER TABLE resultat ADD CONSTRAINT FK_E7DB5DE2A6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleve (id)');
        $this->addSql('ALTER TABLE semestre ADD CONSTRAINT FK_71688FBC9331C741 FOREIGN KEY (annee_scolaire_id) REFERENCES annee_scolaire_courante (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matieres_type_note DROP FOREIGN KEY FK_B6008BFF46CD258');
        $this->addSql('ALTER TABLE matieres_type_note DROP FOREIGN KEY FK_B6008BFECC67A0');
        $this->addSql('ALTER TABLE matieres_type_note DROP FOREIGN KEY FK_B6008BF5577AFDB');
        $this->addSql('ALTER TABLE note_eleve DROP FOREIGN KEY FK_89B1A620A6CC7B2');
        $this->addSql('ALTER TABLE note_eleve DROP FOREIGN KEY FK_89B1A620ECC67A0');
        $this->addSql('ALTER TABLE resultat DROP FOREIGN KEY FK_E7DB5DE2A6CC7B2');
        $this->addSql('ALTER TABLE semestre DROP FOREIGN KEY FK_71688FBC9331C741');
        $this->addSql('DROP TABLE configuration');
        $this->addSql('DROP TABLE matieres_type_note');
        $this->addSql('DROP TABLE note_eleve');
        $this->addSql('DROP TABLE resultat');
        $this->addSql('DROP TABLE semestre');
        $this->addSql('DROP TABLE type_note');
    }
}
