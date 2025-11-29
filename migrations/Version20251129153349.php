<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251129153349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE matiere_niveau (id INT AUTO_INCREMENT NOT NULL, matiere_id INT NOT NULL, niveau_id INT NOT NULL, annee_scolaire_id INT NOT NULL, type_note_id INT NOT NULL, formule_moyenne LONGTEXT DEFAULT NULL, coefficient DOUBLE PRECISION DEFAULT NULL, INDEX IDX_6B3CD676F46CD258 (matiere_id), INDEX IDX_6B3CD676B3E9C81 (niveau_id), INDEX IDX_6B3CD6769331C741 (annee_scolaire_id), INDEX IDX_6B3CD676ECC67A0 (type_note_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE matiere_niveau ADD CONSTRAINT FK_6B3CD676F46CD258 FOREIGN KEY (matiere_id) REFERENCES matieres (id)');
        $this->addSql('ALTER TABLE matiere_niveau ADD CONSTRAINT FK_6B3CD676B3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau (id)');
        $this->addSql('ALTER TABLE matiere_niveau ADD CONSTRAINT FK_6B3CD6769331C741 FOREIGN KEY (annee_scolaire_id) REFERENCES annee_scolaire_courante (id)');
        $this->addSql('ALTER TABLE matiere_niveau ADD CONSTRAINT FK_6B3CD676ECC67A0 FOREIGN KEY (type_note_id) REFERENCES type_note (id)');
        $this->addSql('ALTER TABLE matieres_type_note DROP coefficient');
        $this->addSql('ALTER TABLE note_eleve DROP coefficient');
        $this->addSql('ALTER TABLE type_note ADD code VARCHAR(20) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_18B23F1C77153098 ON type_note (code)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matiere_niveau DROP FOREIGN KEY FK_6B3CD676F46CD258');
        $this->addSql('ALTER TABLE matiere_niveau DROP FOREIGN KEY FK_6B3CD676B3E9C81');
        $this->addSql('ALTER TABLE matiere_niveau DROP FOREIGN KEY FK_6B3CD6769331C741');
        $this->addSql('ALTER TABLE matiere_niveau DROP FOREIGN KEY FK_6B3CD676ECC67A0');
        $this->addSql('DROP TABLE matiere_niveau');
        $this->addSql('ALTER TABLE matieres_type_note ADD coefficient DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE note_eleve ADD coefficient DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_18B23F1C77153098 ON type_note');
        $this->addSql('ALTER TABLE type_note DROP code');
    }
}
