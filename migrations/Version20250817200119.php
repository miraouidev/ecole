<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250817200119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admininstrateur (id INT AUTO_INCREMENT NOT NULL, civilite_id INT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, date_nai DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', start_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', cin VARCHAR(15) DEFAULT NULL, phone VARCHAR(15) DEFAULT NULL, mobile VARCHAR(15) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, INDEX IDX_60F08A0A39194ABF (civilite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE annee_scolaire_courante (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE civilite (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, code VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE eleve (id INT AUTO_INCREMENT NOT NULL, groupe_id INT NOT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, date_nai DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_ECA105F77A45358C (groupe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE enseignant (id INT AUTO_INCREMENT NOT NULL, civilite_id INT DEFAULT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, date_nai DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', start_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', cin VARCHAR(15) DEFAULT NULL, phone VARCHAR(15) DEFAULT NULL, mobile VARCHAR(15) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, INDEX IDX_81A72FA139194ABF (civilite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fonction (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, code VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupe (id INT AUTO_INCREMENT NOT NULL, niveau_id INT NOT NULL, annee_scolaire_id INT NOT NULL, nom VARCHAR(100) NOT NULL, INDEX IDX_4B98C21B3E9C81 (niveau_id), INDEX IDX_4B98C219331C741 (annee_scolaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE langue (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(5) NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE log_api (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, date_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', method VARCHAR(10) DEFAULT NULL, contenu JSON DEFAULT NULL, ip VARCHAR(30) DEFAULT NULL, utilisateur VARCHAR(150) DEFAULT NULL, device VARCHAR(255) DEFAULT NULL, INDEX IDX_C2E0F1D4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere_classe_prof (id INT AUTO_INCREMENT NOT NULL, groupe_id INT NOT NULL, INDEX IDX_741543397A45358C (groupe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matieres (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, link VARCHAR(255) NOT NULL, info VARCHAR(255) DEFAULT NULL, dossier VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE niveau (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parent_eleve_relation (id INT AUTO_INCREMENT NOT NULL, eleve_id INT DEFAULT NULL, parent_id INT NOT NULL, INDEX IDX_68B9D536A6CC7B2 (eleve_id), INDEX IDX_68B9D536727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parent_profile (id INT AUTO_INCREMENT NOT NULL, civilite_id INT DEFAULT NULL, nom VARCHAR(100) NOT NULL, prenom VARCHAR(100) NOT NULL, date_nai DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', start_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', cin VARCHAR(15) DEFAULT NULL, phone VARCHAR(15) DEFAULT NULL, mobile VARCHAR(15) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, INDEX IDX_F31FDE4939194ABF (civilite_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site_about_ticket (id INT AUTO_INCREMENT NOT NULL, langue_id INT NOT NULL, titre VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) NOT NULL, lien_page VARCHAR(255) DEFAULT NULL, INDEX IDX_4A2D87112AADBACD (langue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site_about_us (id INT AUTO_INCREMENT NOT NULL, langue_id INT NOT NULL, link_video VARCHAR(255) DEFAULT NULL, titre VARCHAR(255) DEFAULT NULL, text LONGTEXT DEFAULT NULL, is_active TINYINT(1) NOT NULL, lien_page VARCHAR(255) DEFAULT NULL, INDEX IDX_B96EBC192AADBACD (langue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site_event (id INT AUTO_INCREMENT NOT NULL, langue_id INT NOT NULL, titre VARCHAR(255) DEFAULT NULL, text VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) NOT NULL, lien_page VARCHAR(255) DEFAULT NULL, INDEX IDX_FFF4DAA22AADBACD (langue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site_event_ticket (id INT AUTO_INCREMENT NOT NULL, langue_id INT NOT NULL, image VARCHAR(255) DEFAULT NULL, date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', hour_start VARCHAR(10) DEFAULT NULL, hour_end VARCHAR(10) DEFAULT NULL, titre VARCHAR(255) DEFAULT NULL, text VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) NOT NULL, lien_page VARCHAR(255) DEFAULT NULL, INDEX IDX_6EB347422AADBACD (langue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site_footer (id INT AUTO_INCREMENT NOT NULL, langue_id INT NOT NULL, text VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) NOT NULL, lien_page VARCHAR(255) DEFAULT NULL, INDEX IDX_929FAB0C2AADBACD (langue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site_header (id INT AUTO_INCREMENT NOT NULL, langue_id INT NOT NULL, phone VARCHAR(20) DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) NOT NULL, lien_page VARCHAR(255) DEFAULT NULL, INDEX IDX_1EDC069E2AADBACD (langue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site_our_program (id INT AUTO_INCREMENT NOT NULL, langue_id INT NOT NULL, titre VARCHAR(255) DEFAULT NULL, text VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) NOT NULL, lien_page VARCHAR(255) DEFAULT NULL, INDEX IDX_E3700BD2AADBACD (langue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site_our_program_ticket (id INT AUTO_INCREMENT NOT NULL, langue_id INT NOT NULL, image VARCHAR(255) DEFAULT NULL, titre VARCHAR(255) DEFAULT NULL, text VARCHAR(255) DEFAULT NULL, prix DOUBLE PRECISION DEFAULT NULL, nombre INT DEFAULT NULL, nombre_heure INT DEFAULT NULL, lesson INT DEFAULT NULL, is_active TINYINT(1) NOT NULL, lien_page VARCHAR(255) DEFAULT NULL, INDEX IDX_DCEF77E52AADBACD (langue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site_our_teams (id INT AUTO_INCREMENT NOT NULL, enseignant_id INT NOT NULL, ordre INT NOT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_31A61D3FE455FCC0 (enseignant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site_page_generique (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) DEFAULT NULL, contenu LONGTEXT DEFAULT NULL, link_image VARCHAR(255) DEFAULT NULL, type_page VARCHAR(50) NOT NULL, url VARCHAR(255) DEFAULT NULL, create_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site_reseau (id INT AUTO_INCREMENT NOT NULL, langue_id INT NOT NULL, name VARCHAR(255) DEFAULT NULL, code VARCHAR(10) NOT NULL, link VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) NOT NULL, lien_page VARCHAR(255) DEFAULT NULL, INDEX IDX_BD4B82E72AADBACD (langue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site_testimonials (id INT AUTO_INCREMENT NOT NULL, langue_id INT NOT NULL, client_name VARCHAR(255) DEFAULT NULL, nombre_etoile INT DEFAULT NULL, text VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) NOT NULL, lien_page VARCHAR(255) DEFAULT NULL, INDEX IDX_F3BB88CA2AADBACD (langue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site_top_image (id INT AUTO_INCREMENT NOT NULL, langue_id INT NOT NULL, image VARCHAR(255) DEFAULT NULL, titre VARCHAR(255) DEFAULT NULL, text VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) NOT NULL, lien_page VARCHAR(255) DEFAULT NULL, INDEX IDX_3628DB5E2AADBACD (langue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site_we_what (id INT AUTO_INCREMENT NOT NULL, langue_id INT NOT NULL, titre VARCHAR(255) DEFAULT NULL, text LONGTEXT DEFAULT NULL, is_active TINYINT(1) NOT NULL, lien_page VARCHAR(255) DEFAULT NULL, INDEX IDX_36DFF89D2AADBACD (langue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE site_we_what_ticket (id INT AUTO_INCREMENT NOT NULL, langue_id INT NOT NULL, icone VARCHAR(255) DEFAULT NULL, titre VARCHAR(255) DEFAULT NULL, text VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) NOT NULL, lien_page VARCHAR(255) DEFAULT NULL, INDEX IDX_C7A8895D2AADBACD (langue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_relation (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, code VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, admininstrateur_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, email VARCHAR(255) DEFAULT NULL, type VARCHAR(20) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D6491707C25B (admininstrateur_id), UNIQUE INDEX UNIQ_8D93D649727ACA70 (parent_id), UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE admininstrateur ADD CONSTRAINT FK_60F08A0A39194ABF FOREIGN KEY (civilite_id) REFERENCES civilite (id)');
        $this->addSql('ALTER TABLE eleve ADD CONSTRAINT FK_ECA105F77A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
        $this->addSql('ALTER TABLE enseignant ADD CONSTRAINT FK_81A72FA139194ABF FOREIGN KEY (civilite_id) REFERENCES civilite (id)');
        $this->addSql('ALTER TABLE groupe ADD CONSTRAINT FK_4B98C21B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id)');
        $this->addSql('ALTER TABLE groupe ADD CONSTRAINT FK_4B98C219331C741 FOREIGN KEY (annee_scolaire_id) REFERENCES annee_scolaire_courante (id)');
        $this->addSql('ALTER TABLE log_api ADD CONSTRAINT FK_C2E0F1D4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE matiere_classe_prof ADD CONSTRAINT FK_741543397A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
        $this->addSql('ALTER TABLE parent_eleve_relation ADD CONSTRAINT FK_68B9D536A6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleve (id)');
        $this->addSql('ALTER TABLE parent_eleve_relation ADD CONSTRAINT FK_68B9D536727ACA70 FOREIGN KEY (parent_id) REFERENCES parent_profile (id)');
        $this->addSql('ALTER TABLE parent_profile ADD CONSTRAINT FK_F31FDE4939194ABF FOREIGN KEY (civilite_id) REFERENCES civilite (id)');
        $this->addSql('ALTER TABLE site_about_ticket ADD CONSTRAINT FK_4A2D87112AADBACD FOREIGN KEY (langue_id) REFERENCES langue (id)');
        $this->addSql('ALTER TABLE site_about_us ADD CONSTRAINT FK_B96EBC192AADBACD FOREIGN KEY (langue_id) REFERENCES langue (id)');
        $this->addSql('ALTER TABLE site_event ADD CONSTRAINT FK_FFF4DAA22AADBACD FOREIGN KEY (langue_id) REFERENCES langue (id)');
        $this->addSql('ALTER TABLE site_event_ticket ADD CONSTRAINT FK_6EB347422AADBACD FOREIGN KEY (langue_id) REFERENCES langue (id)');
        $this->addSql('ALTER TABLE site_footer ADD CONSTRAINT FK_929FAB0C2AADBACD FOREIGN KEY (langue_id) REFERENCES langue (id)');
        $this->addSql('ALTER TABLE site_header ADD CONSTRAINT FK_1EDC069E2AADBACD FOREIGN KEY (langue_id) REFERENCES langue (id)');
        $this->addSql('ALTER TABLE site_our_program ADD CONSTRAINT FK_E3700BD2AADBACD FOREIGN KEY (langue_id) REFERENCES langue (id)');
        $this->addSql('ALTER TABLE site_our_program_ticket ADD CONSTRAINT FK_DCEF77E52AADBACD FOREIGN KEY (langue_id) REFERENCES langue (id)');
        $this->addSql('ALTER TABLE site_our_teams ADD CONSTRAINT FK_31A61D3FE455FCC0 FOREIGN KEY (enseignant_id) REFERENCES enseignant (id)');
        $this->addSql('ALTER TABLE site_reseau ADD CONSTRAINT FK_BD4B82E72AADBACD FOREIGN KEY (langue_id) REFERENCES langue (id)');
        $this->addSql('ALTER TABLE site_testimonials ADD CONSTRAINT FK_F3BB88CA2AADBACD FOREIGN KEY (langue_id) REFERENCES langue (id)');
        $this->addSql('ALTER TABLE site_top_image ADD CONSTRAINT FK_3628DB5E2AADBACD FOREIGN KEY (langue_id) REFERENCES langue (id)');
        $this->addSql('ALTER TABLE site_we_what ADD CONSTRAINT FK_36DFF89D2AADBACD FOREIGN KEY (langue_id) REFERENCES langue (id)');
        $this->addSql('ALTER TABLE site_we_what_ticket ADD CONSTRAINT FK_C7A8895D2AADBACD FOREIGN KEY (langue_id) REFERENCES langue (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6491707C25B FOREIGN KEY (admininstrateur_id) REFERENCES admininstrateur (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649727ACA70 FOREIGN KEY (parent_id) REFERENCES parent_profile (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admininstrateur DROP FOREIGN KEY FK_60F08A0A39194ABF');
        $this->addSql('ALTER TABLE eleve DROP FOREIGN KEY FK_ECA105F77A45358C');
        $this->addSql('ALTER TABLE enseignant DROP FOREIGN KEY FK_81A72FA139194ABF');
        $this->addSql('ALTER TABLE groupe DROP FOREIGN KEY FK_4B98C21B3E9C81');
        $this->addSql('ALTER TABLE groupe DROP FOREIGN KEY FK_4B98C219331C741');
        $this->addSql('ALTER TABLE log_api DROP FOREIGN KEY FK_C2E0F1D4A76ED395');
        $this->addSql('ALTER TABLE matiere_classe_prof DROP FOREIGN KEY FK_741543397A45358C');
        $this->addSql('ALTER TABLE parent_eleve_relation DROP FOREIGN KEY FK_68B9D536A6CC7B2');
        $this->addSql('ALTER TABLE parent_eleve_relation DROP FOREIGN KEY FK_68B9D536727ACA70');
        $this->addSql('ALTER TABLE parent_profile DROP FOREIGN KEY FK_F31FDE4939194ABF');
        $this->addSql('ALTER TABLE site_about_ticket DROP FOREIGN KEY FK_4A2D87112AADBACD');
        $this->addSql('ALTER TABLE site_about_us DROP FOREIGN KEY FK_B96EBC192AADBACD');
        $this->addSql('ALTER TABLE site_event DROP FOREIGN KEY FK_FFF4DAA22AADBACD');
        $this->addSql('ALTER TABLE site_event_ticket DROP FOREIGN KEY FK_6EB347422AADBACD');
        $this->addSql('ALTER TABLE site_footer DROP FOREIGN KEY FK_929FAB0C2AADBACD');
        $this->addSql('ALTER TABLE site_header DROP FOREIGN KEY FK_1EDC069E2AADBACD');
        $this->addSql('ALTER TABLE site_our_program DROP FOREIGN KEY FK_E3700BD2AADBACD');
        $this->addSql('ALTER TABLE site_our_program_ticket DROP FOREIGN KEY FK_DCEF77E52AADBACD');
        $this->addSql('ALTER TABLE site_our_teams DROP FOREIGN KEY FK_31A61D3FE455FCC0');
        $this->addSql('ALTER TABLE site_reseau DROP FOREIGN KEY FK_BD4B82E72AADBACD');
        $this->addSql('ALTER TABLE site_testimonials DROP FOREIGN KEY FK_F3BB88CA2AADBACD');
        $this->addSql('ALTER TABLE site_top_image DROP FOREIGN KEY FK_3628DB5E2AADBACD');
        $this->addSql('ALTER TABLE site_we_what DROP FOREIGN KEY FK_36DFF89D2AADBACD');
        $this->addSql('ALTER TABLE site_we_what_ticket DROP FOREIGN KEY FK_C7A8895D2AADBACD');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6491707C25B');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649727ACA70');
        $this->addSql('DROP TABLE admininstrateur');
        $this->addSql('DROP TABLE annee_scolaire_courante');
        $this->addSql('DROP TABLE civilite');
        $this->addSql('DROP TABLE eleve');
        $this->addSql('DROP TABLE enseignant');
        $this->addSql('DROP TABLE fonction');
        $this->addSql('DROP TABLE groupe');
        $this->addSql('DROP TABLE langue');
        $this->addSql('DROP TABLE log_api');
        $this->addSql('DROP TABLE matiere_classe_prof');
        $this->addSql('DROP TABLE matieres');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE niveau');
        $this->addSql('DROP TABLE parent_eleve_relation');
        $this->addSql('DROP TABLE parent_profile');
        $this->addSql('DROP TABLE site');
        $this->addSql('DROP TABLE site_about_ticket');
        $this->addSql('DROP TABLE site_about_us');
        $this->addSql('DROP TABLE site_event');
        $this->addSql('DROP TABLE site_event_ticket');
        $this->addSql('DROP TABLE site_footer');
        $this->addSql('DROP TABLE site_header');
        $this->addSql('DROP TABLE site_our_program');
        $this->addSql('DROP TABLE site_our_program_ticket');
        $this->addSql('DROP TABLE site_our_teams');
        $this->addSql('DROP TABLE site_page_generique');
        $this->addSql('DROP TABLE site_reseau');
        $this->addSql('DROP TABLE site_testimonials');
        $this->addSql('DROP TABLE site_top_image');
        $this->addSql('DROP TABLE site_we_what');
        $this->addSql('DROP TABLE site_we_what_ticket');
        $this->addSql('DROP TABLE type_relation');
        $this->addSql('DROP TABLE user');
    }
}
