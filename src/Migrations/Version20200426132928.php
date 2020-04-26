<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200426132928 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE contenu_panier_produit');
        $this->addSql('ALTER TABLE contenu_panier ADD produit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contenu_panier ADD CONSTRAINT FK_80507DC0F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_80507DC0F347EFB ON contenu_panier (produit_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE contenu_panier_produit (contenu_panier_id INT NOT NULL, produit_id INT NOT NULL, INDEX IDX_179C43E361405BF (contenu_panier_id), INDEX IDX_179C43E3F347EFB (produit_id), PRIMARY KEY(contenu_panier_id, produit_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE contenu_panier_produit ADD CONSTRAINT FK_179C43E361405BF FOREIGN KEY (contenu_panier_id) REFERENCES contenu_panier (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contenu_panier_produit ADD CONSTRAINT FK_179C43E3F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contenu_panier DROP FOREIGN KEY FK_80507DC0F347EFB');
        $this->addSql('DROP INDEX IDX_80507DC0F347EFB ON contenu_panier');
        $this->addSql('ALTER TABLE contenu_panier DROP produit_id');
    }
}
