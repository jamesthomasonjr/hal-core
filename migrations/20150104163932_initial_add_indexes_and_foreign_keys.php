<?php

use Phinx\Migration\AbstractMigration;

class InitialAddIndexesAndForeignKeys extends AbstractMigration
{
    const TABLE_USERS = 'Users';
    const TABLE_CONSUMERS = 'Consumers';
    const TABLE_TOKENS = 'Tokens';

    const TABLE_ENVIRONMENTS = 'Environments';
    const TABLE_SERVERS = 'Servers';
    const TABLE_GROUPS = 'Groups';
    const TABLE_REPOSITORIES = 'Repositories';
    const TABLE_DEPLOYMENTS = 'Deployments';
    const TABLE_BUILDS = 'Builds';
    const TABLE_PUSHES = 'Pushes';
    const TABLE_AUDITS = 'AuditLogs';
    const TABLE_EVENTS = 'EventLogs';

    const TABLE_SUBSCRIPTIONS = 'Subscriptions';

    /**
     * Migrate Up.
     */
    public function up()
    {
        // add indexes
        $this->addIndexes();

        // add foreign keys
        $this->addForeignKeys();

        // $this->addSubscriptionIndexes();
        // $this->addSubscriptionForeignKeys();
    }

    /**
     * WARNING! This wipes all indexes and foreign keys from the db!
     *
     * Migrate down.
     */
    public function down()
    {
        // remove foreign keys
        $this->removeForeignKeys();

        // remove indexes
        $this->removeIndexes();
    }

    private function addIndexes()
    {
        $this->table(self::TABLE_USERS)
            ->addIndex(['UserHandle'], ['unique' => true])
            ->addIndex(['UserName'], ['unique' => true])
            ->save();

        $this->table(self::TABLE_TOKENS)
            ->addIndex(['TokenValue'], ['unique' => true])
            ->addIndex(['UserId'])
            ->addIndex(['ConsumerId'])
            ->save();

        $this->table(self::TABLE_ENVIRONMENTS)
            ->addIndex(['EnvironmentKey'], ['unique' => true])
            ->save();

        $this->table(self::TABLE_SERVERS)
            ->addIndex(['ServerName'], ['unique' => true])
            ->addIndex(['EnvironmentId'])
            ->save();

        $this->table(self::TABLE_GROUPS)
            ->addIndex(['GroupKey'], ['unique' => true])
            ->save();

        $this->table(self::TABLE_REPOSITORIES)
            ->addIndex(['RepositoryKey'], ['unique' => true])
            ->addIndex(['GroupId'])
            ->save();

        $this->table(self::TABLE_DEPLOYMENTS)
            ->addIndex(['ServerId', 'DeploymentPath'], ['unique' => true])
            ->save();

        $this->table(self::TABLE_BUILDS)
            ->addIndex(['UserId'])
            ->addIndex(['ConsumerId'])
            ->addIndex(['EnvironmentId'])
            ->addIndex(['RepositoryId', 'EnvironmentId'])
            ->addIndex(['BuildCreated'])
            ->save();

        $this->table(self::TABLE_PUSHES)
            ->addIndex(['UserId'])
            ->addIndex(['BuildId'])
            ->addIndex(['DeploymentId'])
            ->addIndex(['PushCreated'])
            ->save();

        $this->table(self::TABLE_AUDITS)
            ->addIndex(['UserId'])
            ->save();

        $this->table(self::TABLE_EVENTS)
            ->addIndex(['Event'])
            ->addIndex(['BuildId'])
            ->addIndex(['PushId'])
            ->save();
    }

    private function addForeignKeys()
    {
        // Tokens
        $this->table(self::TABLE_TOKENS)
            ->addForeignKey('UserId', self::TABLE_USERS, 'UserId', [
                'delete' => 'CASCADE',
                'update'=> 'CASCADE'
            ])
            ->addForeignKey('ConsumerId', self::TABLE_CONSUMERS, 'ConsumerId', [
                'delete' => 'CASCADE',
                'update'=> 'CASCADE'
            ])
            ->save();

        // Servers
        $this->table(self::TABLE_SERVERS)
            ->addForeignKey('EnvironmentId', self::TABLE_ENVIRONMENTS, 'EnvironmentId', [
                'delete' => 'RESTRICT',
                'update'=> 'CASCADE'
            ])
            ->save();

        // Repositories
        $this->table(self::TABLE_REPOSITORIES)
            ->addForeignKey('GroupId', self::TABLE_GROUPS, 'GroupId', [
                'delete' => 'RESTRICT',
                'update'=> 'CASCADE'
            ])
            ->save();

        // Deployments
        $this->table(self::TABLE_DEPLOYMENTS)
            ->addForeignKey('RepositoryId', self::TABLE_REPOSITORIES, 'RepositoryId', [
                'delete' => 'RESTRICT',
                'update'=> 'CASCADE'
            ])
            ->addForeignKey('ServerId', self::TABLE_SERVERS, 'ServerId', [
                'delete' => 'RESTRICT',
                'update'=> 'CASCADE'
            ])
            ->save();

        // Builds
        $this->table(self::TABLE_BUILDS)
            ->addForeignKey('UserId', self::TABLE_USERS, 'UserId', [
                'delete' => 'SET_NULL',
                'update'=> 'CASCADE'
            ])
            ->addForeignKey('ConsumerId', self::TABLE_CONSUMERS, 'ConsumerId', [
                'delete' => 'SET_NULL',
                'update'=> 'CASCADE'
            ])
            ->addForeignKey('RepositoryId', self::TABLE_REPOSITORIES, 'RepositoryId', [
                'delete' => 'RESTRICT',
                'update'=> 'CASCADE'
            ])
            ->addForeignKey('EnvironmentId', self::TABLE_ENVIRONMENTS, 'EnvironmentId', [
                'delete' => 'RESTRICT',
                'update'=> 'CASCADE'
            ])
            ->save();

        // Pushes
        $this->table(self::TABLE_PUSHES)
            ->addForeignKey('UserId', self::TABLE_USERS, 'UserId', [
                'delete' => 'SET_NULL',
                'update'=> 'CASCADE'
            ])
            ->addForeignKey('ConsumerId', self::TABLE_CONSUMERS, 'ConsumerId', [
                'delete' => 'SET_NULL',
                'update'=> 'CASCADE'
            ])
            ->addForeignKey('DeploymentId', self::TABLE_DEPLOYMENTS, 'DeploymentId', [
                'delete' => 'SET_NULL',
                'update'=> 'CASCADE'
            ])
            ->addForeignKey('BuildId', self::TABLE_BUILDS, 'BuildId', [
                'delete' => 'RESTRICT',
                'update'=> 'CASCADE'
            ])
            ->save();

        // Audit Logs
        $this->table(self::TABLE_AUDITS)
            ->addForeignKey('UserId', self::TABLE_USERS, 'UserId', [
                'delete' => 'RESTRICT',
                'update'=> 'CASCADE'
            ])
            ->save();

        // Event Logs
        $this->table(self::TABLE_EVENTS)
            ->addForeignKey('BuildId', self::TABLE_BUILDS, 'BuildId', [
                'delete' => 'CASCADE',
                'update'=> 'CASCADE'
            ])
            ->addForeignKey('PushId', self::TABLE_PUSHES, 'PushId', [
                'delete' => 'CASCADE',
                'update'=> 'CASCADE'
            ])
            ->save();
    }

    private function addSubscriptionIndexes()
    {
        $this->table(self::TABLE_SUBSCRIPTIONS)
            ->addIndex(['ConsumerId'])
            ->addIndex(['RepositoryId'])
            ->addIndex(['EnvironmentId'])
            ->addIndex(['ServerId'])
            ->addIndex(['GroupId'])
            ->save();
    }

    private function addSubscriptionForeignKeys()
    {
        // Subscriptions
        $this->table(self::TABLE_SUBSCRIPTIONS)
            ->addForeignKey('ConsumerId', self::TABLE_CONSUMERS, 'ConsumerId', [
                'delete' => 'CASCADE',
                'update'=> 'CASCADE'
            ])
            ->addForeignKey('RepositoryId', self::TABLE_REPOSITORIES, 'RepositoryId', [
                'delete' => 'CASCADE',
                'update'=> 'CASCADE'
            ])
            ->addForeignKey('EnvironmentId', self::TABLE_ENVIRONMENTS, 'EnvironmentId', [
                'delete' => 'CASCADE',
                'update'=> 'CASCADE'
            ])
            ->addForeignKey('ServerId', self::TABLE_SERVERS, 'ServerId', [
                'delete' => 'CASCADE',
                'update'=> 'CASCADE'
            ])
            ->addForeignKey('GroupId', self::TABLE_GROUPS, 'GroupId', [
                'delete' => 'CASCADE',
                'update'=> 'CASCADE'
            ])
            ->save();
    }

    private function removeIndexes()
    {
        $this->table(self::TABLE_USERS)
            ->removeIndex(['UserHandle'])
            ->removeIndex(['UserName'])
            ->save();

        $this->table(self::TABLE_TOKENS)
            ->removeIndex(['TokenValue'])
            ->removeIndex(['UserId'])
            ->removeIndex(['ConsumerId'])
            ->save();

        $this->table(self::TABLE_ENVIRONMENTS)
            ->removeIndex(['EnvironmentKey'])
            ->save();

        $this->table(self::TABLE_SERVERS)
            ->removeIndex(['ServerName'])
            ->removeIndex(['EnvironmentId'])
            ->save();

        $this->table(self::TABLE_GROUPS)
            ->removeIndex(['GroupKey'])
            ->save();

        $this->table(self::TABLE_REPOSITORIES)
            ->removeIndex(['RepositoryKey'])
            ->removeIndex(['GroupId'])
            ->save();

        $this->table(self::TABLE_DEPLOYMENTS)
            ->removeIndex(['ServerId', 'DeploymentPath'])
            ->save();

        $this->table(self::TABLE_BUILDS)
            ->removeIndex(['UserId'])
            ->removeIndex(['ConsumerId'])
            ->removeIndex(['EnvironmentId'])
            ->removeIndex(['RepositoryId', 'EnvironmentId'])
            ->removeIndex(['BuildCreated'])
            ->save();

        $this->table(self::TABLE_PUSHES)
            ->removeIndex(['UserId'])
            ->removeIndex(['BuildId'])
            ->removeIndex(['DeploymentId'])
            ->removeIndex(['PushCreated'])
            ->save();

        $this->table(self::TABLE_AUDITS)
            ->removeIndex(['UserId'])
            ->save();

        $this->table(self::TABLE_EVENTS)
            ->removeIndex(['Event'])
            ->removeIndex(['BuildId'])
            ->removeIndex(['PushId'])
            ->save();
    }

    private function removeForeignKeys()
    {
        // Tokens
        $this->table(self::TABLE_TOKENS)
            ->dropForeignKey('UserId')
            ->dropForeignKey('ConsumerId')
            ->save();

        // Servers
        $this->table(self::TABLE_SERVERS)
            ->dropForeignKey('EnvironmentId')
            ->save();

        // Repositories
        $this->table(self::TABLE_REPOSITORIES)
            ->dropForeignKey('GroupId')
            ->save();

        // Deployments
        $this->table(self::TABLE_DEPLOYMENTS)
            ->dropForeignKey('RepositoryId')
            ->dropForeignKey('ServerId')
            ->save();

        // Builds
        $this->table(self::TABLE_BUILDS)
            ->dropForeignKey('UserId')
            ->dropForeignKey('ConsumerId')
            ->dropForeignKey('RepositoryId')
            ->dropForeignKey('EnvironmentId')
            ->save();

        // Pushes
        $this->table(self::TABLE_PUSHES)
            ->dropForeignKey('UserId')
            ->dropForeignKey('ConsumerId')
            ->dropForeignKey('DeploymentId')
            ->dropForeignKey('BuildId')
            ->save();

        // Audit Logs
        $this->table(self::TABLE_AUDITS)
            ->dropForeignKey('UserId')
            ->save();

        // Event Logs
        $this->table(self::TABLE_EVENTS)
            ->dropForeignKey('BuildId')
            ->dropForeignKey('PushId')
            ->save();
    }
}
