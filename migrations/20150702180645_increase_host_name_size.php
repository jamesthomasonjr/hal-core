<?php

use Phinx\Migration\AbstractMigration;
use QL\Hal\Core\DatabaseMeta;

class IncreaseHostNameSize extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table(DatabaseMeta::DB_SERVER);
        $table
            ->changeColumn('ServerName', 'string', ['limit' => 60]);
        $table->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $table = $this->table(DatabaseMeta::DB_SERVER);
        $table
            ->changeColumn('ServerName', 'string', ['limit' => 24]);
        $table->save();
    }
}
