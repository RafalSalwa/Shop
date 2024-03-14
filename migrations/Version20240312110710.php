<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240312110710 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders ADD coupon_type VARCHAR(25) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD coupon_discount VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE orders DROP updated_at');
        $this->addSql('ALTER TABLE orders ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN orders.created_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA interview');
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE interview.orders ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE interview.orders DROP coupon_type');
        $this->addSql('ALTER TABLE interview.orders DROP coupon_discount');
        $this->addSql('ALTER TABLE interview.orders ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN interview.orders.created_at IS NULL');
    }
}
