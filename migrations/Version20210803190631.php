<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210803190631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE video_category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__videos AS SELECT id, title, description, url FROM videos');
        $this->addSql('DROP TABLE videos');
        $this->addSql('CREATE TABLE videos (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, categoria_id_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, description VARCHAR(255) NOT NULL COLLATE BINARY, url VARCHAR(255) NOT NULL COLLATE BINARY, CONSTRAINT FK_29AA64327E735794 FOREIGN KEY (categoria_id_id) REFERENCES video_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO videos (id, title, description, url) SELECT id, title, description, url FROM __temp__videos');
        $this->addSql('DROP TABLE __temp__videos');
        $this->addSql('CREATE INDEX IDX_29AA64327E735794 ON videos (categoria_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE video_category');
        $this->addSql('DROP INDEX IDX_29AA64327E735794');
        $this->addSql('CREATE TEMPORARY TABLE __temp__videos AS SELECT id, title, description, url FROM videos');
        $this->addSql('DROP TABLE videos');
        $this->addSql('CREATE TABLE videos (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO videos (id, title, description, url) SELECT id, title, description, url FROM __temp__videos');
        $this->addSql('DROP TABLE __temp__videos');
    }
}
