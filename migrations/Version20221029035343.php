<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221029035343 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE editor ADD created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE editor ADD CONSTRAINT FK_CCF1F1BAB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_CCF1F1BAB03A8386 ON editor (created_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE editor DROP FOREIGN KEY FK_CCF1F1BAB03A8386');
        $this->addSql('DROP INDEX IDX_CCF1F1BAB03A8386 ON editor');
        $this->addSql('ALTER TABLE editor DROP created_by_id');
    }
}
