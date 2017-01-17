<?php

use Phinx\Migration\AbstractMigration;
use QL\Hal\Core\DatabaseMeta;

class AddScriptDeploymentType extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table(DatabaseMeta::DB_DEPLOYMENT);

        $table
            ->addColumn('DeploymentScriptContext', 'string', ['limit' => 100, 'null' => true, 'after' => 'DeploymentS3File'])
            ->save();

        $types = ['rsync', 'eb', 's3', 'cd', 'script'];
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
            ->removeColumn('DeploymentScriptContext')
            ->save();

        $types = ['rsync', 'eb', 's3', 'cd'];
        $this->table(DatabaseMeta::DB_SERVER)
            ->changeColumn('ServerType', 'enum', [
                'values' => $types,
                'default' => array_shift($types)
            ])
            ->save();
    }
}
