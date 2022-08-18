<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220304095009 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE supervisor (id INT AUTO_INCREMENT NOT NULL, city_id INT DEFAULT NULL, territory_id INT DEFAULT NULL, created_at DATE NOT NULL, goal_recordings INT NOT NULL, INDEX IDX_4D9192F88BAC62AF (city_id), INDEX IDX_4D9192F873F74AD4 (territory_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE supervisor ADD CONSTRAINT FK_4D9192F88BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE supervisor ADD CONSTRAINT FK_4D9192F873F74AD4 FOREIGN KEY (territory_id) REFERENCES territorry (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE supervisor');
    }
}
