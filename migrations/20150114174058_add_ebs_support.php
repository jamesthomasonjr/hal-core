<?php

use Phinx\Migration\AbstractMigration;
use QL\Hal\Core\Entity\Type\ServerEnumType;

class AddEbsSupport extends AbstractMigration
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
        $this->table(self::TABLE_REPOSITORIES)
            ->addColumn('RepositoryEbsName', 'string', ['limit' => 255, 'after' => 'RepositoryPostPushCmd'])

            // make cmds not null
            ->changeColumn('RepositoryBuildCmd', 'string', ['limit' => 255])
            ->changeColumn('RepositoryBuildTransformCmd', 'string', ['limit' => 255])
            ->changeColumn('RepositoryPrePushCmd', 'string', ['limit' => 255])
            ->changeColumn('RepositoryPostPushCmd', 'string', ['limit' => 255])

            ->save();
    }

    public function updateDeployments()
    {
        // Audit Logs
        $this->table(self::TABLE_DEPLOYMENTS)
            ->dropForeignKey('ServerId')
            ->save();
        $this->table(self::TABLE_DEPLOYMENTS)
            ->removeIndex(['ServerId', 'DeploymentPath'])
            ->save();

        // Make path nullable
        // Add EbsEnvironment
        $this->table(self::TABLE_DEPLOYMENTS)
            ->changeColumn('DeploymentPath', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('DeploymentEbsEnvironment', 'string', ['limit' => 100, 'null' => true, 'after' => 'DeploymentPath'])
            ->save();

        // unique = serverId + path
        // unique = ebs env
        $this->table(self::TABLE_DEPLOYMENTS)
            ->addIndex(['ServerId'])
            ->addIndex(['ServerId', 'DeploymentPath'], ['unique' => true])
            ->addIndex(['DeploymentEbsEnvironment'], ['unique' => true])
            ->save();
    }

    public function updateServers()
    {
        // unique constraint on server name will live in app logic
        $this->table(self::TABLE_SERVERS)
            ->removeIndex(['ServerName'])
            ->save();

        // Phinx (as of 0.4.1) does not support ENUM, so ENUM columns must be manually added
        $types = array_map(function($type) {
            return sprintf("'%s'", $type);
        }, ServerEnumType::values());

        $tbl = self::TABLE_SERVERS;
        $default = reset($types);
        $types = implode(", ", $types);

        $this->execute("
ALTER TABLE $tbl
ADD COLUMN
    ServerType ENUM($types) NOT NULL DEFAULT $default
    AFTER ServerId
");
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
            ->removeColumn('RepositoryEbsName')
            ->save();


        $this->table(self::TABLE_DEPLOYMENTS)
            ->removeIndex(['ServerId', 'DeploymentPath'])
            ->save();

        $this->table(self::TABLE_DEPLOYMENTS)
            ->removeIndex(['ServerId', 'DeploymentPath'])
            ->removeIndex(['DeploymentEbsEnvironment'])
            ->removeColumn('DeploymentEbsEnvironment')
            ->save();
    }
}
