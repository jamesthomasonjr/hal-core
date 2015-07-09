<?php

use Phinx\Migration\AbstractMigration;
use QL\Hal\Core\DatabaseMeta;

class CreateDeploymentViews extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        // Views
        $viewTable = $this->table(DatabaseMeta::DB_DEPLOYMENT_VIEW, [
            'id' => false,
            'primary_key' => 'DeploymentViewId'
        ]);

        $viewTable
            ->addColumn('DeploymentViewId', 'char', ['limit' => 32])
            ->addColumn('DeploymentViewName', 'string', ['limit' => 100])

            ->addColumn('ApplicationId', 'integer', ['null' => true])
            ->addColumn('EnvironmentId', 'integer')
            ->addColumn('UserId', 'integer', ['null' => true]);
        $viewTable->save();

        // Pools
        $poolTable = $this->table(DatabaseMeta::DB_DEPLOYMENT_POOL, [
            'id' => false,
            'primary_key' => 'DeploymentPoolId'
        ]);

        $poolTable
            ->addColumn('DeploymentPoolId', 'char', ['limit' => 32])
            ->addColumn('DeploymentPoolName', 'string', ['limit' => 100])
            ->addColumn('DeploymentPoolDeployments', 'string', ['limit' => 1000])

            ->addColumn('DeploymentViewId', 'char', ['limit' => 32]);
        $poolTable->save();

        // get FKed boiiii
        $viewTable
            ->addForeignKey('ApplicationId', DatabaseMeta::DB_REPO, 'RepositoryId', [
                'delete' => 'CASCADE',
                'update'=> 'CASCADE'
            ])
            ->addForeignKey('EnvironmentId', DatabaseMeta::DB_ENVIRONMENT, 'EnvironmentId', [
                'delete' => 'CASCADE',
                'update'=> 'CASCADE'
            ])
            ->addForeignKey('UserId', DatabaseMeta::DB_USER, 'UserId', [
                'delete' => 'CASCADE',
                'update'=> 'CASCADE'
            ])
            ->save();

        $poolTable
            ->addForeignKey('DeploymentViewId', DatabaseMeta::DB_DEPLOYMENT_VIEW, 'DeploymentViewId', [
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
        $this->dropTable(DatabaseMeta::DB_DEPLOYMENT_POOL);
        $this->dropTable(DatabaseMeta::DB_DEPLOYMENT_VIEW);
    }
}
