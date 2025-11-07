<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251106161145 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE scolarite (id INT AUTO_INCREMENT NOT NULL, eleve_id INT NOT NULL, groupe_id INT NOT NULL, annee_id INT NOT NULL, is_active TINYINT(1) DEFAULT 1 NOT NULL, INDEX IDX_276250ABA6CC7B2 (eleve_id), INDEX IDX_276250AB7A45358C (groupe_id), INDEX IDX_276250AB543EC5F0 (annee_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE scolarite ADD CONSTRAINT FK_276250ABA6CC7B2 FOREIGN KEY (eleve_id) REFERENCES eleve (id)');
        $this->addSql('ALTER TABLE scolarite ADD CONSTRAINT FK_276250AB7A45358C FOREIGN KEY (groupe_id) REFERENCES groupe (id)');
        $this->addSql('ALTER TABLE scolarite ADD CONSTRAINT FK_276250AB543EC5F0 FOREIGN KEY (annee_id) REFERENCES annee_scolaire_courante (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE scolarite DROP FOREIGN KEY FK_276250ABA6CC7B2');
        $this->addSql('ALTER TABLE scolarite DROP FOREIGN KEY FK_276250AB7A45358C');
        $this->addSql('ALTER TABLE scolarite DROP FOREIGN KEY FK_276250AB543EC5F0');
        $this->addSql('DROP TABLE scolarite');
    }
}
