<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220129131927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE monitor (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE productor ADD monitor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE productor ADD CONSTRAINT FK_173A0704CE1C902 FOREIGN KEY (monitor_id) REFERENCES monitor (id)');
        $this->addSql('CREATE INDEX IDX_173A0704CE1C902 ON productor (monitor_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE productor DROP FOREIGN KEY FK_173A0704CE1C902');
        $this->addSql('DROP TABLE monitor');
        $this->addSql('DROP INDEX IDX_173A0704CE1C902 ON productor');
        $this->addSql('ALTER TABLE productor DROP monitor_id');
    }
}
