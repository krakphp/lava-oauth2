<?php

namespace Krak\LavaOAuth2\DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170504011955 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE oauth2_access_tokens (id VARCHAR(255) NOT NULL, client_id VARCHAR(255) DEFAULT NULL, expiry_date_time DATETIME NOT NULL, user_id VARCHAR(255) DEFAULT NULL, is_revoked TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_D247A21B19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oauth2_access_tokens_scopes (access_token_id VARCHAR(255) NOT NULL, scope_id VARCHAR(255) NOT NULL, INDEX IDX_AA6489292CCB2688 (access_token_id), INDEX IDX_AA648929682B5931 (scope_id), PRIMARY KEY(access_token_id, scope_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oauth2_clients (id VARCHAR(255) NOT NULL, secret VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, redirect_uri VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oauth2_clients_scopes (client_id VARCHAR(255) NOT NULL, scope_id VARCHAR(255) NOT NULL, INDEX IDX_493B97BA19EB6921 (client_id), INDEX IDX_493B97BA682B5931 (scope_id), PRIMARY KEY(client_id, scope_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oauth2_refresh_tokens (id VARCHAR(255) NOT NULL, access_token_id VARCHAR(255) DEFAULT NULL, expiry_date_time DATETIME NOT NULL, is_revoked TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_D394478C2CCB2688 (access_token_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oauth2_scopes (id VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE oauth2_access_tokens ADD CONSTRAINT FK_D247A21B19EB6921 FOREIGN KEY (client_id) REFERENCES oauth2_clients (id)');
        $this->addSql('ALTER TABLE oauth2_access_tokens_scopes ADD CONSTRAINT FK_AA6489292CCB2688 FOREIGN KEY (access_token_id) REFERENCES oauth2_access_tokens (id)');
        $this->addSql('ALTER TABLE oauth2_access_tokens_scopes ADD CONSTRAINT FK_AA648929682B5931 FOREIGN KEY (scope_id) REFERENCES oauth2_scopes (id)');
        $this->addSql('ALTER TABLE oauth2_clients_scopes ADD CONSTRAINT FK_493B97BA19EB6921 FOREIGN KEY (client_id) REFERENCES oauth2_clients (id)');
        $this->addSql('ALTER TABLE oauth2_clients_scopes ADD CONSTRAINT FK_493B97BA682B5931 FOREIGN KEY (scope_id) REFERENCES oauth2_scopes (id)');
        $this->addSql('ALTER TABLE oauth2_refresh_tokens ADD CONSTRAINT FK_D394478C2CCB2688 FOREIGN KEY (access_token_id) REFERENCES oauth2_access_tokens (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE oauth2_access_tokens_scopes DROP FOREIGN KEY FK_AA6489292CCB2688');
        $this->addSql('ALTER TABLE oauth2_refresh_tokens DROP FOREIGN KEY FK_D394478C2CCB2688');
        $this->addSql('ALTER TABLE oauth2_access_tokens DROP FOREIGN KEY FK_D247A21B19EB6921');
        $this->addSql('ALTER TABLE oauth2_clients_scopes DROP FOREIGN KEY FK_493B97BA19EB6921');
        $this->addSql('ALTER TABLE oauth2_access_tokens_scopes DROP FOREIGN KEY FK_AA648929682B5931');
        $this->addSql('ALTER TABLE oauth2_clients_scopes DROP FOREIGN KEY FK_493B97BA682B5931');
        $this->addSql('DROP TABLE oauth2_access_tokens');
        $this->addSql('DROP TABLE oauth2_access_tokens_scopes');
        $this->addSql('DROP TABLE oauth2_clients');
        $this->addSql('DROP TABLE oauth2_clients_scopes');
        $this->addSql('DROP TABLE oauth2_refresh_tokens');
        $this->addSql('DROP TABLE oauth2_scopes');
    }
}
