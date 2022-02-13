<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Create "results" table
 */
final class Version20220212173131 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create "results" table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE results_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE results (id INT NOT NULL, batch TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, block INT NOT NULL, string VARCHAR(255) NOT NULL, key VARCHAR(255) NOT NULL, hash VARCHAR(255) NOT NULL, attempts INT NOT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE results_id_seq CASCADE');
        $this->addSql('DROP TABLE results');
    }
}
