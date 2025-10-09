<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251009153621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rh_conge CHANGE statut statut VARCHAR(20) DEFAULT \'EN_ATTENTE\' NOT NULL');
        $this->addSql('ALTER TABLE rh_employe ADD nombre_enfants INT DEFAULT NULL, CHANGE conge_disponible conge_disponible DOUBLE PRECISION DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE rh_jour_ferie CHANGE paye paye TINYINT(1) DEFAULT 1 NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX unique_date ON rh_jour_ferie (date)');
        $this->addSql('CREATE UNIQUE INDEX unique_code ON rh_status_famille (code)');
        $this->addSql('CREATE UNIQUE INDEX unique_libelle_fr ON rh_type_conge (libelle_fr)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rh_conge CHANGE statut statut VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE rh_employe DROP nombre_enfants, CHANGE conge_disponible conge_disponible DOUBLE PRECISION NOT NULL');
        $this->addSql('DROP INDEX unique_date ON rh_jour_ferie');
        $this->addSql('ALTER TABLE rh_jour_ferie CHANGE paye paye TINYINT(1) NOT NULL');
        $this->addSql('DROP INDEX unique_code ON rh_status_famille');
        $this->addSql('DROP INDEX unique_libelle_fr ON rh_type_conge');
    }
}
