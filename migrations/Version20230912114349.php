<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230912114349 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create initial entities: category, tag and task';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tag_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE task_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, owner_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_64C19C17E3C61F9 ON category (owner_id)');
        $this->addSql('CREATE TABLE tag (id INT NOT NULL, owner_id INT NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(7) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_389B7837E3C61F9 ON tag (owner_id)');
        $this->addSql('CREATE TABLE task (id INT NOT NULL, owner_id INT NOT NULL, categories_id INT NOT NULL, title VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, priority SMALLINT DEFAULT NULL, due_date DATE DEFAULT NULL, done BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_527EDB257E3C61F9 ON task (owner_id)');
        $this->addSql('CREATE INDEX IDX_527EDB25A21214B7 ON task (categories_id)');
        $this->addSql('COMMENT ON COLUMN task.due_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN task.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN task.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE task_tag (task_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(task_id, tag_id))');
        $this->addSql('CREATE INDEX IDX_6C0B4F048DB60186 ON task_tag (task_id)');
        $this->addSql('CREATE INDEX IDX_6C0B4F04BAD26311 ON task_tag (tag_id)');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C17E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B7837E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB257E3C61F9 FOREIGN KEY (owner_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25A21214B7 FOREIGN KEY (categories_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task_tag ADD CONSTRAINT FK_6C0B4F048DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE task_tag ADD CONSTRAINT FK_6C0B4F04BAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tag_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE task_id_seq CASCADE');
        $this->addSql('ALTER TABLE category DROP CONSTRAINT FK_64C19C17E3C61F9');
        $this->addSql('ALTER TABLE tag DROP CONSTRAINT FK_389B7837E3C61F9');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB257E3C61F9');
        $this->addSql('ALTER TABLE task DROP CONSTRAINT FK_527EDB25A21214B7');
        $this->addSql('ALTER TABLE task_tag DROP CONSTRAINT FK_6C0B4F048DB60186');
        $this->addSql('ALTER TABLE task_tag DROP CONSTRAINT FK_6C0B4F04BAD26311');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE task_tag');
    }
}
