<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220212165554 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coach DROP FOREIGN KEY FK_3F596DCC9D86650F');
        $this->addSql('ALTER TABLE coach DROP FOREIGN KEY FK_3F596DCC4D77E7D8');
        $this->addSql('DROP INDEX UNIQ_3F596DCC9D86650F ON coach');
        $this->addSql('DROP INDEX IDX_3F596DCC4D77E7D8 ON coach');
        $this->addSql('ALTER TABLE coach ADD user_id INT NOT NULL, ADD game_id INT NOT NULL, DROP user_id_id, DROP game_id_id');
        $this->addSql('ALTER TABLE coach ADD CONSTRAINT FK_3F596DCCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE coach ADD CONSTRAINT FK_3F596DCCE48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3F596DCCA76ED395 ON coach (user_id)');
        $this->addSql('CREATE INDEX IDX_3F596DCCE48FD905 ON coach (game_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE coach DROP FOREIGN KEY FK_3F596DCCA76ED395');
        $this->addSql('ALTER TABLE coach DROP FOREIGN KEY FK_3F596DCCE48FD905');
        $this->addSql('DROP INDEX UNIQ_3F596DCCA76ED395 ON coach');
        $this->addSql('DROP INDEX IDX_3F596DCCE48FD905 ON coach');
        $this->addSql('ALTER TABLE coach ADD user_id_id INT NOT NULL, ADD game_id_id INT NOT NULL, DROP user_id, DROP game_id, CHANGE description description VARCHAR(1000) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE coach ADD CONSTRAINT FK_3F596DCC9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE coach ADD CONSTRAINT FK_3F596DCC4D77E7D8 FOREIGN KEY (game_id_id) REFERENCES game (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_3F596DCC9D86650F ON coach (user_id_id)');
        $this->addSql('CREATE INDEX IDX_3F596DCC4D77E7D8 ON coach (game_id_id)');
        $this->addSql('ALTER TABLE game CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image image VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE second_name second_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
