<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220129112137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE level_study (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE productor ADD level_study_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE productor ADD CONSTRAINT FK_173A0706A19A639 FOREIGN KEY (level_study_id) REFERENCES level_study (id)');
        $this->addSql('CREATE INDEX IDX_173A0706A19A639 ON productor (level_study_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE productor DROP FOREIGN KEY FK_173A0706A19A639');
        $this->addSql('DROP TABLE level_study');
        $this->addSql('DROP INDEX IDX_173A0706A19A639 ON productor');
        $this->addSql('ALTER TABLE productor DROP level_study_id');
    }
}
