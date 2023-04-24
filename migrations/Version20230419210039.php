<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230419210039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE caracteristic (id INT AUTO_INCREMENT NOT NULL, intelligence INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE caracteristic_modifier (id INT AUTO_INCREMENT NOT NULL, caracteristic_id INT DEFAULT NULL, quantity INT NOT NULL, duration INT DEFAULT NULL, UNIQUE INDEX UNIQ_11E4F3B481194CF4 (caracteristic_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE caracteristic_modifier ADD CONSTRAINT FK_11E4F3B481194CF4 FOREIGN KEY (caracteristic_id) REFERENCES caracteristic (id)');
        $this->addSql('ALTER TABLE card ADD caracteristic_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE card ADD CONSTRAINT FK_161498D381194CF4 FOREIGN KEY (caracteristic_id) REFERENCES caracteristic (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_161498D381194CF4 ON card (caracteristic_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE card DROP FOREIGN KEY FK_161498D381194CF4');
        $this->addSql('ALTER TABLE caracteristic_modifier DROP FOREIGN KEY FK_11E4F3B481194CF4');
        $this->addSql('DROP TABLE caracteristic');
        $this->addSql('DROP TABLE caracteristic_modifier');
        $this->addSql('DROP INDEX UNIQ_161498D381194CF4 ON card');
        $this->addSql('ALTER TABLE card DROP caracteristic_id');
    }
}
