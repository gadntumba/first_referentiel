<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220129165755 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, line VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, altitude DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE source_of_supply_activity (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE agricultural_activity ADD exploited_area_id INT DEFAULT NULL, ADD source_of_supply_activity_id INT DEFAULT NULL, ADD adress_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agricultural_activity ADD CONSTRAINT FK_40DB987AA02BB56 FOREIGN KEY (exploited_area_id) REFERENCES exploited_area (id)');
        $this->addSql('ALTER TABLE agricultural_activity ADD CONSTRAINT FK_40DB987A4C5C771A FOREIGN KEY (source_of_supply_activity_id) REFERENCES source_of_supply_activity (id)');
        $this->addSql('ALTER TABLE agricultural_activity ADD CONSTRAINT FK_40DB987A8486F9AC FOREIGN KEY (adress_id) REFERENCES address (id)');
        $this->addSql('CREATE INDEX IDX_40DB987AA02BB56 ON agricultural_activity (exploited_area_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_40DB987A4C5C771A ON agricultural_activity (source_of_supply_activity_id)');
        $this->addSql('CREATE INDEX IDX_40DB987A8486F9AC ON agricultural_activity (adress_id)');
        $this->addSql('ALTER TABLE fiching_activity ADD source_of_supply_activity_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fiching_activity ADD CONSTRAINT FK_F6B4F2414C5C771A FOREIGN KEY (source_of_supply_activity_id) REFERENCES source_of_supply_activity (id)');
        $this->addSql('CREATE INDEX IDX_F6B4F2414C5C771A ON fiching_activity (source_of_supply_activity_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agricultural_activity DROP FOREIGN KEY FK_40DB987A8486F9AC');
        $this->addSql('ALTER TABLE agricultural_activity DROP FOREIGN KEY FK_40DB987A4C5C771A');
        $this->addSql('ALTER TABLE fiching_activity DROP FOREIGN KEY FK_F6B4F2414C5C771A');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE source_of_supply_activity');
        $this->addSql('ALTER TABLE agricultural_activity DROP FOREIGN KEY FK_40DB987AA02BB56');
        $this->addSql('DROP INDEX IDX_40DB987AA02BB56 ON agricultural_activity');
        $this->addSql('DROP INDEX UNIQ_40DB987A4C5C771A ON agricultural_activity');
        $this->addSql('DROP INDEX IDX_40DB987A8486F9AC ON agricultural_activity');
        $this->addSql('ALTER TABLE agricultural_activity DROP exploited_area_id, DROP source_of_supply_activity_id, DROP adress_id');
        $this->addSql('DROP INDEX IDX_F6B4F2414C5C771A ON fiching_activity');
        $this->addSql('ALTER TABLE fiching_activity DROP source_of_supply_activity_id');
    }
}
