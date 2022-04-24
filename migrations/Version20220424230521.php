<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220424230521 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE gift ADD buyer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE gift ADD receiver_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE gift ADD CONSTRAINT FK_A47C990D6C755722 FOREIGN KEY (buyer_id) REFERENCES people (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE gift ADD CONSTRAINT FK_A47C990DCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES people (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A47C990D6C755722 ON gift (buyer_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A47C990DCD53EDB6 ON gift (receiver_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE gift DROP CONSTRAINT FK_A47C990D6C755722');
        $this->addSql('ALTER TABLE gift DROP CONSTRAINT FK_A47C990DCD53EDB6');
        $this->addSql('DROP INDEX UNIQ_A47C990D6C755722');
        $this->addSql('DROP INDEX UNIQ_A47C990DCD53EDB6');
        $this->addSql('ALTER TABLE gift DROP buyer_id');
        $this->addSql('ALTER TABLE gift DROP receiver_id');
    }
}
