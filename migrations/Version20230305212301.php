<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230305212301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE arena (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE arena_player (arena_id INT NOT NULL, player_id INT NOT NULL, INDEX IDX_2D047A85663565CF (arena_id), INDEX IDX_2D047A8599E6F5DF (player_id), PRIMARY KEY(arena_id, player_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE card (id INT AUTO_INCREMENT NOT NULL, player_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, life INT NOT NULL, magic INT NOT NULL, max_life INT NOT NULL, INDEX IDX_161498D399E6F5DF (player_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE arena_player ADD CONSTRAINT FK_2D047A85663565CF FOREIGN KEY (arena_id) REFERENCES arena (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE arena_player ADD CONSTRAINT FK_2D047A8599E6F5DF FOREIGN KEY (player_id) REFERENCES player (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D399E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE arena_player DROP FOREIGN KEY FK_2D047A85663565CF');
        $this->addSql('ALTER TABLE arena_player DROP FOREIGN KEY FK_2D047A8599E6F5DF');
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D399E6F5DF');
        $this->addSql('DROP TABLE arena');
        $this->addSql('DROP TABLE arena_player');
        $this->addSql('DROP TABLE card');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
