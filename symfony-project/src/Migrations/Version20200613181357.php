<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200613181357 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE user_sector (user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', sector_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_2EF1C2D5A76ED395 (user_id), INDEX IDX_2EF1C2D5DE95C867 (sector_id), PRIMARY KEY(user_id, sector_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_sector ADD CONSTRAINT FK_2EF1C2D5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_sector ADD CONSTRAINT FK_2EF1C2D5DE95C867 FOREIGN KEY (sector_id) REFERENCES sector (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE user_sector');
    }
}
