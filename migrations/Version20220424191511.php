<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220424191511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category ADD photo_url VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE food ADD photo_url VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD photo_url VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP photo_url');
        $this->addSql('ALTER TABLE food DROP photo_url');
        $this->addSql('ALTER TABLE user DROP photo_url');
    }
}
