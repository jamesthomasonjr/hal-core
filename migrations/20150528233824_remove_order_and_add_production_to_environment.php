<?php

use Phinx\Migration\AbstractMigration;
use QL\Hal\Core\DatabaseMeta;

class RemoveOrderAndAddProductionToEnvironment extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->table(DatabaseMeta::DB_ENVIRONMENT)
            ->removeColumn('EnvironmentOrder')
            ->addColumn('EnvironmentIsProduction', 'boolean', ['default' => false])
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table(DatabaseMeta::DB_ENVIRONMENT)
            ->removeColumn('EnvironmentIsProduction')
            ->addColumn('EnvironmentOrder', 'integer')
            ->save();
    }
}
