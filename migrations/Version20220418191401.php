<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220418191401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs


        $this->addSql('ALTER TABLE likee ADD hotel_id INT DEFAULT NULL, CHANGE id_user id_user INT DEFAULT NULL, CHANGE id_commentaire id_commentaire INT DEFAULT NULL');
        $this->addSql('ALTER TABLE likee ADD CONSTRAINT FK_BD1EFB2C3243BB18 FOREIGN KEY (hotel_id) REFERENCES hotel (id)');
        $this->addSql('CREATE INDEX IDX_BD1EFB2C3243BB18 ON likee (hotel_id)');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
