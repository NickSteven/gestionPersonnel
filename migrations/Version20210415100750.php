<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210415100750 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conges CHANGE date_demande date_demande DATE NOT NULL, CHANGE date_depart date_depart DATE NOT NULL, CHANGE date_retour date_retour DATE NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conges CHANGE date_demande date_demande DATETIME NOT NULL, CHANGE date_depart date_depart DATETIME NOT NULL, CHANGE date_retour date_retour DATETIME NOT NULL');
    }
}
