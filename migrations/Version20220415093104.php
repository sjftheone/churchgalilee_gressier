<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220415093104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Comment (id INT AUTO_INCREMENT NOT NULL, post_id INT NOT NULL, author_id INT NOT NULL, content LONGTEXT NOT NULL, published_at DATETIME NOT NULL, INDEX IDX_5BC96BF04B89032C (post_id), INDEX IDX_5BC96BF0F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Integration (id INT AUTO_INCREMENT NOT NULL, personne_id INT DEFAULT NULL, date_in DATE NOT NULL, infos_in VARCHAR(255) NOT NULL, date_out DATE DEFAULT NULL, infos_out VARCHAR(255) DEFAULT NULL, INDEX IDX_7B75E24CA21BD112 (personne_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Personne (id INT AUTO_INCREMENT NOT NULL, author_id INT DEFAULT NULL, image_name VARCHAR(255) DEFAULT NULL, sexe VARCHAR(255) NOT NULL, civilite VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATETIME DEFAULT NULL, lieu_naissance VARCHAR(255) DEFAULT NULL, nationalite VARCHAR(255) DEFAULT NULL, profession VARCHAR(255) DEFAULT NULL, statut_mat VARCHAR(255) DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, code_postal VARCHAR(255) DEFAULT NULL, ville VARCHAR(255) DEFAULT NULL, phone_home VARCHAR(255) DEFAULT NULL, phone_personnel VARCHAR(255) DEFAULT NULL, phone_travail VARCHAR(255) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, infos_add VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, published_at DATETIME DEFAULT NULL, INDEX IDX_F6B8ABB9F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Post (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, image_name VARCHAR(255) DEFAULT NULL, title VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, summary VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, published_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_FAB8C3B3F675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE User (id INT AUTO_INCREMENT NOT NULL, full_name VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, enable TINYINT(1) NOT NULL, token VARCHAR(255) DEFAULT NULL, create_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_2DA17977F85E0677 (username), UNIQUE INDEX UNIQ_2DA17977E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bapteme (id INT AUTO_INCREMENT NOT NULL, personne_id INT DEFAULT NULL, date_bapteme DATE NOT NULL, lieu VARCHAR(255) NOT NULL, baptiser_par VARCHAR(255) NOT NULL, INDEX IDX_FCF500BFA21BD112 (personne_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, personnes_id INT DEFAULT NULL, event_date DATE NOT NULL, event_type VARCHAR(255) DEFAULT NULL, event_lieu VARCHAR(255) DEFAULT NULL, event_infos VARCHAR(255) DEFAULT NULL, INDEX IDX_B26681E146AD7BC (personnes_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Comment ADD CONSTRAINT FK_5BC96BF04B89032C FOREIGN KEY (post_id) REFERENCES Post (id)');
        $this->addSql('ALTER TABLE Comment ADD CONSTRAINT FK_5BC96BF0F675F31B FOREIGN KEY (author_id) REFERENCES User (id)');
        $this->addSql('ALTER TABLE Integration ADD CONSTRAINT FK_7B75E24CA21BD112 FOREIGN KEY (personne_id) REFERENCES Personne (id)');
        $this->addSql('ALTER TABLE Personne ADD CONSTRAINT FK_F6B8ABB9F675F31B FOREIGN KEY (author_id) REFERENCES User (id)');
        $this->addSql('ALTER TABLE Post ADD CONSTRAINT FK_FAB8C3B3F675F31B FOREIGN KEY (author_id) REFERENCES User (id)');
        $this->addSql('ALTER TABLE bapteme ADD CONSTRAINT FK_FCF500BFA21BD112 FOREIGN KEY (personne_id) REFERENCES Personne (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681E146AD7BC FOREIGN KEY (personnes_id) REFERENCES Personne (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Integration DROP FOREIGN KEY FK_7B75E24CA21BD112');
        $this->addSql('ALTER TABLE bapteme DROP FOREIGN KEY FK_FCF500BFA21BD112');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681E146AD7BC');
        $this->addSql('ALTER TABLE Comment DROP FOREIGN KEY FK_5BC96BF04B89032C');
        $this->addSql('ALTER TABLE Comment DROP FOREIGN KEY FK_5BC96BF0F675F31B');
        $this->addSql('ALTER TABLE Personne DROP FOREIGN KEY FK_F6B8ABB9F675F31B');
        $this->addSql('ALTER TABLE Post DROP FOREIGN KEY FK_FAB8C3B3F675F31B');
        $this->addSql('DROP TABLE Comment');
        $this->addSql('DROP TABLE Integration');
        $this->addSql('DROP TABLE Personne');
        $this->addSql('DROP TABLE Post');
        $this->addSql('DROP TABLE User');
        $this->addSql('DROP TABLE bapteme');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
