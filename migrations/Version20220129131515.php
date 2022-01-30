<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220129131515 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE house_keeping (id INT AUTO_INCREMENT NOT NULL, nim VARCHAR(255) NOT NULL, reference VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE productor_smartphone (productor_id INT NOT NULL, smartphone_id INT NOT NULL, INDEX IDX_170529FA55BB310E (productor_id), INDEX IDX_170529FA2E4F4908 (smartphone_id), PRIMARY KEY(productor_id, smartphone_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE smartphone (id INT AUTO_INCREMENT NOT NULL, metadata LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE productor_smartphone ADD CONSTRAINT FK_170529FA55BB310E FOREIGN KEY (productor_id) REFERENCES productor (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE productor_smartphone ADD CONSTRAINT FK_170529FA2E4F4908 FOREIGN KEY (smartphone_id) REFERENCES smartphone (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE productor ADD housekeeping_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE productor ADD CONSTRAINT FK_173A0702839160B FOREIGN KEY (housekeeping_id) REFERENCES house_keeping (id)');
        $this->addSql('CREATE INDEX IDX_173A0702839160B ON productor (housekeeping_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE productor DROP FOREIGN KEY FK_173A0702839160B');
        $this->addSql('ALTER TABLE productor_smartphone DROP FOREIGN KEY FK_170529FA2E4F4908');
        $this->addSql('DROP TABLE house_keeping');
        $this->addSql('DROP TABLE productor_smartphone');
        $this->addSql('DROP TABLE smartphone');
        $this->addSql('DROP INDEX IDX_173A0702839160B ON productor');
        $this->addSql('ALTER TABLE productor DROP housekeeping_id');
    }
}
