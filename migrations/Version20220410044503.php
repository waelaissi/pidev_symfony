<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220410044503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY avis-fk');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0377F287F FOREIGN KEY (id_voiture) REFERENCES voiture (id)');
        $this->addSql('ALTER TABLE categorie CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE chambre DROP FOREIGN KEY chambre_ibfk_1');
        $this->addSql('ALTER TABLE chambre CHANGE id_hotel id_hotel INT DEFAULT NULL');
        $this->addSql('ALTER TABLE chambre ADD CONSTRAINT FK_C509E4FFEDD61FE9 FOREIGN KEY (id_hotel) REFERENCES hotel (id)');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY fk_idsujet');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY fk_idusercom');
        $this->addSql('ALTER TABLE commentaire CHANGE iduser iduser INT DEFAULT NULL, CHANGE idsujet idsujet INT DEFAULT NULL, CHANGE nblike nblike INT NOT NULL, CHANGE nbdislike nbdislike INT NOT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCF8163584 FOREIGN KEY (idsujet) REFERENCES sujet (idsujet)');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BC5E5C27E9 FOREIGN KEY (iduser) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE dislikee DROP FOREIGN KEY fk_comdislike');
        $this->addSql('ALTER TABLE dislikee DROP FOREIGN KEY fk_userdislike');
        $this->addSql('ALTER TABLE dislikee CHANGE id_user id_user INT DEFAULT NULL, CHANGE id_commentaire id_commentaire INT DEFAULT NULL');
        $this->addSql('ALTER TABLE dislikee ADD CONSTRAINT FK_D9270B407FE2A54B FOREIGN KEY (id_commentaire) REFERENCES commentaire (idcom)');
        $this->addSql('ALTER TABLE dislikee ADD CONSTRAINT FK_D9270B406B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY evenement_ibfk_1');
        $this->addSql('ALTER TABLE evenement CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E6B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE hotel DROP FOREIGN KEY hotel_ibfk_1');
        $this->addSql('ALTER TABLE hotel CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hotel ADD CONSTRAINT FK_3535ED96B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY images_ibfk_1');
        $this->addSql('ALTER TABLE images CHANGE id_voiture id_voiture INT DEFAULT NULL');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A377F287F FOREIGN KEY (id_voiture) REFERENCES voiture (id)');
        $this->addSql('ALTER TABLE likee DROP FOREIGN KEY fk_com');
        $this->addSql('ALTER TABLE likee DROP FOREIGN KEY fk_user');
        $this->addSql('ALTER TABLE likee CHANGE id_user id_user INT DEFAULT NULL, CHANGE id_commentaire id_commentaire INT DEFAULT NULL');
        $this->addSql('ALTER TABLE likee ADD CONSTRAINT FK_BD1EFB2C7FE2A54B FOREIGN KEY (id_commentaire) REFERENCES commentaire (idcom)');
        $this->addSql('ALTER TABLE likee ADD CONSTRAINT FK_BD1EFB2C6B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE maison DROP FOREIGN KEY maison_ibfk_1');
        $this->addSql('ALTER TABLE maison CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE maison ADD CONSTRAINT FK_F90CB66D6B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY fkiduserrecl');
        $this->addSql('ALTER TABLE reclamation CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064046B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY fk_resvoiture');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY fkchambre');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955377F287F FOREIGN KEY (id_voiture) REFERENCES voiture (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849551B944D8F FOREIGN KEY (id_chambre) REFERENCES chambre (id)');
        $this->addSql('ALTER TABLE sujet DROP FOREIGN KEY fk_idtopic');
        $this->addSql('ALTER TABLE sujet DROP FOREIGN KEY fk_iduser');
        $this->addSql('ALTER TABLE sujet ADD imageName VARCHAR(255) DEFAULT NULL, CHANGE iduser iduser INT DEFAULT NULL, CHANGE idtopic idtopic INT DEFAULT NULL, CHANGE accepter accepter INT NOT NULL, CHANGE nbcom nbcom INT NOT NULL');
        $this->addSql('ALTER TABLE sujet ADD CONSTRAINT FK_2E13599D4B45B202 FOREIGN KEY (idtopic) REFERENCES topic (idtopic)');
        $this->addSql('ALTER TABLE sujet ADD CONSTRAINT FK_2E13599D5E5C27E9 FOREIGN KEY (iduser) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY ftevene');
        $this->addSql('ALTER TABLE ticket CHANGE id_evenement id_evenement INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA38B13D439 FOREIGN KEY (id_evenement) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE topic DROP FOREIGN KEY fk_userid');
        $this->addSql('ALTER TABLE topic CHANGE iduser iduser INT DEFAULT NULL, CHANGE accepter accepter TINYINT(1) NOT NULL, CHANGE nbsujet nbsujet INT NOT NULL, CHANGE hide hide INT NOT NULL');
        $this->addSql('ALTER TABLE topic ADD CONSTRAINT FK_9D40DE1B5E5C27E9 FOREIGN KEY (iduser) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE voiture DROP FOREIGN KEY voiture_ibfk_1');
        $this->addSql('ALTER TABLE voiture DROP FOREIGN KEY voiture_ibfk_2');
        $this->addSql('ALTER TABLE voiture CHANGE id_categorie id_categorie INT DEFAULT NULL');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT FK_E9E2810F6B3CA4B FOREIGN KEY (id_user) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT FK_E9E2810FC9486A13 FOREIGN KEY (id_categorie) REFERENCES categorie (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0377F287F');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT avis-fk FOREIGN KEY (id_voiture) REFERENCES voiture (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categorie CHANGE id id INT NOT NULL, CHANGE libelle libelle VARCHAR(30) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE description description VARCHAR(100) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE chambre DROP FOREIGN KEY FK_C509E4FFEDD61FE9');
        $this->addSql('ALTER TABLE chambre CHANGE id_hotel id_hotel INT NOT NULL, CHANGE type type VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE chambre ADD CONSTRAINT chambre_ibfk_1 FOREIGN KEY (id_hotel) REFERENCES hotel (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCF8163584');
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BC5E5C27E9');
        $this->addSql('ALTER TABLE commentaire CHANGE idsujet idsujet INT NOT NULL, CHANGE iduser iduser INT NOT NULL, CHANGE contenu contenu TEXT CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, CHANGE nblike nblike INT DEFAULT 0 NOT NULL, CHANGE nbdislike nbdislike INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT fk_idsujet FOREIGN KEY (idsujet) REFERENCES sujet (idsujet) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT fk_idusercom FOREIGN KEY (iduser) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dislikee DROP FOREIGN KEY FK_D9270B407FE2A54B');
        $this->addSql('ALTER TABLE dislikee DROP FOREIGN KEY FK_D9270B406B3CA4B');
        $this->addSql('ALTER TABLE dislikee CHANGE id_commentaire id_commentaire INT NOT NULL, CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE dislikee ADD CONSTRAINT fk_comdislike FOREIGN KEY (id_commentaire) REFERENCES commentaire (idcom) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dislikee ADD CONSTRAINT fk_userdislike FOREIGN KEY (id_user) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E6B3CA4B');
        $this->addSql('ALTER TABLE evenement CHANGE id_user id_user INT NOT NULL, CHANGE libelle libelle VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE image image VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE description description VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE emplacement emplacement VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT evenement_ibfk_1 FOREIGN KEY (id_user) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE hotel DROP FOREIGN KEY FK_3535ED96B3CA4B');
        $this->addSql('ALTER TABLE hotel CHANGE id_user id_user INT NOT NULL, CHANGE adresse adresse VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE ville ville VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE region region VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE description description TEXT NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE libelle libelle VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE image image VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE hotel ADD CONSTRAINT hotel_ibfk_1 FOREIGN KEY (id_user) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A377F287F');
        $this->addSql('ALTER TABLE images CHANGE id_voiture id_voiture INT NOT NULL, CHANGE img_blob img_blob VARCHAR(100) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT images_ibfk_1 FOREIGN KEY (id_voiture) REFERENCES voiture (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE likee DROP FOREIGN KEY FK_BD1EFB2C7FE2A54B');
        $this->addSql('ALTER TABLE likee DROP FOREIGN KEY FK_BD1EFB2C6B3CA4B');
        $this->addSql('ALTER TABLE likee CHANGE id_commentaire id_commentaire INT NOT NULL, CHANGE id_user id_user INT NOT NULL');
        $this->addSql('ALTER TABLE likee ADD CONSTRAINT fk_com FOREIGN KEY (id_commentaire) REFERENCES commentaire (idcom) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE likee ADD CONSTRAINT fk_user FOREIGN KEY (id_user) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE maison DROP FOREIGN KEY FK_F90CB66D6B3CA4B');
        $this->addSql('ALTER TABLE maison CHANGE id_user id_user INT NOT NULL, CHANGE adresse adresse VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE region region VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE description description TEXT NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE image image VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE maison ADD CONSTRAINT maison_ibfk_1 FOREIGN KEY (id_user) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064046B3CA4B');
        $this->addSql('ALTER TABLE reclamation CHANGE id_user id_user INT DEFAULT 1 NOT NULL, CHANGE description description VARCHAR(300) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE type type VARCHAR(30) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE date date VARCHAR(30) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT fkiduserrecl FOREIGN KEY (id_user) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955377F287F');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849551B944D8F');
        $this->addSql('ALTER TABLE reservation CHANGE etat etat VARCHAR(255) DEFAULT \'confirmé\' NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE type type VARCHAR(20) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT fk_resvoiture FOREIGN KEY (id_voiture) REFERENCES voiture (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT fkchambre FOREIGN KEY (id_chambre) REFERENCES chambre (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sujet DROP FOREIGN KEY FK_2E13599D4B45B202');
        $this->addSql('ALTER TABLE sujet DROP FOREIGN KEY FK_2E13599D5E5C27E9');
        $this->addSql('ALTER TABLE sujet DROP imageName, CHANGE idtopic idtopic INT NOT NULL, CHANGE iduser iduser INT NOT NULL, CHANGE titresujet titresujet VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, CHANGE contenu contenu TEXT CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, CHANGE accepter accepter INT DEFAULT 0 NOT NULL, CHANGE nbcom nbcom INT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE sujet ADD CONSTRAINT fk_idtopic FOREIGN KEY (idtopic) REFERENCES topic (idtopic) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sujet ADD CONSTRAINT fk_iduser FOREIGN KEY (iduser) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA38B13D439');
        $this->addSql('ALTER TABLE ticket CHANGE id_evenement id_evenement INT NOT NULL, CHANGE type type VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT ftevene FOREIGN KEY (id_evenement) REFERENCES evenement (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE topic DROP FOREIGN KEY FK_9D40DE1B5E5C27E9');
        $this->addSql('ALTER TABLE topic CHANGE iduser iduser INT NOT NULL, CHANGE titretopic titretopic VARCHAR(255) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, CHANGE description description VARCHAR(255) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`, CHANGE accepter accepter TINYINT(1) DEFAULT 0 NOT NULL, CHANGE nbsujet nbsujet INT DEFAULT 0 NOT NULL, CHANGE hide hide INT DEFAULT 0 NOT NULL, CHANGE imageName imageName VARCHAR(255) CHARACTER SET latin1 DEFAULT NULL COLLATE `latin1_swedish_ci`');
        $this->addSql('ALTER TABLE topic ADD CONSTRAINT fk_userid FOREIGN KEY (iduser) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE transaction CHANGE paymentIntent_id paymentIntent_id VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE utilisateur CHANGE login login VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE nom nom VARCHAR(30) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE prenom prenom VARCHAR(30) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE adresse adresse VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE role role VARCHAR(255) NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE image image VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE description description TEXT DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE etat etat VARCHAR(255) DEFAULT \'1\' NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE account_date account_date VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE voiture DROP FOREIGN KEY FK_E9E2810F6B3CA4B');
        $this->addSql('ALTER TABLE voiture DROP FOREIGN KEY FK_E9E2810FC9486A13');
        $this->addSql('ALTER TABLE voiture CHANGE id_categorie id_categorie INT NOT NULL, CHANGE model model VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE marque marque VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE couleur couleur VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE description description VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_general_ci`, CHANGE immat immat VARCHAR(30) DEFAULT NULL COLLATE `utf8mb4_general_ci`');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT voiture_ibfk_1 FOREIGN KEY (id_user) REFERENCES utilisateur (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE voiture ADD CONSTRAINT voiture_ibfk_2 FOREIGN KEY (id_categorie) REFERENCES categorie (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
