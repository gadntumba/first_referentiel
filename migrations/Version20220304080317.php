<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220304080317 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE monitor ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE monitor ADD CONSTRAINT FK_E1159985A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_E1159985A76ED395 ON monitor (user_id)');
        $this->addSql('ALTER TABLE ot ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ot ADD CONSTRAINT FK_F4D4D0B2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_F4D4D0B2A76ED395 ON ot (user_id)');
        $this->addSql('ALTER TABLE user ADD productor_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64955BB310E FOREIGN KEY (productor_id) REFERENCES productor (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D64955BB310E ON user (productor_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE monitor DROP FOREIGN KEY FK_E1159985A76ED395');
        $this->addSql('DROP INDEX IDX_E1159985A76ED395 ON monitor');
        $this->addSql('ALTER TABLE monitor DROP user_id');
        $this->addSql('ALTER TABLE ot DROP FOREIGN KEY FK_F4D4D0B2A76ED395');
        $this->addSql('DROP INDEX IDX_F4D4D0B2A76ED395 ON ot');
        $this->addSql('ALTER TABLE ot DROP user_id');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64955BB310E');
        $this->addSql('DROP INDEX UNIQ_8D93D64955BB310E ON user');
        $this->addSql('ALTER TABLE user DROP productor_id');
    }
}
