<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220305110629 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipe (id INT AUTO_INCREMENT NOT NULL, equipe_id INT NOT NULL, equipe_name VARCHAR(255) NOT NULL, creater VARCHAR(255) NOT NULL, verified VARCHAR(255) NOT NULL, nb_joueurs INT NOT NULL, rank VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE historique (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(255) NOT NULL, model VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rewards (id INT AUTO_INCREMENT NOT NULL, tournament_id_id INT NOT NULL, type VARCHAR(255) NOT NULL, quantity INT NOT NULL, INDEX IDX_E9221E37BE120E4E (tournament_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscribe (id INT AUTO_INCREMENT NOT NULL, tournament_id INT DEFAULT NULL, team_name VARCHAR(255) NOT NULL, size INT NOT NULL, INDEX IDX_68B95F3E33D1A3E7 (tournament_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournaments (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, decription VARCHAR(255) NOT NULL, team_size INT NOT NULL, start_date DATETIME NOT NULL, finish_date DATETIME NOT NULL, max_t INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rewards ADD CONSTRAINT FK_E9221E37BE120E4E FOREIGN KEY (tournament_id_id) REFERENCES tournaments (id)');
        $this->addSql('ALTER TABLE subscribe ADD CONSTRAINT FK_68B95F3E33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournaments (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rewards DROP FOREIGN KEY FK_E9221E37BE120E4E');
        $this->addSql('ALTER TABLE subscribe DROP FOREIGN KEY FK_68B95F3E33D1A3E7');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP TABLE historique');
        $this->addSql('DROP TABLE rewards');
        $this->addSql('DROP TABLE subscribe');
        $this->addSql('DROP TABLE tournaments');
        $this->addSql('ALTER TABLE coach CHANGE description description VARCHAR(1000) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE game CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image image VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description TEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE mission CHANGE description description LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE badge badge VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE reset_password_request CHANGE selector selector VARCHAR(20) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE hashed_token hashed_token VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE sessioncoaching CHANGE background_color background_color VARCHAR(7) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE border_color border_color VARCHAR(7) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description VARCHAR(1000) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE text_color text_color VARCHAR(7) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name name VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE second_name second_name VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
