<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240209192647 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reset_password_request DROP CONSTRAINT fk_7ce748aa76ed395');
        $this->addSql('ALTER TABLE oauth2_user_consent DROP CONSTRAINT fk_c8f05d01a76ed395');
        $this->addSql('ALTER TABLE cart DROP CONSTRAINT fk_24f4932ea76ed395');
        $this->addSql('DROP SEQUENCE intrv_user_user_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE user_subscription_subscription_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE voucher_voucher_id_seq CASCADE');
        $this->addSql('CREATE TABLE interview.address (address_id INT NOT NULL, user_id INT DEFAULT NULL, first_name VARCHAR(40) NOT NULL, last_name VARCHAR(40) NOT NULL, address_line_1 VARCHAR(40) NOT NULL, address_line_2 VARCHAR(40) DEFAULT NULL, city VARCHAR(40) NOT NULL, state VARCHAR(40) NOT NULL, postal_code VARCHAR(40) NOT NULL, PRIMARY KEY(address_id))');
        $this->addSql('CREATE INDEX IDX_A3B5E31CA76ED395 ON interview.address (user_id)');
        $this->addSql('CREATE TABLE interview.order_item (order_item_id INT NOT NULL, order_id INT DEFAULT NULL, cart_item_entity JSON NOT NULL, cart_item_type VARCHAR(25) NOT NULL, PRIMARY KEY(order_item_id))');
        $this->addSql('CREATE INDEX IDX_1A78C8D78D9F6D38 ON interview.order_item (order_id)');
        $this->addSql('CREATE TABLE interview.orders (order_id INT NOT NULL, user_id INT DEFAULT NULL, address_id INT DEFAULT NULL, status VARCHAR(25) NOT NULL, amount INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(order_id))');
        $this->addSql('CREATE INDEX IDX_CFC92A06A76ED395 ON interview.orders (user_id)');
        $this->addSql('CREATE INDEX IDX_CFC92A06F5B7AF75 ON interview.orders (address_id)');
        $this->addSql('CREATE TABLE interview.payment (payment_id INT NOT NULL, user_id INT DEFAULT NULL, order_id INT DEFAULT NULL, operation_number VARCHAR(40) NOT NULL, operation_type VARCHAR(40) NOT NULL, amount INT NOT NULL, status VARCHAR(25) NOT NULL, payment_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(payment_id))');
        $this->addSql('CREATE INDEX IDX_C3D30890A76ED395 ON interview.payment (user_id)');
        $this->addSql('CREATE INDEX IDX_C3D308908D9F6D38 ON interview.payment (order_id)');
        $this->addSql('CREATE TABLE "user" (user_id INT NOT NULL, subscription_id INT DEFAULT NULL, username VARCHAR(180) DEFAULT NULL, pass VARCHAR(100) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, phone_no VARCHAR(11) DEFAULT NULL, roles JSON DEFAULT NULL, verification_code VARCHAR(12) NOT NULL, is_verified BOOLEAN DEFAULT false NOT NULL, is_active BOOLEAN DEFAULT false NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(user_id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6499A1887DC ON "user" (subscription_id)');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE interview.address ADD CONSTRAINT FK_A3B5E31CA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE interview.order_item ADD CONSTRAINT FK_1A78C8D78D9F6D38 FOREIGN KEY (order_id) REFERENCES interview.orders (order_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE interview.orders ADD CONSTRAINT FK_CFC92A06A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE interview.orders ADD CONSTRAINT FK_CFC92A06F5B7AF75 FOREIGN KEY (address_id) REFERENCES interview.address (address_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE interview.payment ADD CONSTRAINT FK_C3D30890A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE interview.payment ADD CONSTRAINT FK_C3D308908D9F6D38 FOREIGN KEY (order_id) REFERENCES interview.orders (order_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D6499A1887DC FOREIGN KEY (subscription_id) REFERENCES interview.subscription (subscription_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.address DROP CONSTRAINT fk_d4e6f81a76ed395');
        $this->addSql('ALTER TABLE public.oauth2_user_consent DROP CONSTRAINT fk_c8f05d0119eb6921');
        $this->addSql('ALTER TABLE public.oauth2_user_consent DROP CONSTRAINT fk_c8f05d01a76ed395');
        $this->addSql('ALTER TABLE public.payment DROP CONSTRAINT fk_6d28840d8d9f6d38');
        $this->addSql('ALTER TABLE public.payment DROP CONSTRAINT payment_user_id_fk');
        $this->addSql('ALTER TABLE public.user_subscription DROP CONSTRAINT subscription_plan_id_fk');
        $this->addSql('ALTER TABLE public.user_subscription DROP CONSTRAINT subscription_user_id_fk');
        $this->addSql('ALTER TABLE public.cart_item DROP CONSTRAINT fk_f0fe25271ad5cdbf');
        $this->addSql('ALTER TABLE public.products DROP CONSTRAINT fk_b3ba5a5a12469de2');
        $this->addSql('ALTER TABLE public.products DROP CONSTRAINT fk_b3ba5a5acb1d096a');
        $this->addSql('ALTER TABLE public.subscription_plan_cart_item DROP CONSTRAINT fk_6a21f6f6d0a5cda7');
        $this->addSql('ALTER TABLE public.subscription_plan_cart_item DROP CONSTRAINT fk_6a21f6f6e9b59a59');
        $this->addSql('ALTER TABLE public.oauth2_refresh_token DROP CONSTRAINT fk_4dd90732b6a2dd68');
        $this->addSql('ALTER TABLE public.subscription DROP CONSTRAINT fk_a3c664d39b8ce200');
        $this->addSql('ALTER TABLE public.intrv_user DROP CONSTRAINT fk_c2dbfc019a1887dc');
        $this->addSql('ALTER TABLE public.orders DROP CONSTRAINT fk_e52ffdeef5b7af75');
        $this->addSql('ALTER TABLE public.orders DROP CONSTRAINT fk_f5299398a76ed395');
        $this->addSql('ALTER TABLE public.order_item DROP CONSTRAINT fk_52ea1f098d9f6d38');
        $this->addSql('ALTER TABLE public.product_cart_item DROP CONSTRAINT fk_9e5e93aad0a5cda7');
        $this->addSql('ALTER TABLE public.product_cart_item DROP CONSTRAINT fk_9e5e93aae9b59a59');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT fk_8a492bdf9a1887dc');
        $this->addSql('ALTER TABLE public.cart DROP CONSTRAINT fk_ba388b7a76ed395');
        $this->addSql('ALTER TABLE public.oauth2_authorization_code DROP CONSTRAINT fk_509fef5fc7440455');
        $this->addSql('ALTER TABLE public.subscription_cart_item DROP CONSTRAINT fk_c241e79b9a1887dc');
        $this->addSql('ALTER TABLE public.subscription_cart_item DROP CONSTRAINT fk_c241e79be9b59a59');
        $this->addSql('ALTER TABLE public.oauth2_client_profile DROP CONSTRAINT fk_9b524e1f19eb6921');
        $this->addSql('ALTER TABLE public.oauth2_access_token DROP CONSTRAINT fk_454d9673c7440455');
        $this->addSql('DROP TABLE public.categories');
        $this->addSql('DROP TABLE public.address');
        $this->addSql('DROP TABLE public.oauth2_user_consent');
        $this->addSql('DROP TABLE public.plan');
        $this->addSql('DROP TABLE public.payment');
        $this->addSql('DROP TABLE public.user_subscription');
        $this->addSql('DROP TABLE public.cart_item');
        $this->addSql('DROP TABLE public.products');
        $this->addSql('DROP TABLE public.oauth2_client');
        $this->addSql('DROP TABLE public.order_pending');
        $this->addSql('DROP TABLE public.voucher');
        $this->addSql('DROP TABLE public.subscription_plan_cart_item');
        $this->addSql('DROP TABLE public.suppliers');
        $this->addSql('DROP TABLE public.oauth2_refresh_token');
        $this->addSql('DROP TABLE public.subscription');
        $this->addSql('DROP TABLE public.intrv_user');
        $this->addSql('DROP TABLE public.orders');
        $this->addSql('DROP TABLE public.order_item');
        $this->addSql('DROP TABLE public.product_cart_item');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE public.cart');
        $this->addSql('DROP TABLE public.oauth2_authorization_code');
        $this->addSql('DROP TABLE public.subscription_cart_item');
        $this->addSql('DROP TABLE public.oauth2_client_profile');
        $this->addSql('DROP TABLE public.oauth2_access_token');
        $this->addSql('DROP INDEX idx_24f4932ea76ed395');
        $this->addSql('ALTER TABLE cart ALTER user_id SET NOT NULL');
        $this->addSql('ALTER TABLE oauth2_user_consent DROP CONSTRAINT FK_C8F05D01A76ED395');
        $this->addSql('ALTER TABLE oauth2_user_consent ADD CONSTRAINT FK_C8F05D01A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reset_password_request DROP CONSTRAINT FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE subscription ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE subscription ALTER tier TYPE INT');
        $this->addSql('ALTER TABLE subscription ALTER tier SET NOT NULL');
        $this->addSql('ALTER TABLE subscription ALTER is_active SET DEFAULT true');
        $this->addSql('ALTER TABLE subscription ALTER starts_at SET DEFAULT CURRENT_TIMESTAMP');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA interview');
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE interview.address DROP CONSTRAINT FK_A3B5E31CA76ED395');
        $this->addSql('ALTER TABLE oauth2_user_consent DROP CONSTRAINT FK_C8F05D01A76ED395');
        $this->addSql('ALTER TABLE interview.orders DROP CONSTRAINT FK_CFC92A06A76ED395');
        $this->addSql('ALTER TABLE interview.payment DROP CONSTRAINT FK_C3D30890A76ED395');
        $this->addSql('ALTER TABLE reset_password_request DROP CONSTRAINT FK_7CE748AA76ED395');
        $this->addSql('CREATE SEQUENCE intrv_user_user_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE user_subscription_subscription_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE voucher_voucher_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE public.categories (category_id SMALLINT NOT NULL, category_name VARCHAR(15) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(category_id))');
        $this->addSql('CREATE TABLE public.address (address_id INT NOT NULL, user_id INT DEFAULT NULL, first_name VARCHAR(40) NOT NULL, last_name VARCHAR(40) NOT NULL, address_line_1 VARCHAR(40) NOT NULL, address_line_2 VARCHAR(40) DEFAULT NULL, city VARCHAR(40) NOT NULL, state VARCHAR(40) NOT NULL, postal_code VARCHAR(40) NOT NULL, PRIMARY KEY(address_id))');
        $this->addSql('CREATE INDEX idx_d4e6f81a76ed395 ON public.address (user_id)');
        $this->addSql('CREATE TABLE public.oauth2_user_consent (id INT NOT NULL, client_id VARCHAR(32) NOT NULL, user_id INT NOT NULL, created TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, expires TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, scopes TEXT DEFAULT NULL, ip_address VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_c8f05d01a76ed395 ON public.oauth2_user_consent (user_id)');
        $this->addSql('CREATE INDEX idx_c8f05d0119eb6921 ON public.oauth2_user_consent (client_id)');
        $this->addSql('COMMENT ON COLUMN public.oauth2_user_consent.created IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN public.oauth2_user_consent.expires IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN public.oauth2_user_consent.scopes IS \'(DC2Type:simple_array)\'');
        $this->addSql('CREATE TABLE public.plan (plan_id INT NOT NULL, plan_name VARCHAR(255) NOT NULL, description TEXT NOT NULL, is_active BOOLEAN DEFAULT false NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, unit_price SMALLINT NOT NULL, is_visible BOOLEAN DEFAULT false NOT NULL, tier SMALLINT NOT NULL, PRIMARY KEY(plan_id))');
        $this->addSql('CREATE INDEX u_plan_idx ON public.plan (plan_name)');
        $this->addSql('CREATE TABLE public.payment (payment_id INT NOT NULL, user_id INT DEFAULT NULL, order_id INT DEFAULT NULL, operation_number VARCHAR(40) NOT NULL, operation_type VARCHAR(40) NOT NULL, amount INT NOT NULL, payment_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, status VARCHAR(25) NOT NULL, PRIMARY KEY(payment_id))');
        $this->addSql('CREATE INDEX idx_6d28840d8d9f6d38 ON public.payment (order_id)');
        $this->addSql('CREATE INDEX IDX_560C4AB1A76ED395 ON public.payment (user_id)');
        $this->addSql('CREATE TABLE public.user_subscription (subscription_id SERIAL NOT NULL, user_id INT NOT NULL, plan_id INT NOT NULL, purchased_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP, started_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, ends_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(subscription_id))');
        $this->addSql('CREATE INDEX subscriptions_user_idx ON public.user_subscription (user_id)');
        $this->addSql('CREATE INDEX plan_created_at_index ON public.user_subscription (purchased_at)');
        $this->addSql('CREATE UNIQUE INDEX user_subscription_user_id_key ON public.user_subscription (user_id)');
        $this->addSql('CREATE INDEX IDX_109D36B8E899029B ON public.user_subscription (plan_id)');
        $this->addSql('CREATE TABLE public.cart_item (cart_item_id INT NOT NULL, cart_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, item_type VARCHAR(30) NOT NULL, quantity INT DEFAULT 1 NOT NULL, PRIMARY KEY(cart_item_id))');
        $this->addSql('CREATE INDEX idx_f0fe25271ad5cdbf ON public.cart_item (cart_id)');
        $this->addSql('CREATE TABLE public.products (product_id INT NOT NULL, category_id SMALLINT DEFAULT NULL, required_subscription_id INT DEFAULT NULL, product_name VARCHAR(40) NOT NULL, supplier_id SMALLINT DEFAULT NULL, quantity_per_unit VARCHAR(20) DEFAULT NULL, unit_price SMALLINT NOT NULL, units_in_stock SMALLINT DEFAULT NULL, units_on_order SMALLINT DEFAULT NULL, PRIMARY KEY(product_id))');
        $this->addSql('CREATE INDEX idx_b3ba5a5acb1d096a ON public.products (required_subscription_id)');
        $this->addSql('CREATE INDEX idx_b3ba5a5a12469de2 ON public.products (category_id)');
        $this->addSql('CREATE TABLE public.oauth2_client (identifier VARCHAR(32) NOT NULL, name VARCHAR(128) NOT NULL, secret VARCHAR(128) DEFAULT NULL, redirect_uris TEXT DEFAULT NULL, grants TEXT DEFAULT NULL, scopes TEXT DEFAULT NULL, active BOOLEAN NOT NULL, allow_plain_text_pkce BOOLEAN DEFAULT false NOT NULL, PRIMARY KEY(identifier))');
        $this->addSql('COMMENT ON COLUMN public.oauth2_client.redirect_uris IS \'(DC2Type:oauth2_redirect_uri)\'');
        $this->addSql('COMMENT ON COLUMN public.oauth2_client.grants IS \'(DC2Type:oauth2_grant)\'');
        $this->addSql('COMMENT ON COLUMN public.oauth2_client.scopes IS \'(DC2Type:oauth2_scope)\'');
        $this->addSql('CREATE TABLE public.order_pending (order_id INT NOT NULL, user_id INT NOT NULL, cart_id INT NOT NULL, PRIMARY KEY(order_id))');
        $this->addSql('CREATE TABLE public.voucher (voucher_id SERIAL NOT NULL, code VARCHAR(25) NOT NULL, valid_for VARCHAR(12) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(voucher_id))');
        $this->addSql('CREATE TABLE public.subscription_plan_cart_item (cart_item_id INT NOT NULL, destination_entity_id INT DEFAULT NULL, PRIMARY KEY(cart_item_id))');
        $this->addSql('CREATE INDEX idx_6a21f6f6d0a5cda7 ON public.subscription_plan_cart_item (destination_entity_id)');
        $this->addSql('CREATE TABLE public.suppliers (supplier_id SMALLINT NOT NULL, company_name VARCHAR(40) NOT NULL, PRIMARY KEY(supplier_id))');
        $this->addSql('CREATE TABLE public.oauth2_refresh_token (identifier CHAR(80) NOT NULL, access_token CHAR(80) DEFAULT NULL, expiry TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, revoked BOOLEAN NOT NULL, PRIMARY KEY(identifier))');
        $this->addSql('CREATE INDEX idx_4dd90732b6a2dd68 ON public.oauth2_refresh_token (access_token)');
        $this->addSql('COMMENT ON COLUMN public.oauth2_refresh_token.expiry IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE public.subscription (subscription_id INT NOT NULL, subscription_plan_id INT DEFAULT NULL, is_active BOOLEAN DEFAULT false NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, starts_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, ends_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, tier SMALLINT DEFAULT NULL, PRIMARY KEY(subscription_id))');
        $this->addSql('CREATE INDEX idx_a3c664d39b8ce200 ON public.subscription (subscription_plan_id)');
        $this->addSql('CREATE TABLE public.intrv_user (user_id INT NOT NULL, subscription_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, pass VARCHAR(100) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, phone_no VARCHAR(11) DEFAULT NULL, roles JSON DEFAULT NULL, verification_code VARCHAR(12) NOT NULL, is_verified BOOLEAN DEFAULT false NOT NULL, is_active BOOLEAN DEFAULT false NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(user_id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_c2dbfc019a1887dc ON public.intrv_user (subscription_id)');
        $this->addSql('CREATE TABLE public.orders (order_id INT NOT NULL, user_id INT DEFAULT NULL, address_id INT DEFAULT NULL, status VARCHAR(25) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, amount INT NOT NULL, PRIMARY KEY(order_id))');
        $this->addSql('CREATE INDEX idx_e52ffdeef5b7af75 ON public.orders (address_id)');
        $this->addSql('CREATE INDEX idx_e52ffdeea76ed395 ON public.orders (user_id)');
        $this->addSql('CREATE TABLE public.order_item (order_item_id INT NOT NULL, order_id INT DEFAULT NULL, cart_item_entity JSON NOT NULL, cart_item_type VARCHAR(25) NOT NULL, quantity INT DEFAULT 1 NOT NULL, PRIMARY KEY(order_item_id))');
        $this->addSql('CREATE INDEX idx_52ea1f098d9f6d38 ON public.order_item (order_id)');
        $this->addSql('CREATE TABLE public.product_cart_item (cart_item_id INT NOT NULL, destination_entity_id INT DEFAULT NULL, PRIMARY KEY(cart_item_id))');
        $this->addSql('CREATE INDEX idx_9e5e93aad0a5cda7 ON public.product_cart_item (destination_entity_id)');
        $this->addSql('CREATE TABLE users (user_id INT NOT NULL, subscription_id INT DEFAULT NULL, username VARCHAR(180) DEFAULT NULL, pass VARCHAR(100) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, phone_no VARCHAR(11) DEFAULT NULL, roles JSON DEFAULT NULL, verification_code VARCHAR(12) NOT NULL, is_verified BOOLEAN DEFAULT false NOT NULL, is_active BOOLEAN DEFAULT false NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, last_login TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(user_id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_9d7fd9129a1887dc ON users (subscription_id)');
        $this->addSql('COMMENT ON COLUMN users.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN users.deleted_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE public.cart (cart_id INT NOT NULL, user_id INT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, status VARCHAR(25) NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(cart_id))');
        $this->addSql('CREATE INDEX idx_ba388b7a76ed395 ON public.cart (user_id)');
        $this->addSql('COMMENT ON COLUMN public.cart.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE public.oauth2_authorization_code (identifier CHAR(80) NOT NULL, client VARCHAR(32) NOT NULL, expiry TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, user_identifier VARCHAR(128) DEFAULT NULL, scopes TEXT DEFAULT NULL, revoked BOOLEAN NOT NULL, PRIMARY KEY(identifier))');
        $this->addSql('CREATE INDEX idx_509fef5fc7440455 ON public.oauth2_authorization_code (client)');
        $this->addSql('COMMENT ON COLUMN public.oauth2_authorization_code.expiry IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN public.oauth2_authorization_code.scopes IS \'(DC2Type:oauth2_scope)\'');
        $this->addSql('CREATE TABLE public.subscription_cart_item (cart_item_id INT NOT NULL, subscription_id INT DEFAULT NULL, PRIMARY KEY(cart_item_id))');
        $this->addSql('CREATE INDEX idx_c241e79b9a1887dc ON public.subscription_cart_item (subscription_id)');
        $this->addSql('CREATE TABLE public.oauth2_client_profile (id INT NOT NULL, client_id VARCHAR(32) NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX uniq_9b524e1f19eb6921 ON public.oauth2_client_profile (client_id)');
        $this->addSql('CREATE TABLE public.oauth2_access_token (identifier CHAR(80) NOT NULL, client VARCHAR(32) NOT NULL, expiry TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, user_identifier VARCHAR(128) DEFAULT NULL, scopes TEXT DEFAULT NULL, revoked BOOLEAN NOT NULL, PRIMARY KEY(identifier))');
        $this->addSql('CREATE INDEX idx_454d9673c7440455 ON public.oauth2_access_token (client)');
        $this->addSql('COMMENT ON COLUMN public.oauth2_access_token.expiry IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN public.oauth2_access_token.scopes IS \'(DC2Type:oauth2_scope)\'');
        $this->addSql('ALTER TABLE public.address ADD CONSTRAINT fk_d4e6f81a76ed395 FOREIGN KEY (user_id) REFERENCES intrv_user (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.oauth2_user_consent ADD CONSTRAINT fk_c8f05d0119eb6921 FOREIGN KEY (client_id) REFERENCES public.oauth2_client (identifier) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.oauth2_user_consent ADD CONSTRAINT fk_c8f05d01a76ed395 FOREIGN KEY (user_id) REFERENCES intrv_user (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.payment ADD CONSTRAINT fk_6d28840d8d9f6d38 FOREIGN KEY (order_id) REFERENCES orders (order_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.payment ADD CONSTRAINT payment_user_id_fk FOREIGN KEY (user_id) REFERENCES intrv_user (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.user_subscription ADD CONSTRAINT subscription_plan_id_fk FOREIGN KEY (plan_id) REFERENCES public.plan (plan_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.user_subscription ADD CONSTRAINT subscription_user_id_fk FOREIGN KEY (user_id) REFERENCES intrv_user (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.cart_item ADD CONSTRAINT fk_f0fe25271ad5cdbf FOREIGN KEY (cart_id) REFERENCES public.cart (cart_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.products ADD CONSTRAINT fk_b3ba5a5a12469de2 FOREIGN KEY (category_id) REFERENCES public.categories (category_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.products ADD CONSTRAINT fk_b3ba5a5acb1d096a FOREIGN KEY (required_subscription_id) REFERENCES public.plan (plan_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.subscription_plan_cart_item ADD CONSTRAINT fk_6a21f6f6d0a5cda7 FOREIGN KEY (destination_entity_id) REFERENCES public.plan (plan_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.subscription_plan_cart_item ADD CONSTRAINT fk_6a21f6f6e9b59a59 FOREIGN KEY (cart_item_id) REFERENCES public.cart_item (cart_item_id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.oauth2_refresh_token ADD CONSTRAINT fk_4dd90732b6a2dd68 FOREIGN KEY (access_token) REFERENCES public.oauth2_access_token (identifier) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.subscription ADD CONSTRAINT fk_a3c664d39b8ce200 FOREIGN KEY (subscription_plan_id) REFERENCES public.plan (plan_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.intrv_user ADD CONSTRAINT fk_c2dbfc019a1887dc FOREIGN KEY (subscription_id) REFERENCES public.subscription (subscription_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.orders ADD CONSTRAINT fk_e52ffdeef5b7af75 FOREIGN KEY (address_id) REFERENCES address (address_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.orders ADD CONSTRAINT fk_f5299398a76ed395 FOREIGN KEY (user_id) REFERENCES intrv_user (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.order_item ADD CONSTRAINT fk_52ea1f098d9f6d38 FOREIGN KEY (order_id) REFERENCES orders (order_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.product_cart_item ADD CONSTRAINT fk_9e5e93aad0a5cda7 FOREIGN KEY (destination_entity_id) REFERENCES public.products (product_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.product_cart_item ADD CONSTRAINT fk_9e5e93aae9b59a59 FOREIGN KEY (cart_item_id) REFERENCES public.cart_item (cart_item_id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT fk_8a492bdf9a1887dc FOREIGN KEY (subscription_id) REFERENCES subscription (subscription_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.cart ADD CONSTRAINT fk_ba388b7a76ed395 FOREIGN KEY (user_id) REFERENCES intrv_user (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.oauth2_authorization_code ADD CONSTRAINT fk_509fef5fc7440455 FOREIGN KEY (client) REFERENCES public.oauth2_client (identifier) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.subscription_cart_item ADD CONSTRAINT fk_c241e79b9a1887dc FOREIGN KEY (subscription_id) REFERENCES public.plan (plan_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.subscription_cart_item ADD CONSTRAINT fk_c241e79be9b59a59 FOREIGN KEY (cart_item_id) REFERENCES public.cart_item (cart_item_id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.oauth2_client_profile ADD CONSTRAINT fk_9b524e1f19eb6921 FOREIGN KEY (client_id) REFERENCES public.oauth2_client (identifier) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE public.oauth2_access_token ADD CONSTRAINT fk_454d9673c7440455 FOREIGN KEY (client) REFERENCES public.oauth2_client (identifier) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE interview.address DROP CONSTRAINT FK_A3B5E31CA76ED395');
        $this->addSql('ALTER TABLE interview.order_item DROP CONSTRAINT FK_1A78C8D78D9F6D38');
        $this->addSql('ALTER TABLE interview.orders DROP CONSTRAINT FK_CFC92A06A76ED395');
        $this->addSql('ALTER TABLE interview.orders DROP CONSTRAINT FK_CFC92A06F5B7AF75');
        $this->addSql('ALTER TABLE interview.payment DROP CONSTRAINT FK_C3D30890A76ED395');
        $this->addSql('ALTER TABLE interview.payment DROP CONSTRAINT FK_C3D308908D9F6D38');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D6499A1887DC');
        $this->addSql('DROP TABLE interview.address');
        $this->addSql('DROP TABLE interview.order_item');
        $this->addSql('DROP TABLE interview.orders');
        $this->addSql('DROP TABLE interview.payment');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('ALTER TABLE reset_password_request DROP CONSTRAINT fk_7ce748aa76ed395');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT fk_7ce748aa76ed395 FOREIGN KEY (user_id) REFERENCES users (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE interview.subscription DROP user_id');
        $this->addSql('ALTER TABLE interview.subscription ALTER tier TYPE SMALLINT');
        $this->addSql('ALTER TABLE interview.subscription ALTER tier DROP NOT NULL');
        $this->addSql('ALTER TABLE interview.subscription ALTER is_active SET DEFAULT false');
        $this->addSql('ALTER TABLE interview.subscription ALTER starts_at DROP DEFAULT');
        $this->addSql('ALTER TABLE oauth2_user_consent DROP CONSTRAINT fk_c8f05d01a76ed395');
        $this->addSql('ALTER TABLE oauth2_user_consent ADD CONSTRAINT fk_c8f05d01a76ed395 FOREIGN KEY (user_id) REFERENCES users (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE interview.cart ALTER user_id DROP NOT NULL');
        $this->addSql('ALTER TABLE interview.cart ADD CONSTRAINT fk_24f4932ea76ed395 FOREIGN KEY (user_id) REFERENCES users (user_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_24f4932ea76ed395 ON interview.cart (user_id)');
    }
}
