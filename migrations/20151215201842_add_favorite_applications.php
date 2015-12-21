<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;
use QL\Hal\Core\DatabaseMeta;

class AddFavoriteApplications extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table(DatabaseMeta::DB_USER_SETTING, [
            'id' => false,
            'primary_key' => 'UserSettingsId'
        ]);

        $table
            ->addColumn('UserSettingsId', 'char', ['limit' => 32])
            ->addColumn('UserSettingsFavoriteApplications', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR, 'null' => false])

            ->addColumn('UserId', 'integer', ['null' => false])

            ->save();

        $table
            ->addIndex(['UserId'], ['unique' => true])
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
        $this->dropTable(DatabaseMeta::DB_USER_SETTING);
    }
}
