<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230907154008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book ADD COLUMN new_release_date DATE');
        $this->addSql('UPDATE book SET new_release_date = TO_TIMESTAMP(release_date)');
        $this->addSql('ALTER TABLE book DROP COLUMN release_date');
        $this->addSql('ALTER TABLE book RENAME COLUMN new_release_date TO release_date');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE book ALTER release_date TYPE INT');
    }
}
