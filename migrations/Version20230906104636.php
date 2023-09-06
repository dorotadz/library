<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230906104636 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book ADD author_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE book ADD branch_id INT NOT NULL');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331F675F31B FOREIGN KEY (author_id) REFERENCES author (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A331DCD6CC49 FOREIGN KEY (branch_id) REFERENCES branch (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_CBE5A331F675F31B ON book (author_id)');
        $this->addSql('CREATE INDEX IDX_CBE5A331DCD6CC49 ON book (branch_id)');
        $this->addSql('ALTER TABLE comment ADD book_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD user_login_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CBC3F045D FOREIGN KEY (user_login_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_9474526C16A2B381 ON comment (book_id)');
        $this->addSql('CREATE INDEX IDX_9474526CBC3F045D ON comment (user_login_id)');
        $this->addSql('ALTER TABLE lendings ADD book_id INT NOT NULL');
        $this->addSql('ALTER TABLE lendings ADD lender_id INT NOT NULL');
        $this->addSql('ALTER TABLE lendings ADD CONSTRAINT FK_8273353D16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE lendings ADD CONSTRAINT FK_8273353D855D3E3D FOREIGN KEY (lender_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8273353D16A2B381 ON lendings (book_id)');
        $this->addSql('CREATE INDEX IDX_8273353D855D3E3D ON lendings (lender_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C16A2B381');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CBC3F045D');
        $this->addSql('DROP INDEX IDX_9474526C16A2B381');
        $this->addSql('DROP INDEX IDX_9474526CBC3F045D');
        $this->addSql('ALTER TABLE comment DROP book_id');
        $this->addSql('ALTER TABLE comment DROP user_login_id');
        $this->addSql('ALTER TABLE book DROP CONSTRAINT FK_CBE5A331F675F31B');
        $this->addSql('ALTER TABLE book DROP CONSTRAINT FK_CBE5A331DCD6CC49');
        $this->addSql('DROP INDEX IDX_CBE5A331F675F31B');
        $this->addSql('DROP INDEX IDX_CBE5A331DCD6CC49');
        $this->addSql('ALTER TABLE book DROP author_id');
        $this->addSql('ALTER TABLE book DROP branch_id');
        $this->addSql('ALTER TABLE lendings DROP CONSTRAINT FK_8273353D16A2B381');
        $this->addSql('ALTER TABLE lendings DROP CONSTRAINT FK_8273353D855D3E3D');
        $this->addSql('DROP INDEX IDX_8273353D16A2B381');
        $this->addSql('DROP INDEX IDX_8273353D855D3E3D');
        $this->addSql('ALTER TABLE lendings DROP book_id');
        $this->addSql('ALTER TABLE lendings DROP lender_id');
    }
}
