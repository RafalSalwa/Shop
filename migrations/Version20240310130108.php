<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240310130108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_item ADD CONSTRAINT FK_1A78C8D78D9F6D38 FOREIGN KEY (order_id) REFERENCES interview.orders (order_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1A78C8D78D9F6D38 ON order_item (order_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA interview');
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE interview.order_item DROP CONSTRAINT FK_1A78C8D78D9F6D38');
        $this->addSql('DROP INDEX IDX_1A78C8D78D9F6D38');
    }
}
