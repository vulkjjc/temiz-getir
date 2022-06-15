<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

use App\Entity\Country;
use App\Entity\City;
use App\Entity\Province;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220615185409 extends AbstractMigration implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    
    public array $locations = [
        "Turkey" => [
            "Istanbul" => [
                "Adalar",
                "Arnavutköy",
                "Ataşehir",
                "Avcılar",
                "Bağcılar",
                "Bahçelievler",
                "Bakırköy",
                "Başakşehir",
                "Bayrampaşa",
                "Beşiktaş",
                "Beykoz",
                "Beylikdüzü",
                "Beyoğlu",
                "Büyükçekmece",
                "Çatalca",
                "Çekmeköy",
                "Esenler",
                "Esenyurt",
                "Eyüpsultan",
                "Fatih",
                "Gaziosmanpaşa",
                "Güngören",
                "Kadıköy",
                "Kağıthane",
                "Kartal",
                "Küçükçekmece",
                "Maltepe",
                "Pendik",
                "Sancaktepe",
                "Sarıyer",
                "Silivri",
                "Sultanbeyli",
                "Sultangazi",
                "Şile",
                "Şişli",
                "Tuzla",
                "Ümraniye",
                "Üsküdar",
                "Zeytinburnu",
            ]
        ]
    ];

    public function getDescription(): string
    {
        return '';
    }

    public function postUp(Schema $schema): void
    {
        $entityManager = $this->container->get('doctrine.orm.entity_manager');

        foreach ($this->locations as $country => $cities) {
            $countryManager = new Country();
            $countryManager->setName($country);

            $entityManager->persist($countryManager);
            $entityManager->flush();

            foreach ($cities as $city => $provinces) {
                $cityManager = new City();
                $cityManager->setName($city);
                $cityManager->setCountry($countryManager);

                $entityManager->persist($cityManager);
                $entityManager->flush();

                foreach ($provinces as $province) {
                    $provinceManager = new Province();
                    $provinceManager->setName($province);
                    $provinceManager->setCity($cityManager);

                    $entityManager->persist($provinceManager);
                    $entityManager->flush();
                }
            }
        }
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_2D5B0234F92F3E70 (country_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dry_cleaning (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, rating_id INT NOT NULL, INDEX IDX_B36D94FA76ED395 (user_id), INDEX IDX_B36D94FA32EFC6 (rating_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ironing (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, rating_id INT NOT NULL, INDEX IDX_6A9D5853A76ED395 (user_id), INDEX IDX_6A9D5853A32EFC6 (rating_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, country_id INT NOT NULL, city_id INT NOT NULL, provice_id INT NOT NULL, address VARCHAR(255) NOT NULL, INDEX IDX_5E9E89CBF92F3E70 (country_id), INDEX IDX_5E9E89CB8BAC62AF (city_id), INDEX IDX_5E9E89CB297419A7 (provice_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phone (id INT AUTO_INCREMENT NOT NULL, phone VARCHAR(255) NOT NULL, phone_business VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE province (id INT AUTO_INCREMENT NOT NULL, city_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_4ADAD40B8BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, rating VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, rating_id INT NOT NULL, ironing_id INT DEFAULT NULL, dry_cleaning_id INT DEFAULT NULL, shoe_cleaning_id INT DEFAULT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_E19D9AD2A32EFC6 (rating_id), INDEX IDX_E19D9AD2F4676D16 (ironing_id), INDEX IDX_E19D9AD282C7E409 (dry_cleaning_id), INDEX IDX_E19D9AD2F50151A2 (shoe_cleaning_id), INDEX IDX_E19D9AD2A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shoe_cleaning (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, rating_id INT NOT NULL, INDEX IDX_2553ECC3A76ED395 (user_id), INDEX IDX_2553ECC3A32EFC6 (rating_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, phone_id INT NOT NULL, location_id INT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(1500) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D6493B7323CB (phone_id), UNIQUE INDEX UNIQ_8D93D64964D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_email_new (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, email_new VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E2C7E9E7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_password_reset (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, password_new VARCHAR(1500) NOT NULL, UNIQUE INDEX UNIQ_DA84AD0BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_provider_info (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, supply_amount INT NOT NULL, computer_available TINYINT(1) NOT NULL, delivery_available TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_C08FFF7BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_provider_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_B6DA6FE5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B0234F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE dry_cleaning ADD CONSTRAINT FK_B36D94FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE dry_cleaning ADD CONSTRAINT FK_B36D94FA32EFC6 FOREIGN KEY (rating_id) REFERENCES rating (id)');
        $this->addSql('ALTER TABLE ironing ADD CONSTRAINT FK_6A9D5853A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE ironing ADD CONSTRAINT FK_6A9D5853A32EFC6 FOREIGN KEY (rating_id) REFERENCES rating (id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CBF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB297419A7 FOREIGN KEY (provice_id) REFERENCES province (id)');
        $this->addSql('ALTER TABLE province ADD CONSTRAINT FK_4ADAD40B8BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2A32EFC6 FOREIGN KEY (rating_id) REFERENCES rating (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2F4676D16 FOREIGN KEY (ironing_id) REFERENCES ironing (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD282C7E409 FOREIGN KEY (dry_cleaning_id) REFERENCES dry_cleaning (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2F50151A2 FOREIGN KEY (shoe_cleaning_id) REFERENCES shoe_cleaning (id)');
        $this->addSql('ALTER TABLE service ADD CONSTRAINT FK_E19D9AD2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE shoe_cleaning ADD CONSTRAINT FK_2553ECC3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE shoe_cleaning ADD CONSTRAINT FK_2553ECC3A32EFC6 FOREIGN KEY (rating_id) REFERENCES rating (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6493B7323CB FOREIGN KEY (phone_id) REFERENCES phone (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64964D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE user_email_new ADD CONSTRAINT FK_E2C7E9E7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_password_reset ADD CONSTRAINT FK_DA84AD0BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_provider_info ADD CONSTRAINT FK_C08FFF7BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_provider_request ADD CONSTRAINT FK_B6DA6FE5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB8BAC62AF');
        $this->addSql('ALTER TABLE province DROP FOREIGN KEY FK_4ADAD40B8BAC62AF');
        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B0234F92F3E70');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CBF92F3E70');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD282C7E409');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2F4676D16');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64964D218E');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6493B7323CB');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB297419A7');
        $this->addSql('ALTER TABLE dry_cleaning DROP FOREIGN KEY FK_B36D94FA32EFC6');
        $this->addSql('ALTER TABLE ironing DROP FOREIGN KEY FK_6A9D5853A32EFC6');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2A32EFC6');
        $this->addSql('ALTER TABLE shoe_cleaning DROP FOREIGN KEY FK_2553ECC3A32EFC6');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2F50151A2');
        $this->addSql('ALTER TABLE dry_cleaning DROP FOREIGN KEY FK_B36D94FA76ED395');
        $this->addSql('ALTER TABLE ironing DROP FOREIGN KEY FK_6A9D5853A76ED395');
        $this->addSql('ALTER TABLE service DROP FOREIGN KEY FK_E19D9AD2A76ED395');
        $this->addSql('ALTER TABLE shoe_cleaning DROP FOREIGN KEY FK_2553ECC3A76ED395');
        $this->addSql('ALTER TABLE user_email_new DROP FOREIGN KEY FK_E2C7E9E7A76ED395');
        $this->addSql('ALTER TABLE user_password_reset DROP FOREIGN KEY FK_DA84AD0BA76ED395');
        $this->addSql('ALTER TABLE user_provider_info DROP FOREIGN KEY FK_C08FFF7BA76ED395');
        $this->addSql('ALTER TABLE user_provider_request DROP FOREIGN KEY FK_B6DA6FE5A76ED395');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE dry_cleaning');
        $this->addSql('DROP TABLE ironing');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE phone');
        $this->addSql('DROP TABLE province');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE service');
        $this->addSql('DROP TABLE shoe_cleaning');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_email_new');
        $this->addSql('DROP TABLE user_password_reset');
        $this->addSql('DROP TABLE user_provider_info');
        $this->addSql('DROP TABLE user_provider_request');
    }
}
