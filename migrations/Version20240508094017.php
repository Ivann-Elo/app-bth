<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240508094017 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client CHANGE civilite civilite VARCHAR(10) DEFAULT NULL, CHANGE prenom prenom VARCHAR(20) DEFAULT NULL, CHANGE nom nom VARCHAR(20) DEFAULT NULL, CHANGE ent ent VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE intervention CHANGE note note VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE photo CHANGE updated_at updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL, CHANGE google_id google_id VARCHAR(255) DEFAULT NULL, CHANGE nom nom VARCHAR(20) DEFAULT NULL, CHANGE prenom prenom VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client CHANGE civilite civilite VARCHAR(10) DEFAULT \'NULL\', CHANGE prenom prenom VARCHAR(20) DEFAULT \'NULL\', CHANGE nom nom VARCHAR(20) DEFAULT \'NULL\', CHANGE ent ent VARCHAR(50) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE intervention CHANGE note note VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\' COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE photo CHANGE updated_at updated_at DATETIME DEFAULT \'NULL\' COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE `user` CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`, CHANGE google_id google_id VARCHAR(255) DEFAULT \'NULL\', CHANGE nom nom VARCHAR(20) DEFAULT \'NULL\', CHANGE prenom prenom VARCHAR(20) DEFAULT \'NULL\'');
    }
}
