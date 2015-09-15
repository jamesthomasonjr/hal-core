<?php

use Phinx\Migration\AbstractMigration;
use QL\Hal\Core\DatabaseMeta;
use QL\Hal\Core\Type\EnumType\ServerEnum;

class AddCodeDeployDeploymentType extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $types = ServerEnum::values();

        $table = $this->table(DatabaseMeta::DB_SERVER);
        $table
            ->changeColumn('ServerType', 'enum', [
                'values' => $types,
                'default' => array_shift($types)
            ])
            ->save();
    }
}
