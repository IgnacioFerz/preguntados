<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240420165704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pregunta ADD partida_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pregunta ADD CONSTRAINT FK_AEE0E1F7F15A1987 FOREIGN KEY (partida_id) REFERENCES partida (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_AEE0E1F7F15A1987 ON pregunta (partida_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE pregunta DROP CONSTRAINT FK_AEE0E1F7F15A1987');
        $this->addSql('DROP INDEX IDX_AEE0E1F7F15A1987');
        $this->addSql('ALTER TABLE pregunta DROP partida_id');
    }
}
