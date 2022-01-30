<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220129171438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, stock_rais_in_activity_id INT DEFAULT NULL, house_keeping_id INT DEFAULT NULL, line VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, altitude DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_D4E6F81978A7D91 (stock_rais_in_activity_id), UNIQUE INDEX UNIQ_D4E6F81FF8221C5 (house_keeping_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fiching_activity_type (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE source_of_supply_activity (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81978A7D91 FOREIGN KEY (stock_rais_in_activity_id) REFERENCES stock_raising_activity (id)');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81FF8221C5 FOREIGN KEY (house_keeping_id) REFERENCES house_keeping (id)');
        $this->addSql('ALTER TABLE agricultural_activity ADD exploited_area_id INT DEFAULT NULL, ADD source_of_supply_activity_id INT DEFAULT NULL, ADD adress_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agricultural_activity ADD CONSTRAINT FK_40DB987AA02BB56 FOREIGN KEY (exploited_area_id) REFERENCES exploited_area (id)');
        $this->addSql('ALTER TABLE agricultural_activity ADD CONSTRAINT FK_40DB987A4C5C771A FOREIGN KEY (source_of_supply_activity_id) REFERENCES source_of_supply_activity (id)');
        $this->addSql('ALTER TABLE agricultural_activity ADD CONSTRAINT FK_40DB987A8486F9AC FOREIGN KEY (adress_id) REFERENCES address (id)');
        $this->addSql('CREATE INDEX IDX_40DB987AA02BB56 ON agricultural_activity (exploited_area_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_40DB987A4C5C771A ON agricultural_activity (source_of_supply_activity_id)');
        $this->addSql('CREATE INDEX IDX_40DB987A8486F9AC ON agricultural_activity (adress_id)');
        $this->addSql('ALTER TABLE fiching_activity ADD source_of_supply_activity_id INT DEFAULT NULL, ADD address_id INT DEFAULT NULL, ADD fiching_activity_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fiching_activity ADD CONSTRAINT FK_F6B4F2414C5C771A FOREIGN KEY (source_of_supply_activity_id) REFERENCES source_of_supply_activity (id)');
        $this->addSql('ALTER TABLE fiching_activity ADD CONSTRAINT FK_F6B4F241F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE fiching_activity ADD CONSTRAINT FK_F6B4F241E1FD70F3 FOREIGN KEY (fiching_activity_type_id) REFERENCES fiching_activity_type (id)');
        $this->addSql('CREATE INDEX IDX_F6B4F2414C5C771A ON fiching_activity (source_of_supply_activity_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F6B4F241F5B7AF75 ON fiching_activity (address_id)');
        $this->addSql('CREATE INDEX IDX_F6B4F241E1FD70F3 ON fiching_activity (fiching_activity_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agricultural_activity DROP FOREIGN KEY FK_40DB987A8486F9AC');
        $this->addSql('ALTER TABLE fiching_activity DROP FOREIGN KEY FK_F6B4F241F5B7AF75');
        $this->addSql('ALTER TABLE fiching_activity DROP FOREIGN KEY FK_F6B4F241E1FD70F3');
        $this->addSql('ALTER TABLE agricultural_activity DROP FOREIGN KEY FK_40DB987A4C5C771A');
        $this->addSql('ALTER TABLE fiching_activity DROP FOREIGN KEY FK_F6B4F2414C5C771A');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE fiching_activity_type');
        $this->addSql('DROP TABLE source_of_supply_activity');
        $this->addSql('ALTER TABLE agricultural_activity DROP FOREIGN KEY FK_40DB987AA02BB56');
        $this->addSql('DROP INDEX IDX_40DB987AA02BB56 ON agricultural_activity');
        $this->addSql('DROP INDEX UNIQ_40DB987A4C5C771A ON agricultural_activity');
        $this->addSql('DROP INDEX IDX_40DB987A8486F9AC ON agricultural_activity');
        $this->addSql('ALTER TABLE agricultural_activity DROP exploited_area_id, DROP source_of_supply_activity_id, DROP adress_id');
        $this->addSql('DROP INDEX IDX_F6B4F2414C5C771A ON fiching_activity');
        $this->addSql('DROP INDEX UNIQ_F6B4F241F5B7AF75 ON fiching_activity');
        $this->addSql('DROP INDEX IDX_F6B4F241E1FD70F3 ON fiching_activity');
        $this->addSql('ALTER TABLE fiching_activity DROP source_of_supply_activity_id, DROP address_id, DROP fiching_activity_type_id');
    }
}
