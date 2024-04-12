<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240411145512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE address ALTER phone_no SET NOT NULL');
        $this->addSql('ALTER TABLE cart ALTER coupon_discount TYPE INT');
        $this->addSql('ALTER TABLE cart_item ALTER cart_id SET NOT NULL');
        $this->addSql('ALTER TABLE cart_item ALTER referenced_entity_id SET NOT NULL');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_D01FB08093EC2557 FOREIGN KEY (referenced_entity_id) REFERENCES interview.products (product_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE categories ALTER slug SET NOT NULL');
        $this->addSql('ALTER TABLE oauth2_user_consent ALTER expires SET NOT NULL');
        $this->addSql('ALTER TABLE oauth2_user_consent ALTER scopes SET NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER delivery_address_id SET NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER biling_address_id SET NOT NULL');
        $this->addSql('ALTER TABLE orders ALTER coupon_discount TYPE INT');
        $this->addSql('ALTER TABLE payment ALTER payment_date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE payment ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN payment.payment_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN payment.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE plan DROP updated_at');
        $this->addSql('ALTER TABLE plan DROP deleted_at');
        $this->addSql('ALTER TABLE plan ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN plan.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE products DROP category_id');
        $this->addSql('ALTER TABLE products ALTER quantity_per_unit SET NOT NULL');
        $this->addSql('ALTER TABLE products ALTER units_in_stock TYPE INT');
        $this->addSql('ALTER TABLE products ALTER units_in_stock SET NOT NULL');
        $this->addSql('ALTER TABLE products ALTER units_on_order SET NOT NULL');
        $this->addSql('ALTER TABLE subscription ALTER subscription_plan_id SET NOT NULL');
        $this->addSql('ALTER TABLE subscription ALTER user_id SET NOT NULL');
        $this->addSql('ALTER TABLE subscription ALTER starts_at SET NOT NULL');
        $this->addSql('ALTER TABLE subscription ALTER ends_at SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA interview');
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE interview.cart_item DROP CONSTRAINT FK_D01FB08093EC2557');
        $this->addSql('ALTER TABLE interview.cart_item ALTER cart_id DROP NOT NULL');
        $this->addSql('ALTER TABLE interview.cart_item ALTER referenced_entity_id DROP NOT NULL');
        $this->addSql('ALTER TABLE interview.subscription ALTER subscription_plan_id DROP NOT NULL');
        $this->addSql('ALTER TABLE interview.subscription ALTER user_id DROP NOT NULL');
        $this->addSql('ALTER TABLE interview.subscription ALTER starts_at DROP NOT NULL');
        $this->addSql('ALTER TABLE interview.subscription ALTER ends_at DROP NOT NULL');
        $this->addSql('ALTER TABLE interview.cart ALTER coupon_discount TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE interview.orders ALTER delivery_address_id DROP NOT NULL');
        $this->addSql('ALTER TABLE interview.orders ALTER biling_address_id DROP NOT NULL');
        $this->addSql('ALTER TABLE interview.orders ALTER coupon_discount TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE interview.products ADD category_id SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE interview.products ALTER quantity_per_unit DROP NOT NULL');
        $this->addSql('ALTER TABLE interview.products ALTER units_in_stock TYPE SMALLINT');
        $this->addSql('ALTER TABLE interview.products ALTER units_in_stock DROP NOT NULL');
        $this->addSql('ALTER TABLE interview.products ALTER units_on_order DROP NOT NULL');
        $this->addSql('ALTER TABLE interview.address ALTER phone_no DROP NOT NULL');
        $this->addSql('ALTER TABLE oauth2_user_consent ALTER expires DROP NOT NULL');
        $this->addSql('ALTER TABLE oauth2_user_consent ALTER scopes DROP NOT NULL');
        $this->addSql('ALTER TABLE interview.plan ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE interview.plan ADD deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE interview.plan ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN interview.plan.created_at IS NULL');
        $this->addSql('ALTER TABLE interview.payment ALTER payment_date TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE interview.payment ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('COMMENT ON COLUMN interview.payment.payment_date IS NULL');
        $this->addSql('COMMENT ON COLUMN interview.payment.created_at IS NULL');
        $this->addSql('ALTER TABLE interview.categories ALTER slug DROP NOT NULL');
    }
}
