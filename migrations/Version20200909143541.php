<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200909143541 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('CREATE INDEX IDX_23A0E66BCF5E72D ON article (categorie_id)');
        $this->addSql('DROP INDEX u_libelle ON article');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_23A0E66A4D60759 ON article (libelle)');
        $this->addSql('ALTER TABLE categorie ADD nom VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66BCF5E72D');
        $this->addSql('DROP INDEX IDX_23A0E66BCF5E72D ON article');
        $this->addSql('DROP INDEX uniq_23a0e66a4d60759 ON article');
        $this->addSql('CREATE UNIQUE INDEX U_Libelle ON article (libelle)');
        $this->addSql('ALTER TABLE categorie DROP nom');
    }
}
