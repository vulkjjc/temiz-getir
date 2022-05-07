<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220504022317 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location CHANGE country_id country_id INT NOT NULL, CHANGE city_id city_id INT NOT NULL, CHANGE provice_id provice_id INT NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE location_id location_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location CHANGE country_id country_id INT DEFAULT NULL, CHANGE city_id city_id INT DEFAULT NULL, CHANGE provice_id provice_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE location_id location_id INT DEFAULT NULL');
    }
}
