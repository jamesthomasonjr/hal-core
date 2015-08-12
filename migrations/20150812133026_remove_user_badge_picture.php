<?php

use QL\Hal\Core\DatabaseMeta;
use Phinx\Migration\AbstractMigration;

class RemoveUserBadgePicture extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table(DatabaseMeta::DB_USER);
        $table
            ->removeColumn('UserPictureUrl')
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $table = $this->table(DatabaseMeta::DB_USER);
        $table
            ->addColumn('UserPictureUrl', 'string', ['after' => 'UserEmail', 'limit' => 128])
            ->save();
    }
}
