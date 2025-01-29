<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250128172733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE liaison_plat_ingredients (id INT AUTO_INCREMENT NOT NULL, id_plat_id INT NOT NULL, id_ingredients_id INT NOT NULL, quantite INT NOT NULL, INDEX IDX_37ECAA599A01C10 (id_plat_id), INDEX IDX_37ECAA593E8AF0C8 (id_ingredients_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plat (id INT AUTO_INCREMENT NOT NULL, id_restaurant_id INT NOT NULL, nom VARCHAR(255) NOT NULL, temps_de_preparation INT NOT NULL, INDEX IDX_2038A207FCFA10B (id_restaurant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE liaison_plat_ingredients ADD CONSTRAINT FK_37ECAA599A01C10 FOREIGN KEY (id_plat_id) REFERENCES plat (id)');
        $this->addSql('ALTER TABLE liaison_plat_ingredients ADD CONSTRAINT FK_37ECAA593E8AF0C8 FOREIGN KEY (id_ingredients_id) REFERENCES ingredients (id)');
        $this->addSql('ALTER TABLE plat ADD CONSTRAINT FK_2038A207FCFA10B FOREIGN KEY (id_restaurant_id) REFERENCES restaurant (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE liaison_plat_ingredients DROP FOREIGN KEY FK_37ECAA599A01C10');
        $this->addSql('ALTER TABLE liaison_plat_ingredients DROP FOREIGN KEY FK_37ECAA593E8AF0C8');
        $this->addSql('ALTER TABLE plat DROP FOREIGN KEY FK_2038A207FCFA10B');
        $this->addSql('DROP TABLE liaison_plat_ingredients');
        $this->addSql('DROP TABLE plat');
        $this->addSql('DROP TABLE restaurant');
    }
}
