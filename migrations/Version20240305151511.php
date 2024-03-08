<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240305151511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX idx_d01fb08041471bab');
        $this->addSql('ALTER TABLE cart_item ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE cart_item RENAME COLUMN reference_entity_id TO referenced_entity_id');
        $this->addSql('COMMENT ON COLUMN cart_item.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE INDEX IDX_D01FB08093EC2557 ON cart_item (referenced_entity_id)');
        $this->addSql('ALTER TABLE subscription ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN subscription.created_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA interview');
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE interview.subscription ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN interview.subscription.created_at IS NULL');
        $this->addSql('DROP INDEX IDX_D01FB08093EC2557');
        $this->addSql('ALTER TABLE interview.cart_item ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE interview.cart_item RENAME COLUMN referenced_entity_id TO reference_entity_id');
        $this->addSql('COMMENT ON COLUMN interview.cart_item.created_at IS NULL');
        $this->addSql('CREATE INDEX idx_d01fb08041471bab ON interview.cart_item (reference_entity_id)');
    }
}
