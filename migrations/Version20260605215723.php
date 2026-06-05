<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260605215723 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE candidature_user DROP FOREIGN KEY `FK_998D59AEA76ED395`');
        $this->addSql('ALTER TABLE candidature_user DROP FOREIGN KEY `FK_998D59AEB6121583`');
        $this->addSql('DROP TABLE candidature_user');
        $this->addSql('ALTER TABLE candidature ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B8A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_E33BD3B8A76ED395 ON candidature (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE candidature_user (candidature_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_998D59AEA76ED395 (user_id), INDEX IDX_998D59AEB6121583 (candidature_id), PRIMARY KEY (candidature_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE candidature_user ADD CONSTRAINT `FK_998D59AEA76ED395` FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE candidature_user ADD CONSTRAINT `FK_998D59AEB6121583` FOREIGN KEY (candidature_id) REFERENCES candidature (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B8A76ED395');
        $this->addSql('DROP INDEX IDX_E33BD3B8A76ED395 ON candidature');
        $this->addSql('ALTER TABLE candidature DROP user_id');
    }
}
