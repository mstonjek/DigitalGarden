<?php

declare(strict_types=1);

namespace App\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Incremental change on top of Version20240820112557 (which must stay
 * in place so existing databases keep a valid migration history).
 *
 * 1. GitHub may return email: null for users with a private email
 *    (AuthService falls back to /user/emails, but NULL is still possible).
 * 2. Defensive data cleanup: web_portfolio is rendered as a link href,
 *    so rows predating the http(s) form validation must not contain
 *    javascript:/data: URIs. The cleanup is intentionally one-way;
 *    down() only reverts the schema, not the data.
 */
final class Version20260917000000 extends AbstractMigration
{
    #[\Override]
    public function getDescription(): string
    {
        return 'Nullable user email + sanitize web_portfolio URL schemes';
    }

    #[\Override]
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user MODIFY email VARCHAR(255) DEFAULT NULL');
        $this->addSql(
            "UPDATE flower SET web_portfolio = '' "
            . "WHERE web_portfolio NOT LIKE 'http://%' AND web_portfolio NOT LIKE 'https://%'"
        );
    }

    #[\Override]
    public function down(Schema $schema): void
    {
        // NOTE: rolling back requires all user rows to have a non-NULL email first.
        $this->addSql('ALTER TABLE user MODIFY email VARCHAR(255) NOT NULL');
    }
}
