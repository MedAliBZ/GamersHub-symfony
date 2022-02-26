<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220226075047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE mission (id INT AUTO_INCREMENT NOT NULL, description LONGTEXT NOT NULL, prize INT NOT NULL, badge VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE missions_done (id INT AUTO_INCREMENT NOT NULL, mission_id INT NOT NULL, user_id INT DEFAULT NULL, is_claimed TINYINT(1) NOT NULL, INDEX IDX_ADF00AC0BE6CAE90 (mission_id), INDEX IDX_ADF00AC0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE missions_done ADD CONSTRAINT FK_ADF00AC0BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id)');
        $this->addSql('ALTER TABLE missions_done ADD CONSTRAINT FK_ADF00AC0A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE missions_done DROP FOREIGN KEY FK_ADF00AC0BE6CAE90');
        $this->addSql('DROP TABLE mission');
        $this->addSql('DROP TABLE missions_done');
        $this->addSql('ALTER TABLE game CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image image VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description TEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE reset_password_request CHANGE selector selector VARCHAR(20) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE hashed_token hashed_token VARCHAR(100) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name name VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE second_name second_name VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
