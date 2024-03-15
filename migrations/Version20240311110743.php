<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240311110743 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_cfc92a06fae894cb');
        $this->addSql('DROP INDEX uniq_cfc92a06ebf23851');
        $this->addSql('CREATE INDEX IDX_CFC92A06EBF23851 ON orders (delivery_address_id)');
        $this->addSql('CREATE INDEX IDX_CFC92A06FAE894CB ON orders (biling_address_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA interview');
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX IDX_CFC92A06EBF23851');
        $this->addSql('DROP INDEX IDX_CFC92A06FAE894CB');
        $this->addSql('CREATE UNIQUE INDEX uniq_cfc92a06fae894cb ON interview.orders (biling_address_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_cfc92a06ebf23851 ON interview.orders (delivery_address_id)');
    }
}
