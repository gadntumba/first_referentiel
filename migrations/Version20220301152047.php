<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220301152047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, stock_rais_in_activity_id INT DEFAULT NULL, house_keeping_id INT DEFAULT NULL, town_id INT DEFAULT NULL, sector_id INT DEFAULT NULL, line VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, altitude DOUBLE PRECISION DEFAULT NULL, UNIQUE INDEX UNIQ_D4E6F81978A7D91 (stock_rais_in_activity_id), UNIQUE INDEX UNIQ_D4E6F81FF8221C5 (house_keeping_id), INDEX IDX_D4E6F8175E23604 (town_id), INDEX IDX_D4E6F81DE95C867 (sector_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE agricultural_activity (id INT AUTO_INCREMENT NOT NULL, productor_id INT DEFAULT NULL, exploited_area_id INT DEFAULT NULL, source_of_supply_activity_id INT DEFAULT NULL, adress_id INT DEFAULT NULL, activity_create_date DATE NOT NULL, goal LONGTEXT NOT NULL, INDEX IDX_40DB987A55BB310E (productor_id), INDEX IDX_40DB987AA02BB56 (exploited_area_id), INDEX IDX_40DB987A4C5C771A (source_of_supply_activity_id), INDEX IDX_40DB987A8486F9AC (adress_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, province_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_2D5B0234E946114A (province_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exploited_area (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fiching_activity (id INT AUTO_INCREMENT NOT NULL, productor_id INT DEFAULT NULL, source_of_supply_activity_id INT DEFAULT NULL, address_id INT DEFAULT NULL, fiching_activity_type_id INT DEFAULT NULL, activity_create_date DATE NOT NULL, goal LONGTEXT NOT NULL, INDEX IDX_F6B4F24155BB310E (productor_id), INDEX IDX_F6B4F2414C5C771A (source_of_supply_activity_id), UNIQUE INDEX UNIQ_F6B4F241F5B7AF75 (address_id), INDEX IDX_F6B4F241E1FD70F3 (fiching_activity_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fiching_activity_type (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE house_keeping (id INT AUTO_INCREMENT NOT NULL, address_id INT DEFAULT NULL, nim VARCHAR(255) NOT NULL, reference VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D70C549F5B7AF75 (address_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE level_study (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE monitor (id INT AUTO_INCREMENT NOT NULL, ot_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, INDEX IDX_E1159985A01D3C68 (ot_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nfc (id INT AUTO_INCREMENT NOT NULL, productor_id INT DEFAULT NULL, metadata LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_5A14C3FB55BB310E (productor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ot (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE piece_identification_type (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE productor (id INT AUTO_INCREMENT NOT NULL, level_study_id INT DEFAULT NULL, housekeeping_id INT DEFAULT NULL, monitor_id INT DEFAULT NULL, type_piece_of_identification_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, sexe VARCHAR(255) NOT NULL, phone_number VARCHAR(255) NOT NULL, birthdate DATE NOT NULL, nui VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, altitude DOUBLE PRECISION NOT NULL, number_piece_of_identification VARCHAR(255) NOT NULL, photo_piece_of_identification LONGBLOB NOT NULL, household_size INT NOT NULL, deleted_at DATE DEFAULT NULL, incumbent_photo LONGBLOB NOT NULL, INDEX IDX_173A0706A19A639 (level_study_id), INDEX IDX_173A0702839160B (housekeeping_id), INDEX IDX_173A0704CE1C902 (monitor_id), INDEX IDX_173A07034DA76C5 (type_piece_of_identification_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE productor_smartphone (productor_id INT NOT NULL, smartphone_id INT NOT NULL, INDEX IDX_170529FA55BB310E (productor_id), INDEX IDX_170529FA2E4F4908 (smartphone_id), PRIMARY KEY(productor_id, smartphone_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE province (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sector (id INT AUTO_INCREMENT NOT NULL, territorry_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_4BA3D9E87ED18DD7 (territorry_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE smartphone (id INT AUTO_INCREMENT NOT NULL, metadata LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE source_of_supply_activity (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock_rainsing_activity_type (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock_raising_activity (id INT AUTO_INCREMENT NOT NULL, productor_id INT DEFAULT NULL, stock_rainsing_activity_type_id INT DEFAULT NULL, source_of_supply_activity_id INT DEFAULT NULL, activity_create_date DATE NOT NULL, goal LONGTEXT NOT NULL, INDEX IDX_2BE2B53855BB310E (productor_id), INDEX IDX_2BE2B5389AE27267 (stock_rainsing_activity_type_id), INDEX IDX_2BE2B5384C5C771A (source_of_supply_activity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE territorry (id INT AUTO_INCREMENT NOT NULL, province_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_BC174F8AE946114A (province_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE town (id INT AUTO_INCREMENT NOT NULL, city_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_4CE6C7A48BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81978A7D91 FOREIGN KEY (stock_rais_in_activity_id) REFERENCES stock_raising_activity (id)');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81FF8221C5 FOREIGN KEY (house_keeping_id) REFERENCES house_keeping (id)');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F8175E23604 FOREIGN KEY (town_id) REFERENCES town (id)');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81DE95C867 FOREIGN KEY (sector_id) REFERENCES sector (id)');
        $this->addSql('ALTER TABLE agricultural_activity ADD CONSTRAINT FK_40DB987A55BB310E FOREIGN KEY (productor_id) REFERENCES productor (id)');
        $this->addSql('ALTER TABLE agricultural_activity ADD CONSTRAINT FK_40DB987AA02BB56 FOREIGN KEY (exploited_area_id) REFERENCES exploited_area (id)');
        $this->addSql('ALTER TABLE agricultural_activity ADD CONSTRAINT FK_40DB987A4C5C771A FOREIGN KEY (source_of_supply_activity_id) REFERENCES source_of_supply_activity (id)');
        $this->addSql('ALTER TABLE agricultural_activity ADD CONSTRAINT FK_40DB987A8486F9AC FOREIGN KEY (adress_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B0234E946114A FOREIGN KEY (province_id) REFERENCES province (id)');
        $this->addSql('ALTER TABLE fiching_activity ADD CONSTRAINT FK_F6B4F24155BB310E FOREIGN KEY (productor_id) REFERENCES productor (id)');
        $this->addSql('ALTER TABLE fiching_activity ADD CONSTRAINT FK_F6B4F2414C5C771A FOREIGN KEY (source_of_supply_activity_id) REFERENCES source_of_supply_activity (id)');
        $this->addSql('ALTER TABLE fiching_activity ADD CONSTRAINT FK_F6B4F241F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE fiching_activity ADD CONSTRAINT FK_F6B4F241E1FD70F3 FOREIGN KEY (fiching_activity_type_id) REFERENCES fiching_activity_type (id)');
        $this->addSql('ALTER TABLE house_keeping ADD CONSTRAINT FK_8D70C549F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
        $this->addSql('ALTER TABLE monitor ADD CONSTRAINT FK_E1159985A01D3C68 FOREIGN KEY (ot_id) REFERENCES ot (id)');
        $this->addSql('ALTER TABLE nfc ADD CONSTRAINT FK_5A14C3FB55BB310E FOREIGN KEY (productor_id) REFERENCES productor (id)');
        $this->addSql('ALTER TABLE productor ADD CONSTRAINT FK_173A0706A19A639 FOREIGN KEY (level_study_id) REFERENCES level_study (id)');
        $this->addSql('ALTER TABLE productor ADD CONSTRAINT FK_173A0702839160B FOREIGN KEY (housekeeping_id) REFERENCES house_keeping (id)');
        $this->addSql('ALTER TABLE productor ADD CONSTRAINT FK_173A0704CE1C902 FOREIGN KEY (monitor_id) REFERENCES monitor (id)');
        $this->addSql('ALTER TABLE productor ADD CONSTRAINT FK_173A07034DA76C5 FOREIGN KEY (type_piece_of_identification_id) REFERENCES piece_identification_type (id)');
        $this->addSql('ALTER TABLE productor_smartphone ADD CONSTRAINT FK_170529FA55BB310E FOREIGN KEY (productor_id) REFERENCES productor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE productor_smartphone ADD CONSTRAINT FK_170529FA2E4F4908 FOREIGN KEY (smartphone_id) REFERENCES smartphone (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sector ADD CONSTRAINT FK_4BA3D9E87ED18DD7 FOREIGN KEY (territorry_id) REFERENCES territorry (id)');
        $this->addSql('ALTER TABLE stock_raising_activity ADD CONSTRAINT FK_2BE2B53855BB310E FOREIGN KEY (productor_id) REFERENCES productor (id)');
        $this->addSql('ALTER TABLE stock_raising_activity ADD CONSTRAINT FK_2BE2B5389AE27267 FOREIGN KEY (stock_rainsing_activity_type_id) REFERENCES stock_rainsing_activity_type (id)');
        $this->addSql('ALTER TABLE stock_raising_activity ADD CONSTRAINT FK_2BE2B5384C5C771A FOREIGN KEY (source_of_supply_activity_id) REFERENCES source_of_supply_activity (id)');
        $this->addSql('ALTER TABLE territorry ADD CONSTRAINT FK_BC174F8AE946114A FOREIGN KEY (province_id) REFERENCES province (id)');
        $this->addSql('ALTER TABLE town ADD CONSTRAINT FK_4CE6C7A48BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agricultural_activity DROP FOREIGN KEY FK_40DB987A8486F9AC');
        $this->addSql('ALTER TABLE fiching_activity DROP FOREIGN KEY FK_F6B4F241F5B7AF75');
        $this->addSql('ALTER TABLE house_keeping DROP FOREIGN KEY FK_8D70C549F5B7AF75');
        $this->addSql('ALTER TABLE town DROP FOREIGN KEY FK_4CE6C7A48BAC62AF');
        $this->addSql('ALTER TABLE agricultural_activity DROP FOREIGN KEY FK_40DB987AA02BB56');
        $this->addSql('ALTER TABLE fiching_activity DROP FOREIGN KEY FK_F6B4F241E1FD70F3');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81FF8221C5');
        $this->addSql('ALTER TABLE productor DROP FOREIGN KEY FK_173A0702839160B');
        $this->addSql('ALTER TABLE productor DROP FOREIGN KEY FK_173A0706A19A639');
        $this->addSql('ALTER TABLE productor DROP FOREIGN KEY FK_173A0704CE1C902');
        $this->addSql('ALTER TABLE monitor DROP FOREIGN KEY FK_E1159985A01D3C68');
        $this->addSql('ALTER TABLE productor DROP FOREIGN KEY FK_173A07034DA76C5');
        $this->addSql('ALTER TABLE agricultural_activity DROP FOREIGN KEY FK_40DB987A55BB310E');
        $this->addSql('ALTER TABLE fiching_activity DROP FOREIGN KEY FK_F6B4F24155BB310E');
        $this->addSql('ALTER TABLE nfc DROP FOREIGN KEY FK_5A14C3FB55BB310E');
        $this->addSql('ALTER TABLE productor_smartphone DROP FOREIGN KEY FK_170529FA55BB310E');
        $this->addSql('ALTER TABLE stock_raising_activity DROP FOREIGN KEY FK_2BE2B53855BB310E');
        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B0234E946114A');
        $this->addSql('ALTER TABLE territorry DROP FOREIGN KEY FK_BC174F8AE946114A');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81DE95C867');
        $this->addSql('ALTER TABLE productor_smartphone DROP FOREIGN KEY FK_170529FA2E4F4908');
        $this->addSql('ALTER TABLE agricultural_activity DROP FOREIGN KEY FK_40DB987A4C5C771A');
        $this->addSql('ALTER TABLE fiching_activity DROP FOREIGN KEY FK_F6B4F2414C5C771A');
        $this->addSql('ALTER TABLE stock_raising_activity DROP FOREIGN KEY FK_2BE2B5384C5C771A');
        $this->addSql('ALTER TABLE stock_raising_activity DROP FOREIGN KEY FK_2BE2B5389AE27267');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F81978A7D91');
        $this->addSql('ALTER TABLE sector DROP FOREIGN KEY FK_4BA3D9E87ED18DD7');
        $this->addSql('ALTER TABLE address DROP FOREIGN KEY FK_D4E6F8175E23604');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE agricultural_activity');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE exploited_area');
        $this->addSql('DROP TABLE fiching_activity');
        $this->addSql('DROP TABLE fiching_activity_type');
        $this->addSql('DROP TABLE house_keeping');
        $this->addSql('DROP TABLE level_study');
        $this->addSql('DROP TABLE monitor');
        $this->addSql('DROP TABLE nfc');
        $this->addSql('DROP TABLE ot');
        $this->addSql('DROP TABLE piece_identification_type');
        $this->addSql('DROP TABLE productor');
        $this->addSql('DROP TABLE productor_smartphone');
        $this->addSql('DROP TABLE province');
        $this->addSql('DROP TABLE sector');
        $this->addSql('DROP TABLE smartphone');
        $this->addSql('DROP TABLE source_of_supply_activity');
        $this->addSql('DROP TABLE stock_rainsing_activity_type');
        $this->addSql('DROP TABLE stock_raising_activity');
        $this->addSql('DROP TABLE territorry');
        $this->addSql('DROP TABLE town');
    }
}
