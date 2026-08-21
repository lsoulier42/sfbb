<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260821160000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Rend la colonne chat.title nullable (elle n\'est pas utilisée par l\'UI des messages privés).';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE chat ALTER title DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE chat ALTER title SET NOT NULL');
    }
}
