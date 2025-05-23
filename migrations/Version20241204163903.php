<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241204163903 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bucket_list ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE bucket_list ADD CONSTRAINT FK_DC282CE12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_DC282CE12469DE2 ON bucket_list (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bucket_list DROP FOREIGN KEY FK_DC282CE12469DE2');
        $this->addSql('DROP INDEX IDX_DC282CE12469DE2 ON bucket_list');
        $this->addSql('ALTER TABLE bucket_list DROP category_id');
    }
}
