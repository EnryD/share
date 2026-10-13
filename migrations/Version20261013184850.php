<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20261013184850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(50) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE fichier (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, nom_fichier VARCHAR(255) NOT NULL, categorie_id INT NOT NULL, INDEX IDX_9B76551FBCF5E72D (categorie_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE fichier ADD CONSTRAINT FK_9B76551FBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE fichier DROP FOREIGN KEY FK_9B76551FBCF5E72D');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP TABLE fichier');
    }
}
