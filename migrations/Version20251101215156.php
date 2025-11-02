<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251101215156 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE groupe_matier_type_note (id INT AUTO_INCREMENT NOT NULL, groupe_id INT NOT NULL, matieres_type_note_id INT NOT NULL, admin_valide TINYINT(1) NOT NULL, prof_valide TINYINT(1) NOT NULL, date_affiche DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_EB2E8B1A7A45358C (groupe_id), INDEX IDX_EB2E8B1A2D289746 (matieres_type_note_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE groupe_matier_type_note ADD CONSTRAINT FK_EB2E8B1A7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
        $this->addSql('ALTER TABLE groupe_matier_type_note ADD CONSTRAINT FK_EB2E8B1A2D289746 FOREIGN KEY (matieres_type_note_id) REFERENCES matieres_type_note (id)');
        $this->addSql('ALTER TABLE type_note ADD for_all TINYINT(1) DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE groupe_matier_type_note DROP FOREIGN KEY FK_EB2E8B1A7A45358C');
        $this->addSql('ALTER TABLE groupe_matier_type_note DROP FOREIGN KEY FK_EB2E8B1A2D289746');
        $this->addSql('DROP TABLE groupe_matier_type_note');
        $this->addSql('ALTER TABLE type_note DROP for_all');
    }
}
