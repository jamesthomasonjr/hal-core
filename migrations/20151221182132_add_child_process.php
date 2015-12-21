<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;
use QL\Hal\Core\DatabaseMeta;

class AddChildProcess extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table(DatabaseMeta::DB_PROCESS, [
            'id' => false,
            'primary_key' => 'ProcessId'
        ]);

        $table
            ->addColumn('ProcessId', 'char', ['limit' => 32])
            ->addColumn('ProcessCreated', 'datetime')
            ->addColumn('ProcessStatus', 'string', ['limit' => 20, 'default' => 'Pending'])
            ->addColumn('ProcessMessage', 'string', ['limit' => 200])
            ->addColumn('ProcessContext', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR, 'null' => false])

            ->addColumn('ProcessParentId', 'string', ['limit' => 40])
            ->addColumn('ProcessParentType', 'string', ['limit' => 40])
            ->addColumn('ProcessChildId', 'string', ['limit' => 40])
            ->addColumn('ProcessChildType', 'string', ['limit' => 40])

            ->addColumn('UserId', 'integer', ['null' => false])

            ->save();

        $table
            ->addIndex(['ProcessStatus'])
            ->addIndex(['ProcessParentId'])
            ->addIndex(['ProcessChildId'])
            ->addForeignKey('UserId', DatabaseMeta::DB_USER, 'UserId', [
                'delete' => 'CASCADE',
                'update'=> 'CASCADE'
            ])
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable(DatabaseMeta::DB_PROCESS);
    }
}
