<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230508204218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE caracteristic ADD strength_modifier_id INT DEFAULT NULL, ADD strength INT NOT NULL');
        $this->addSql('ALTER TABLE caracteristic ADD CONSTRAINT FK_9B9583442731E098 FOREIGN KEY (strength_modifier_id) REFERENCES caracteristic_modifier (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9B9583442731E098 ON caracteristic (strength_modifier_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE caracteristic DROP FOREIGN KEY FK_9B9583442731E098');
        $this->addSql('DROP INDEX UNIQ_9B9583442731E098 ON caracteristic');
        $this->addSql('ALTER TABLE caracteristic DROP strength_modifier_id, DROP strength');
    }
}
