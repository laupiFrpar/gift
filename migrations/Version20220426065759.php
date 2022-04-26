<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220426065759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE participants (gift_id INT NOT NULL, people_id INT NOT NULL, PRIMARY KEY(gift_id, people_id))');
        $this->addSql('CREATE INDEX IDX_7169709297A95A83 ON participants (gift_id)');
        $this->addSql('CREATE INDEX IDX_716970923147C936 ON participants (people_id)');
        $this->addSql('ALTER TABLE participants ADD CONSTRAINT FK_7169709297A95A83 FOREIGN KEY (gift_id) REFERENCES gift (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE participants ADD CONSTRAINT FK_716970923147C936 FOREIGN KEY (people_id) REFERENCES people (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE participants');
    }
}
