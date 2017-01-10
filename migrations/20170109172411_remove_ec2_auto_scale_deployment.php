<?php

use Phinx\Migration\AbstractMigration;
use QL\Hal\Core\DatabaseMeta;
use QL\Hal\Core\Type\EnumType\ServerEnum;

class RemoveEc2AutoScaleDeployment extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table(DatabaseMeta::DB_DEPLOYMENT);

        $table
            ->removeIndex(['DeploymentEc2Pool'])
            ->save();

        $table
            ->removeColumn('DeploymentEc2Pool')
            ->save();

        $types = ServerEnum::values();
        $this->table(DatabaseMeta::DB_SERVER)
            ->changeColumn('ServerType', 'enum', [
                'values' => $types,
                'default' => array_shift($types)
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
            ->addColumn('DeploymentEc2Pool', 'string', ['limit' => 100, 'null' => true, 'after' => 'DeploymentEbEnvironment'])
            ->save();

        $table
            ->addIndex(['DeploymentEc2Pool'], ['unique' => true])
            ->save();

        $types = ServerEnum::values();
        $this->table(DatabaseMeta::DB_SERVER)
            ->changeColumn('ServerType', 'enum', [
                'values' => $types,
                'default' => array_shift($types)
            ])
            ->save();
    }
}
