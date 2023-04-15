<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230415151436 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'change order name';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category RENAME COLUMN "order" TO order_number');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64C19C12B36786B ON category (title)');
        $this->addSql('ALTER TABLE forum RENAME COLUMN "order" TO order_number');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_64C19C12B36786B');
        $this->addSql('ALTER TABLE category RENAME COLUMN order_number TO "order"');
        $this->addSql('ALTER TABLE forum RENAME COLUMN order_number TO "order"');
    }
}
