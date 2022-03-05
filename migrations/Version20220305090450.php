<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220305090450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5AD69F3311');
        $this->addSql('DROP INDEX IDX_B3BA5A5AD69F3311 ON products');
        $this->addSql('ALTER TABLE products DROP wish_list_id');
        $this->addSql('ALTER TABLE wish_list ADD product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE wish_list ADD CONSTRAINT FK_5B8739BD4584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('CREATE INDEX IDX_5B8739BD4584665A ON wish_list (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category CHANGE name_category name_category VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image image VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE game CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image image VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE products ADD wish_list_id INT DEFAULT NULL, CHANGE name_product name_product VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image image LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5AD69F3311 FOREIGN KEY (wish_list_id) REFERENCES wish_list (id)');
        $this->addSql('CREATE INDEX IDX_B3BA5A5AD69F3311 ON products (wish_list_id)');
        $this->addSql('ALTER TABLE user CHANGE username username VARCHAR(180) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE second_name second_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE wish_list DROP FOREIGN KEY FK_5B8739BD4584665A');
        $this->addSql('DROP INDEX IDX_5B8739BD4584665A ON wish_list');
        $this->addSql('ALTER TABLE wish_list DROP product_id');
    }
}
