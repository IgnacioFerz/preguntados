<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240420164946 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partida ADD jugador1_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE partida ADD jugador2_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE partida DROP jugador1');
        $this->addSql('ALTER TABLE partida DROP jugador2');
        $this->addSql('ALTER TABLE partida ADD CONSTRAINT FK_A9C1580C390198F4 FOREIGN KEY (jugador1_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE partida ADD CONSTRAINT FK_A9C1580C2BB4371A FOREIGN KEY (jugador2_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_A9C1580C390198F4 ON partida (jugador1_id)');
        $this->addSql('CREATE INDEX IDX_A9C1580C2BB4371A ON partida (jugador2_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE partida DROP CONSTRAINT FK_A9C1580C390198F4');
        $this->addSql('ALTER TABLE partida DROP CONSTRAINT FK_A9C1580C2BB4371A');
        $this->addSql('DROP INDEX IDX_A9C1580C390198F4');
        $this->addSql('DROP INDEX IDX_A9C1580C2BB4371A');
        $this->addSql('ALTER TABLE partida ADD jugador1 INT NOT NULL');
        $this->addSql('ALTER TABLE partida ADD jugador2 INT NOT NULL');
        $this->addSql('ALTER TABLE partida DROP jugador1_id');
        $this->addSql('ALTER TABLE partida DROP jugador2_id');
    }
}
