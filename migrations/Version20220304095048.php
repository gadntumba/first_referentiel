<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220304095048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE supervisor (id INT AUTO_INCREMENT NOT NULL, city_id INT DEFAULT NULL, territory_id INT DEFAULT NULL, created_at DATE NOT NULL, goal_recordings INT NOT NULL, INDEX IDX_4D9192F88BAC62AF (city_id), INDEX IDX_4D9192F873F74AD4 (territory_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE supervisor_ot (supervisor_id INT NOT NULL, ot_id INT NOT NULL, INDEX IDX_52041BBD19E9AC5F (supervisor_id), INDEX IDX_52041BBDA01D3C68 (ot_id), PRIMARY KEY(supervisor_id, ot_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE supervisor ADD CONSTRAINT FK_4D9192F88BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE supervisor ADD CONSTRAINT FK_4D9192F873F74AD4 FOREIGN KEY (territory_id) REFERENCES territorry (id)');
        $this->addSql('ALTER TABLE supervisor_ot ADD CONSTRAINT FK_52041BBD19E9AC5F FOREIGN KEY (supervisor_id) REFERENCES supervisor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE supervisor_ot ADD CONSTRAINT FK_52041BBDA01D3C68 FOREIGN KEY (ot_id) REFERENCES ot (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE supervisor_ot DROP FOREIGN KEY FK_52041BBD19E9AC5F');
        $this->addSql('DROP TABLE supervisor');
        $this->addSql('DROP TABLE supervisor_ot');
    }
}
