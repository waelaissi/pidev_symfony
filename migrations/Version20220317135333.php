<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220317135333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE hotel ADD lat DOUBLE PRECISION DEFAULT NULL, ADD lon DOUBLE PRECISION DEFAULT NULL');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE hotel DROP lat, DROP lon, CHANGE adresse adresse VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE ville ville VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE region region VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE description description TEXT NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE libelle libelle VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE image image VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`');

    }
}
