<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220129125627 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE stock_raising_activity (id INT AUTO_INCREMENT NOT NULL, productor_id INT DEFAULT NULL, create_date DATE NOT NULL, goal LONGTEXT NOT NULL, INDEX IDX_2BE2B53855BB310E (productor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE stock_raising_activity ADD CONSTRAINT FK_2BE2B53855BB310E FOREIGN KEY (productor_id) REFERENCES productor (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE stock_raising_activity');
    }
}
