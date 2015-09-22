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

        $table = $this->table(DatabaseMeta::DB_REPO);
        $table
            ->removeColumn('RepositoryEbName')
            ->save();

        $table = $this->table(DatabaseMeta::DB_DEPLOYMENT);
        $table
            ->addColumn('DeploymentCDName', 'string', ['limit' => 100, 'null' => true, 'after' => 'DeploymentPath'])
            ->addColumn('DeploymentCDGroup', 'string', ['limit' => 100, 'null' => true, 'after' => 'DeploymentCDName'])
            ->addColumn('DeploymentCDConfiguration', 'string', ['limit' => 100, 'null' => true, 'after' => 'DeploymentCDGroup'])

            ->addColumn('DeploymentEbName', 'string', ['limit' => 100, 'null' => true, 'after' => 'DeploymentCDConfiguration'])
            ->save();

        // indexes
        $table
            ->addIndex(['DeploymentCDName', 'DeploymentCDGroup'], ['unique' => true]);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $table = $this->table(DatabaseMeta::DB_REPO);
        $table
            ->addColumn('RepositoryEbName', 'string', ['limit' => 255, 'after' => 'RepositoryPostPushCmd'])
            ->save();


        $table = $this->table(DatabaseMeta::DB_DEPLOYMENT);
        $table
            ->removeColumn('DeploymentCDName')
            ->removeColumn('DeploymentCDGroup')
            ->removeColumn('DeploymentCDConfiguration')
            ->removeColumn('DeploymentEbName')
            ->save();

        // indexes
        $table
            ->removeIndex(['DeploymentCDName', 'DeploymentCDGroup']);
    }
}
