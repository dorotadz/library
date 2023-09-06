<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230906144742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lendings DROP CONSTRAINT fk_8273353d855d3e3d');
        $this->addSql('DROP INDEX idx_8273353d855d3e3d');
        $this->addSql('ALTER TABLE lendings DROP lender_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE lendings ADD lender_id INT NOT NULL');
        $this->addSql('ALTER TABLE lendings ADD CONSTRAINT fk_8273353d855d3e3d FOREIGN KEY (lender_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_8273353d855d3e3d ON lendings (lender_id)');
    }
}
