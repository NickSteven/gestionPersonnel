<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210527141915 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE soldes ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE soldes ADD CONSTRAINT FK_C8BEAA73A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C8BEAA73A76ED395 ON soldes (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE soldes DROP FOREIGN KEY FK_C8BEAA73A76ED395');
        $this->addSql('DROP INDEX UNIQ_C8BEAA73A76ED395 ON soldes');
        $this->addSql('ALTER TABLE soldes DROP user_id');
    }
}
