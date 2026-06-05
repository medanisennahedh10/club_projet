<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260605225110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE candidature (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(255) NOT NULL, cv_filename VARCHAR(255) NOT NULL, message VARCHAR(255) NOT NULL, submitted_at DATETIME NOT NULL, user_id INT NOT NULL, recrutement_id_id INT NOT NULL, INDEX IDX_E33BD3B8A76ED395 (user_id), INDEX IDX_E33BD3B8683C367E (recrutement_id_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE club (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, proposed_by_id_id INT DEFAULT NULL, INDEX IDX_B8EE387288F73161 (proposed_by_id_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE club_member (id INT AUTO_INCREMENT NOT NULL, role VARCHAR(255) NOT NULL, joined_at DATETIME NOT NULL, user_id_id INT NOT NULL, club_id_id INT NOT NULL, INDEX IDX_552B46F29D86650F (user_id_id), INDEX IDX_552B46F2DF2AB4E5 (club_id_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, nom_evenement VARCHAR(255) NOT NULL, lieu VARCHAR(255) NOT NULL, date_debut VARCHAR(255) NOT NULL, date_fin DATETIME NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, club_id_id INT NOT NULL, INDEX IDX_B26681EDF2AB4E5 (club_id_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE feedback (id INT AUTO_INCREMENT NOT NULL, content LONGTEXT NOT NULL, rating INT NOT NULL, created_at DATETIME NOT NULL, user_id_id INT NOT NULL, event_id_id INT NOT NULL, INDEX IDX_D22944589D86650F (user_id_id), INDEX IDX_D22944583E5F2F7B (event_id_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE participation (id INT AUTO_INCREMENT NOT NULL, registered_at DATETIME NOT NULL, user_id_id INT NOT NULL, evenement_id_id INT NOT NULL, INDEX IDX_AB55E24F9D86650F (user_id_id), INDEX IDX_AB55E24FECEE32AF (evenement_id_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, message VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, subject VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, user_id_id INT NOT NULL, INDEX IDX_CE6064049D86650F (user_id_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE recrutement (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, requirements VARCHAR(255) NOT NULL, deadline DATETIME NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, club_id_id INT NOT NULL, INDEX IDX_25EB2319DF2AB4E5 (club_id_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT DEFAULT 0 NOT NULL, role VARCHAR(255) NOT NULL, dtype VARCHAR(255) NOT NULL, profile_picture VARCHAR(255) DEFAULT NULL, otp_code VARCHAR(255) DEFAULT NULL, otp_expires_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B8A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B8683C367E FOREIGN KEY (recrutement_id_id) REFERENCES recrutement (id)');
        $this->addSql('ALTER TABLE club ADD CONSTRAINT FK_B8EE387288F73161 FOREIGN KEY (proposed_by_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE club_member ADD CONSTRAINT FK_552B46F29D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE club_member ADD CONSTRAINT FK_552B46F2DF2AB4E5 FOREIGN KEY (club_id_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE evenement ADD CONSTRAINT FK_B26681EDF2AB4E5 FOREIGN KEY (club_id_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D22944589D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE feedback ADD CONSTRAINT FK_D22944583E5F2F7B FOREIGN KEY (event_id_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24F9D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FECEE32AF FOREIGN KEY (evenement_id_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064049D86650F FOREIGN KEY (user_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE recrutement ADD CONSTRAINT FK_25EB2319DF2AB4E5 FOREIGN KEY (club_id_id) REFERENCES club (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B8A76ED395');
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B8683C367E');
        $this->addSql('ALTER TABLE club DROP FOREIGN KEY FK_B8EE387288F73161');
        $this->addSql('ALTER TABLE club_member DROP FOREIGN KEY FK_552B46F29D86650F');
        $this->addSql('ALTER TABLE club_member DROP FOREIGN KEY FK_552B46F2DF2AB4E5');
        $this->addSql('ALTER TABLE evenement DROP FOREIGN KEY FK_B26681EDF2AB4E5');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D22944589D86650F');
        $this->addSql('ALTER TABLE feedback DROP FOREIGN KEY FK_D22944583E5F2F7B');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24F9D86650F');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FECEE32AF');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064049D86650F');
        $this->addSql('ALTER TABLE recrutement DROP FOREIGN KEY FK_25EB2319DF2AB4E5');
        $this->addSql('DROP TABLE candidature');
        $this->addSql('DROP TABLE club');
        $this->addSql('DROP TABLE club_member');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE feedback');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE recrutement');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
