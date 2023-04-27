<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Add 'users' table with fields for email, password, roles and timestamps
 * 
 * Up: 
 *  - CREATE users
 * 
 * Down:
 *  - DROP users
 */
final class Version20230427120924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add users table with fields for email, password, roles and timestamps';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE users (
            id INT AUTO_INCREMENT NOT NULL,
            email VARCHAR(180) NOT NULL,
            password VARCHAR(255) NOT NULL,
            roles MEDIUMTEXT NOT NULL,
            created DATETIME NOT NULL,
            modified DATETIME DEFAULT NULL,
            deleted DATETIME DEFAULT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE users');
    }
}
