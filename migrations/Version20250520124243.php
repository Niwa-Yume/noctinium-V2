<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250520124243 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE interet (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, event_id INT NOT NULL, status_id INT DEFAULT NULL, INDEX IDX_A9816FA5A76ED395 (user_id), INDEX IDX_A9816FA571F7E88B (event_id), INDEX IDX_A9816FA56BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE interet ADD CONSTRAINT FK_A9816FA5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE interet ADD CONSTRAINT FK_A9816FA571F7E88B FOREIGN KEY (event_id) REFERENCES event (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE interet ADD CONSTRAINT FK_A9816FA56BF700BD FOREIGN KEY (status_id) REFERENCES status (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE interet DROP FOREIGN KEY FK_A9816FA5A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE interet DROP FOREIGN KEY FK_A9816FA571F7E88B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE interet DROP FOREIGN KEY FK_A9816FA56BF700BD
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE interet
        SQL);
    }
}
