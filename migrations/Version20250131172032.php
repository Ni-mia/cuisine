<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250131172032 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE stock (id INT AUTO_INCREMENT NOT NULL, id_restaurant_id INT NOT NULL, id_ingredient_id INT NOT NULL, id_type_id INT DEFAULT NULL, quantite INT NOT NULL, dt DATE NOT NULL, deleted_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_4B365660FCFA10B (id_restaurant_id), INDEX IDX_4B3656602D1731E9 (id_ingredient_id), INDEX IDX_4B3656601BD125E3 (id_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_mvt (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, type_mvt INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B365660FCFA10B FOREIGN KEY (id_restaurant_id) REFERENCES restaurant (id)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B3656602D1731E9 FOREIGN KEY (id_ingredient_id) REFERENCES ingredients (id)');
        $this->addSql('ALTER TABLE stock ADD CONSTRAINT FK_4B3656601BD125E3 FOREIGN KEY (id_type_id) REFERENCES type_mvt (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B365660FCFA10B');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B3656602D1731E9');
        $this->addSql('ALTER TABLE stock DROP FOREIGN KEY FK_4B3656601BD125E3');
        $this->addSql('DROP TABLE stock');
        $this->addSql('DROP TABLE type_mvt');
    }
}
