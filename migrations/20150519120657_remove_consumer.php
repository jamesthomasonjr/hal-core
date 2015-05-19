<?php

use Phinx\Migration\AbstractMigration;
use QL\Hal\Core\DatabaseMeta;

class RemoveConsumer extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->table(DatabaseMeta::DB_BUILD)
            ->dropForeignKey('ConsumerId')
            ->removeColumn('ConsumerId')
            ->save();
        $this->table(DatabaseMeta::DB_PUSH)
            ->dropForeignKey('ConsumerId')
            ->removeColumn('ConsumerId')
            ->save();

        $this->table(DatabaseMeta::DB_TOKEN)
            ->dropForeignKey('ConsumerId')
            ->removeColumn('ConsumerId')
            ->save();

        $this->dropTable('Consumers');
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}
