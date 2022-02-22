<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220221233834 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE matchs_teams');
        $this->addSql('ALTER TABLE matchs DROP FOREIGN KEY matchs_ibfk_1');
        $this->addSql('DROP INDEX teams ON matchs');
        $this->addSql('ALTER TABLE matchs DROP teams');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE matchs_teams (matchs_id INT NOT NULL, teams_id INT NOT NULL, INDEX IDX_B588642D88EB7468 (matchs_id), INDEX IDX_B588642DD6365F12 (teams_id), PRIMARY KEY(matchs_id, teams_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE matchs_teams ADD CONSTRAINT FK_B588642DD6365F12 FOREIGN KEY (teams_id) REFERENCES teams (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE matchs_teams ADD CONSTRAINT FK_B588642D88EB7468 FOREIGN KEY (matchs_id) REFERENCES matchs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE game CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image image VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE matchs ADD teams INT DEFAULT NULL, CHANGE result result VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE matchs ADD CONSTRAINT matchs_ibfk_1 FOREIGN KEY (teams) REFERENCES teams (id)');
        $this->addSql('CREATE INDEX teams ON matchs (teams)');
        $this->addSql('ALTER TABLE teams CHANGE team_name team_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE second_name second_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
