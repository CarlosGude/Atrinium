<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200613134358 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Add exchange and Fixer data entity';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE exchange (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', origin_currency VARCHAR(3) NOT NULL, destiny_currency VARCHAR(3) NOT NULL, origin_value DOUBLE PRECISION NOT NULL, final_currency DOUBLE PRECISION NOT NULL, date DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fixer_data (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', base_currency VARCHAR(3) NOT NULL, date DATE NOT NULL, rate JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE exchange');
        $this->addSql('DROP TABLE fixer_data');
    }
}
