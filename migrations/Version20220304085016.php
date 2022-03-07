<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220304085016 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE smartphone ADD imei VARCHAR(255) NOT NULL, ADD email_adress VARCHAR(255) NOT NULL, ADD phone_number VARCHAR(255) NOT NULL, ADD prefix_nui VARCHAR(255) NOT NULL, ADD created_at DATE NOT NULL, ADD longitude DOUBLE PRECISION NOT NULL, ADD altitude DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE smartphone DROP imei, DROP email_adress, DROP phone_number, DROP prefix_nui, DROP created_at, DROP longitude, DROP altitude');
    }
}
