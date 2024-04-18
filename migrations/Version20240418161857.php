<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240418161857 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partida DROP CONSTRAINT fk_a9c1580cdb38439e');
        $this->addSql('DROP INDEX idx_a9c1580cdb38439e');
        $this->addSql('ALTER TABLE partida DROP usuario_id');
        $this->addSql('ALTER TABLE "user" ADD queue VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP queue');
        $this->addSql('ALTER TABLE partida ADD usuario_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE partida ADD CONSTRAINT fk_a9c1580cdb38439e FOREIGN KEY (usuario_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_a9c1580cdb38439e ON partida (usuario_id)');
    }
}
