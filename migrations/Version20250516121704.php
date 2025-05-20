<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250516121704 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE event_category (event_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_40A0F01171F7E88B (event_id), INDEX IDX_40A0F01112469DE2 (category_id), PRIMARY KEY(event_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_category ADD CONSTRAINT FK_40A0F01171F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_category ADD CONSTRAINT FK_40A0F01112469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event ADD created_by_id INT NOT NULL, ADD created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, ADD lieu VARCHAR(255) NOT NULL, ADD price DOUBLE PRECISION NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA7B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_3BAE0AA7B03A8386 ON event (created_by_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE event_category DROP FOREIGN KEY FK_40A0F01171F7E88B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event_category DROP FOREIGN KEY FK_40A0F01112469DE2
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE event_category
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA7B03A8386
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_3BAE0AA7B03A8386 ON event
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE event DROP created_by_id, DROP created_at, DROP lieu, DROP price
        SQL);
    }
}
