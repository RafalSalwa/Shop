<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240310132102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE orders ADD delivery_address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD biling_address_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_CFC92A06EBF23851 FOREIGN KEY (delivery_address_id) REFERENCES interview.address (address_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE orders ADD CONSTRAINT FK_CFC92A06FAE894CB FOREIGN KEY (biling_address_id) REFERENCES interview.address (address_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CFC92A06EBF23851 ON orders (delivery_address_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CFC92A06FAE894CB ON orders (biling_address_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA interview');
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE interview.orders DROP CONSTRAINT FK_CFC92A06EBF23851');
        $this->addSql('ALTER TABLE interview.orders DROP CONSTRAINT FK_CFC92A06FAE894CB');
        $this->addSql('DROP INDEX UNIQ_CFC92A06EBF23851');
        $this->addSql('DROP INDEX UNIQ_CFC92A06FAE894CB');
        $this->addSql('ALTER TABLE interview.orders DROP delivery_address_id');
        $this->addSql('ALTER TABLE interview.orders DROP biling_address_id');
    }
}
