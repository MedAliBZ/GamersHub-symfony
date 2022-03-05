<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220222000904 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sessioncoaching (id INT AUTO_INCREMENT NOT NULL, coach_id INT NOT NULL, user_id INT DEFAULT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, prix DOUBLE PRECISION DEFAULT NULL, background_color VARCHAR(7) NOT NULL, border_color VARCHAR(7) NOT NULL, description VARCHAR(1000) NOT NULL, INDEX IDX_AC2E6BE93C105691 (coach_id), INDEX IDX_AC2E6BE9A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sessioncoaching ADD CONSTRAINT FK_AC2E6BE93C105691 FOREIGN KEY (coach_id) REFERENCES coach (id)');
        $this->addSql('ALTER TABLE sessioncoaching ADD CONSTRAINT FK_AC2E6BE9A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE coach CHANGE rating rating INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE sessioncoaching');
        $this->addSql('ALTER TABLE coach CHANGE description description VARCHAR(1000) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE rating rating INT NOT NULL');
        $this->addSql('ALTER TABLE game CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image image VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles JSON NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE second_name second_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
