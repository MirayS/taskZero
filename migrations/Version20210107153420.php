<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210107153420 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblProductData ADD price NUMERIC(8, 2) DEFAULT NULL, ADD stock INT DEFAULT NULL, CHANGE dtmAdded dtmAdded DATETIME NOT NULL, CHANGE stmTimestamp stmTimestamp DATETIME NOT NULL');
        $this->addSql('DROP INDEX strproductcode ON tblProductData');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2C11248662F10A58 ON tblProductData (strProductCode)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tblProductData DROP price, DROP stock, CHANGE dtmAdded dtmAdded DATETIME DEFAULT NULL, CHANGE stmTimestamp stmTimestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('DROP INDEX uniq_2c11248662f10a58 ON tblProductData');
        $this->addSql('CREATE UNIQUE INDEX strProductCode ON tblProductData (strProductCode)');
    }
}
