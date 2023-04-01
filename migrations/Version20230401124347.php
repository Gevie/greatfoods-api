<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Add 'menus' table with fields for name, description, order, and timestamps.
 */
final class Version20230401124347 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add menus table with fields for name, description, order, and timestamps.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE menus (
            id INT AUTO_INCREMENT NOT NULL,
            name VARCHAR(128) NOT NULL,
            description VARCHAR(255) DEFAULT NULL,
            `order` SMALLINT UNSIGNED DEFAULT NULL,
            created DATETIME NOT NULL,
            modified DATETIME DEFAULT NULL,
            deleted DATETIME DEFAULT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE menus');
    }
}
