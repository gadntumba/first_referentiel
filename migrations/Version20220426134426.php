<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220426134426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agricultural_activity_type (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE coordinator (id INT NOT NULL, user_id INT DEFAULT NULL, ot_id INT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL, INDEX IDX_15FE0E6AA76ED395 (user_id), INDEX IDX_15FE0E6AA01D3C68 (ot_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stock_raising_activity_sub_type (id INT AUTO_INCREMENT NOT NULL, stock_rainsing_activity_type_id INT DEFAULT NULL, libelle VARCHAR(255) NOT NULL, INDEX IDX_7723754A9AE27267 (stock_rainsing_activity_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE coordinator ADD CONSTRAINT FK_15FE0E6AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE coordinator ADD CONSTRAINT FK_15FE0E6AA01D3C68 FOREIGN KEY (ot_id) REFERENCES ot (id)');
        $this->addSql('ALTER TABLE stock_raising_activity_sub_type ADD CONSTRAINT FK_7723754A9AE27267 FOREIGN KEY (stock_rainsing_activity_type_id) REFERENCES stock_rainsing_activity_type (id)');
        $this->addSql('DROP TABLE supervisor_ot');
        $this->addSql('ALTER TABLE agricultural_activity ADD agricultural_activity_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agricultural_activity ADD CONSTRAINT FK_40DB987A70B96C48 FOREIGN KEY (agricultural_activity_type_id) REFERENCES agricultural_activity_type (id)');
        $this->addSql('CREATE INDEX IDX_40DB987A70B96C48 ON agricultural_activity (agricultural_activity_type_id)');
        $this->addSql('ALTER TABLE monitor ADD supervisor_post_id INT DEFAULT NULL, ADD goal_recordings INT NOT NULL, DROP name, DROP phone_number, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE monitor ADD CONSTRAINT FK_E11599851D41D3A5 FOREIGN KEY (supervisor_post_id) REFERENCES supervisor (id)');
        $this->addSql('CREATE INDEX IDX_E11599851D41D3A5 ON monitor (supervisor_post_id)');
        $this->addSql('ALTER TABLE ot CHANGE id id INT NOT NULL, CHANGE goal_recordings goal_recordings INT NOT NULL');
        $this->addSql('ALTER TABLE productor CHANGE photo_piece_of_identification photo_piece_of_identification VARCHAR(255) NOT NULL, CHANGE incumbent_photo incumbent_photo VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE province ADD ot_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE province ADD CONSTRAINT FK_4ADAD40BA01D3C68 FOREIGN KEY (ot_id) REFERENCES ot (id)');
        $this->addSql('CREATE INDEX IDX_4ADAD40BA01D3C68 ON province (ot_id)');
        $this->addSql('ALTER TABLE supervisor ADD ot_id INT DEFAULT NULL, ADD user_id INT DEFAULT NULL, ADD updated_at DATETIME DEFAULT NULL, ADD deleted_at DATETIME DEFAULT NULL, ADD slug VARCHAR(255) DEFAULT NULL, CHANGE id id INT NOT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE supervisor ADD CONSTRAINT FK_4D9192F8A01D3C68 FOREIGN KEY (ot_id) REFERENCES ot (id)');
        $this->addSql('ALTER TABLE supervisor ADD CONSTRAINT FK_4D9192F8A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_4D9192F8A01D3C68 ON supervisor (ot_id)');
        $this->addSql('CREATE INDEX IDX_4D9192F8A76ED395 ON supervisor (user_id)');
        $this->addSql('ALTER TABLE messenger_messages CHANGE queue_name queue_name VARCHAR(190) NOT NULL');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agricultural_activity DROP FOREIGN KEY FK_40DB987A70B96C48');
        $this->addSql('CREATE TABLE supervisor_ot (supervisor_id INT NOT NULL, ot_id INT NOT NULL, INDEX IDX_52041BBD19E9AC5F (supervisor_id), INDEX IDX_52041BBDA01D3C68 (ot_id), PRIMARY KEY(supervisor_id, ot_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE supervisor_ot ADD CONSTRAINT FK_52041BBDA01D3C68 FOREIGN KEY (ot_id) REFERENCES ot (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE supervisor_ot ADD CONSTRAINT FK_52041BBD19E9AC5F FOREIGN KEY (supervisor_id) REFERENCES supervisor (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE agricultural_activity_type');
        $this->addSql('DROP TABLE coordinator');
        $this->addSql('DROP TABLE stock_raising_activity_sub_type');
        $this->addSql('DROP INDEX IDX_40DB987A70B96C48 ON agricultural_activity');
        $this->addSql('ALTER TABLE agricultural_activity DROP agricultural_activity_type_id');
        $this->addSql('DROP INDEX IDX_75EA56E0FB7336F0 ON messenger_messages');
        $this->addSql('DROP INDEX IDX_75EA56E0E3BD61CE ON messenger_messages');
        $this->addSql('ALTER TABLE messenger_messages CHANGE queue_name queue_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE monitor DROP FOREIGN KEY FK_E11599851D41D3A5');
        $this->addSql('DROP INDEX IDX_E11599851D41D3A5 ON monitor');
        $this->addSql('ALTER TABLE monitor ADD name VARCHAR(255) NOT NULL, ADD phone_number VARCHAR(255) NOT NULL, DROP supervisor_post_id, DROP goal_recordings, CHANGE id id INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE ot CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE goal_recordings goal_recordings VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE productor CHANGE photo_piece_of_identification photo_piece_of_identification LONGBLOB NOT NULL, CHANGE incumbent_photo incumbent_photo LONGBLOB NOT NULL');
        $this->addSql('ALTER TABLE province DROP FOREIGN KEY FK_4ADAD40BA01D3C68');
        $this->addSql('DROP INDEX IDX_4ADAD40BA01D3C68 ON province');
        $this->addSql('ALTER TABLE province DROP ot_id');
        $this->addSql('ALTER TABLE supervisor DROP FOREIGN KEY FK_4D9192F8A01D3C68');
        $this->addSql('ALTER TABLE supervisor DROP FOREIGN KEY FK_4D9192F8A76ED395');
        $this->addSql('DROP INDEX IDX_4D9192F8A01D3C68 ON supervisor');
        $this->addSql('DROP INDEX IDX_4D9192F8A76ED395 ON supervisor');
        $this->addSql('ALTER TABLE supervisor DROP ot_id, DROP user_id, DROP updated_at, DROP deleted_at, DROP slug, CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE created_at created_at DATE NOT NULL');
    }
}
