<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;
use QL\Hal\Core\DatabaseMeta;

class AddLatestPushToDeployment extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table(DatabaseMeta::DB_DEPLOYMENT);

        $table
            ->addColumn('PushId', 'char', ['limit' => 40, 'null' => true])
            ->save();

        $table
            ->addForeignKey('PushId', DatabaseMeta::DB_PUSH, 'PushId', [
                'delete' => 'SET_NULL',
                'update'=> 'CASCADE'
            ])
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $table = $this->table(DatabaseMeta::DB_DEPLOYMENT);

        $table
            ->dropForeignKey('PushId')
            ->save();

        $table
            ->removeColumn('PushId')
            ->save();
    }
}
