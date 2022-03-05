<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220304151256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscribe ADD tournament_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE subscribe ADD CONSTRAINT FK_68B95F3E33D1A3E7 FOREIGN KEY (tournament_id) REFERENCES tournaments (id)');
        $this->addSql('CREATE INDEX IDX_68B95F3E33D1A3E7 ON subscribe (tournament_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscribe DROP FOREIGN KEY FK_68B95F3E33D1A3E7');
        $this->addSql('DROP INDEX IDX_68B95F3E33D1A3E7 ON subscribe');
        $this->addSql('ALTER TABLE subscribe DROP tournament_id');
    }
}
