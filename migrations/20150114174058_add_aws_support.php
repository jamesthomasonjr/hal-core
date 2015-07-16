<?php

use Phinx\Migration\AbstractMigration;
use QL\Hal\Core\Type\EnumType\ServerEnum;

class AddAwsSupport extends AbstractMigration
{
    const TABLE_SERVERS = 'Servers';
    const TABLE_REPOSITORIES = 'Repositories';
    const TABLE_DEPLOYMENTS = 'Deployments';

    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->updateRepositories();
        $this->updateDeployments();
        $this->updateServers();
    }

    public function updateRepositories()
    {
        $this->execute('UPDATE Repositories SET RepositoryBuildCmd="" WHERE RepositoryBuildCmd IS NULL');
        $this->execute('UPDATE Repositories SET RepositoryBuildTransformCmd="" WHERE RepositoryBuildTransformCmd IS NULL');
        $this->execute('UPDATE Repositories SET RepositoryPrePushCmd="" WHERE RepositoryPrePushCmd IS NULL');
        $this->execute('UPDATE Repositories SET RepositoryPostPushCmd="" WHERE RepositoryPostPushCmd IS NULL');

        $this->table(self::TABLE_REPOSITORIES)
            ->addColumn('RepositoryEbName', 'string', ['limit' => 255, 'after' => 'RepositoryPostPushCmd'])

            // make cmds not null
            ->changeColumn('RepositoryBuildCmd', 'string', ['limit' => 255])
            ->changeColumn('RepositoryBuildTransformCmd', 'string', ['limit' => 255])
            ->changeColumn('RepositoryPrePushCmd', 'string', ['limit' => 255])
            ->changeColumn('RepositoryPostPushCmd', 'string', ['limit' => 255])

            ->save();
    }

    public function updateDeployments()
    {
        // Drop old FKs and Indexes
        $this->table(self::TABLE_DEPLOYMENTS)
            ->dropForeignKey('ServerId')
            ->save();
        $this->table(self::TABLE_DEPLOYMENTS)
            ->removeIndex(['ServerId', 'DeploymentPath'])
            ->save();

        // Make path nullable
        // Add EbEnvironment, Ec2Pool
        $this->table(self::TABLE_DEPLOYMENTS)
            ->changeColumn('DeploymentPath', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('DeploymentEbEnvironment', 'string', ['limit' => 100, 'null' => true, 'after' => 'DeploymentPath'])
            ->addColumn('DeploymentEc2Pool', 'string', ['limit' => 100, 'null' => true, 'after' => 'DeploymentEbEnvironment'])
            ->save();

        // unique = serverId + path
        // unique = ebs env
        $this->table(self::TABLE_DEPLOYMENTS)
            ->addIndex(['ServerId'])
            ->addIndex(['ServerId', 'DeploymentPath'], ['unique' => true])
            ->addIndex(['DeploymentEbEnvironment'], ['unique' => true])
            ->addIndex(['DeploymentEc2Pool'], ['unique' => true])
            ->save();

        // Deployments
        $this->table(self::TABLE_DEPLOYMENTS)
            ->addForeignKey('ServerId', self::TABLE_SERVERS, 'ServerId', [
                'delete' => 'RESTRICT',
                'update'=> 'CASCADE'
            ])
            ->save();
    }

    public function updateServers()
    {
        // unique constraint on server name will live in app logic
        $types = ServerEnum::values();

        $this->table(self::TABLE_SERVERS)
            ->addColumn('ServerType', 'enum', [
                'values' => $types,
                'default' => array_shift($types)
            ])
            ->removeIndex(['ServerName'])
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table(self::TABLE_SERVERS)
            ->addIndex(['ServerName'], ['unique' => true])
            ->save();

        $this->table(self::TABLE_REPOSITORIES)
            ->removeColumn('RepositoryEbName')
            ->save();


        $this->table(self::TABLE_DEPLOYMENTS)
            ->removeIndex(['ServerId', 'DeploymentPath'])
            ->save();

        $this->table(self::TABLE_DEPLOYMENTS)
            ->removeIndex(['ServerId', 'DeploymentPath'])

            ->removeIndex(['DeploymentEbEnvironment'])
            ->removeColumn('DeploymentEbEnvironment')

            ->removeIndex(['DeploymentEc2Pool'])
            ->removeColumn('DeploymentEc2Pool')
            ->save();
    }
}
