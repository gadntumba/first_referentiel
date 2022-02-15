<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220215133706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agricultural_activity DROP INDEX UNIQ_40DB987A4C5C771A, ADD INDEX IDX_40DB987A4C5C771A (source_of_supply_activity_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agricultural_activity DROP INDEX IDX_40DB987A4C5C771A, ADD UNIQUE INDEX UNIQ_40DB987A4C5C771A (source_of_supply_activity_id)');
    }
}
