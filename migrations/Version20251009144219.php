<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251009144219 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rh_conge (id INT AUTO_INCREMENT NOT NULL, employee_id INT NOT NULL, type_conge_id INT DEFAULT NULL, date_debut DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_fin DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', statut VARCHAR(20) NOT NULL, commentaire VARCHAR(255) DEFAULT NULL, INDEX IDX_262C95718C03F15C (employee_id), INDEX IDX_262C9571753BDA5 (type_conge_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rh_employe (id INT AUTO_INCREMENT NOT NULL, civilite_id INT NOT NULL, status_famille_id INT NOT NULL, nom_ar VARCHAR(255) NOT NULL, prenom_ar VARCHAR(255) NOT NULL, nom_fr VARCHAR(255) DEFAULT NULL, prenom_fr VARCHAR(255) DEFAULT NULL, date_embauche DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_fin_embauche DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', heures_par_semaine INT DEFAULT NULL, mobile VARCHAR(15) NOT NULL, fix VARCHAR(15) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, conge_disponible DOUBLE PRECISION NOT NULL, INDEX IDX_1FE3F3C239194ABF (civilite_id), INDEX IDX_1FE3F3C296F49F02 (status_famille_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rh_jour_ferie (id INT AUTO_INCREMENT NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', libelle_ar VARCHAR(255) NOT NULL, libelle_fr VARCHAR(255) NOT NULL, paye TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rh_status_famille (id INT AUTO_INCREMENT NOT NULL, nom_ar VARCHAR(255) NOT NULL, nom_fr VARCHAR(255) NOT NULL, code VARCHAR(5) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rh_type_conge (id INT AUTO_INCREMENT NOT NULL, libelle_ar VARCHAR(255) NOT NULL, libelle_fr VARCHAR(255) DEFAULT NULL, coleur VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rh_conge ADD CONSTRAINT FK_262C95718C03F15C FOREIGN KEY (employee_id) REFERENCES rh_employe (id)');
        $this->addSql('ALTER TABLE rh_conge ADD CONSTRAINT FK_262C9571753BDA5 FOREIGN KEY (type_conge_id) REFERENCES rh_type_conge (id)');
        $this->addSql('ALTER TABLE rh_employe ADD CONSTRAINT FK_1FE3F3C239194ABF FOREIGN KEY (civilite_id) REFERENCES civilite (id)');
        $this->addSql('ALTER TABLE rh_employe ADD CONSTRAINT FK_1FE3F3C296F49F02 FOREIGN KEY (status_famille_id) REFERENCES rh_status_famille (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rh_conge DROP FOREIGN KEY FK_262C95718C03F15C');
        $this->addSql('ALTER TABLE rh_conge DROP FOREIGN KEY FK_262C9571753BDA5');
        $this->addSql('ALTER TABLE rh_employe DROP FOREIGN KEY FK_1FE3F3C239194ABF');
        $this->addSql('ALTER TABLE rh_employe DROP FOREIGN KEY FK_1FE3F3C296F49F02');
        $this->addSql('DROP TABLE rh_conge');
        $this->addSql('DROP TABLE rh_employe');
        $this->addSql('DROP TABLE rh_jour_ferie');
        $this->addSql('DROP TABLE rh_status_famille');
        $this->addSql('DROP TABLE rh_type_conge');
    }
}
