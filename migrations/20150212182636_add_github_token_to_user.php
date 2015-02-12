<?php

use Phinx\Migration\AbstractMigration;

class AddGithubTokenToUser extends AbstractMigration
{
    const TABLE_USERS = 'Users';

    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->table(self::TABLE_USERS)
            ->addColumn('UserGithubToken', 'string', ['limit' => 128, 'after' => 'UserIsActive'])
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table(self::TABLE_USERS)
            ->removeColumn('UserGithubToken')
            ->save();
    }
}
