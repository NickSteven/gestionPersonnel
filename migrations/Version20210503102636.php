<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210503102636 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE conges (id INT AUTO_INCREMENT NOT NULL, users_id INT NOT NULL, date_demande DATE NOT NULL, date_depart DATE NOT NULL, date_retour DATE NOT NULL, motif VARCHAR(255) NOT NULL, etat VARCHAR(255) NOT NULL, INDEX IDX_6327DE3A67B3B43D (users_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE conges ADD CONSTRAINT FK_6327DE3A67B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE permission DROP date_permission, DROP heure_depart, DROP heure_retour');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE conges');
        $this->addSql('ALTER TABLE permission ADD date_permission DATE NOT NULL, ADD heure_depart TIME NOT NULL, ADD heure_retour TIME NOT NULL');
    }
}
