<?php

declare(strict_types=1);

namespace App\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240818122801 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE flower (flower_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid_binary)\', user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid_binary)\', name VARCHAR(100) NOT NULL, latin_name VARCHAR(125) NOT NULL, flower_description VARCHAR(255) NOT NULL, family VARCHAR(100) NOT NULL, height INT NOT NULL, flower_education VARCHAR(255) NOT NULL, gender VARCHAR(255) NOT NULL, country VARCHAR(100) NOT NULL, favourite_serial VARCHAR(255) NOT NULL, web_portfolio VARCHAR(255) NOT NULL, favourite_song VARCHAR(255) NOT NULL, dream_vacation VARCHAR(255) NOT NULL, favourite_quote VARCHAR(255) DEFAULT NULL, planting_date DATETIME NOT NULL, UNIQUE INDEX UNIQ_A7D7C1DAA76ED395 (user_id), PRIMARY KEY(flower_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE flower ADD CONSTRAINT FK_A7D7C1DAA76ED395 FOREIGN KEY (user_id) REFERENCES user (user_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user CHANGE bio bio LONGTEXT DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flower DROP FOREIGN KEY FK_A7D7C1DAA76ED395');
        $this->addSql('DROP TABLE flower');
        $this->addSql('ALTER TABLE user CHANGE bio bio LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
