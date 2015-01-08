<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;
use QL\Hal\Core\Entity\Type\BuildStatusEnumType;
use QL\Hal\Core\Entity\Type\PushStatusEnumType;
use QL\Hal\Core\Entity\Type\EventEnumType;
use QL\Hal\Core\Entity\Type\EventStatusEnumType;

class InitialSchemaVersion23 extends AbstractMigration
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
        // create tables
        $this->createUsers();
        $this->createConsumers();
        $this->createTokens();

        $this->createEnvironments();
        $this->createServers();
        $this->createGroups();
        $this->createRepositories();
        $this->createDeployments();

        $this->createBuilds();
        $this->createPushes();

        $this->createAuditLogs();
        $this->createEventLogs();

        // $this->createSubscriptions();
        // $this->addSubscriptionForeignKeys();

        // add foreign keys
        $this->addForeignKeys();
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

    private function createUsers()
    {
        if ($this->hasTable(self::TABLE_USERS)) {
            return;
        }

        $table = $this->table(self::TABLE_USERS, [
            'id' => false,
            'primary_key' => 'UserId'
        ]);

        $table
            ->addColumn('UserId', 'integer')
            ->addColumn('UserHandle', 'string', ['limit' => 32])
            ->addColumn('UserName', 'string', ['limit' => 128])
            ->addColumn('UserEmail', 'string', ['limit' => 128])
            ->addColumn('UserPictureUrl', 'string', ['limit' => 128])
            ->addColumn('UserIsActive', 'boolean')

            ->addIndex(['UserHandle'], ['unique' => true])
            ->addIndex(['UserName'], ['unique' => true])

            ->save();
    }

    private function createConsumers()
    {
        if ($this->hasTable(self::TABLE_CONSUMERS)) {
            return;
        }

        $table = $this->table(self::TABLE_CONSUMERS, [
            'id' => 'ConsumerId'
        ]);

        $table
            ->addColumn('ConsumerKey', 'string', ['limit' => 24])
            ->addColumn('ConsumerName', 'string', ['limit' => 48])
            ->addColumn('ConsumerSecret', 'string', ['limit' => 128])
            ->addColumn('ConsumerIsActive', 'boolean')

            ->save();
    }

    private function createTokens()
    {
        if ($this->hasTable(self::TABLE_TOKENS)) {
            return;
        }

        $table = $this->table(self::TABLE_TOKENS, [
            'id' => 'TokenId'
        ]);

        $table
            ->addColumn('TokenValue', 'string', ['limit' => 64])
            ->addColumn('TokenLabel', 'string', ['limit' => 128])

            ->addColumn('UserId', 'integer', ['null' => true])
            ->addColumn('ConsumerId', 'integer', ['null' => true])

            ->addIndex(['TokenValue'], ['unique' => true])
            ->addIndex(['UserId'])
            ->addIndex(['ConsumerId'])

            ->save();
    }

    private function createEnvironments()
    {
        if ($this->hasTable(self::TABLE_ENVIRONMENTS)) {
            return;
        }

        $table = $this->table(self::TABLE_ENVIRONMENTS, [
            'id' => 'EnvironmentId'
        ]);

        $table
            ->addColumn('EnvironmentKey', 'string', ['limit' => 24])
            ->addColumn('EnvironmentOrder', 'integer')

            ->addIndex(['EnvironmentKey'], ['unique' => true])

            ->save();
    }

    private function createServers()
    {
        if ($this->hasTable(self::TABLE_SERVERS)) {
            return;
        }

        $table = $this->table(self::TABLE_SERVERS, [
            'id' => 'ServerId'
        ]);

        $table
            ->addColumn('ServerName', 'string', ['limit' => 24])

            ->addColumn('EnvironmentId', 'integer')

            ->addIndex(['ServerName'], ['unique' => true])
            ->addIndex(['EnvironmentId'])

            ->save();
    }

    private function createGroups()
    {
        if ($this->hasTable(self::TABLE_GROUPS)) {
            return;
        }

        $table = $this->table(self::TABLE_GROUPS, [
            'id' => 'GroupId'
        ]);

        $table
            ->addColumn('GroupKey', 'string', ['limit' => 24])
            ->addColumn('GroupName', 'string', ['limit' => 48])

            ->addIndex(['GroupKey'], ['unique' => true])

            ->save();
    }

    private function createRepositories()
    {
        if ($this->hasTable(self::TABLE_REPOSITORIES)) {
            return;
        }

        $table = $this->table(self::TABLE_REPOSITORIES, [
            'id' => 'RepositoryId'
        ]);

        $table
            ->addColumn('RepositoryKey', 'string', ['limit' => 24])
            ->addColumn('RepositoryDescription', 'string', ['limit' => 255])
            ->addColumn('RepositoryGithubUser', 'string', ['limit' => 48])
            ->addColumn('RepositoryGithubRepo', 'string', ['limit' => 48])
            ->addColumn('RepositoryEmail', 'string', ['limit' => 128])
            ->addColumn('RepositoryBuildCmd', 'string', ['limit' => 255])
            ->addColumn('RepositoryBuildTransformCmd', 'string', ['limit' => 255])
            ->addColumn('RepositoryPrePushCmd', 'string', ['limit' => 128])
            ->addColumn('RepositoryPostPushCmd', 'string', ['limit' => 128])

            ->addColumn('GroupId', 'integer')

            ->addIndex(['RepositoryKey'], ['unique' => true])
            ->addIndex(['GroupId'])

            ->save();
    }

    private function createDeployments()
    {
        if ($this->hasTable(self::TABLE_DEPLOYMENTS)) {
            return;
        }

        $table = $this->table(self::TABLE_DEPLOYMENTS, [
            'id' => 'DeploymentId'
        ]);

        $table
            ->addColumn('DeploymentPath', 'string', ['limit' => 255])
            ->addColumn('DeploymentUrl', 'string', ['limit' => 255])

            ->addColumn('RepositoryId', 'integer')
            ->addColumn('ServerId', 'integer')

            ->addIndex(['ServerId', 'DeploymentPath'], ['unique' => true])

            ->save();
    }

    private function createBuilds()
    {
        if ($this->hasTable(self::TABLE_BUILDS)) {
            return;
        }

        $table = $this->table(self::TABLE_BUILDS, [
            'id' => false,
            'primary_key' => 'BuildId'
        ]);

        $table
            ->addColumn('BuildId', 'char', ['limit' => 40])
            ->addColumn('BuildCreated', 'datetime')
            ->addColumn('BuildStart', 'datetime', ['null' => true])
            ->addColumn('BuildEnd', 'datetime', ['null' => true])
            // ->addColumn('BuildStatus') -- see below
            ->addColumn('BuildBranch', 'string', ['limit' => 64])
            ->addColumn('BuildCommit', 'char', ['limit' => 40])

            ->addColumn('UserId', 'integer', ['null' => true])
            ->addColumn('ConsumerId', 'integer', ['null' => true])
            ->addColumn('RepositoryId', 'integer')
            ->addColumn('EnvironmentId', 'integer')

            ->addIndex(['UserId'])
            ->addIndex(['ConsumerId'])
            ->addIndex(['EnvironmentId'])
            ->addIndex(['RepositoryId', 'EnvironmentId'])
            ->addIndex(['BuildCreated'])

            ->save();

        // Phinx (as of 0.4.1) does not support ENUM, so ENUM columns must be manually added
        $statuses = array_map(function($status) {
            return sprintf("'%s'", $status);
        }, BuildStatusEnumType::values());

        $tbl = self::TABLE_BUILDS;
        $default = reset($statuses);
        $statuses = implode(", ", $statuses);

        $this->execute("
ALTER TABLE $tbl
ADD COLUMN
    BuildStatus ENUM($statuses) NOT NULL DEFAULT $default
    AFTER BuildEnd
");
    }

    private function createPushes()
    {
        if ($this->hasTable(self::TABLE_PUSHES)) {
            return;
        }

        $table = $this->table(self::TABLE_PUSHES, [
            'id' => false,
            'primary_key' => 'PushId'
        ]);

        $table
            ->addColumn('PushId', 'char', ['limit' => 40])
            ->addColumn('PushCreated', 'datetime')
            ->addColumn('PushStart', 'datetime', ['null' => true])
            ->addColumn('PushEnd', 'datetime', ['null' => true])
            // ->addColumn('PushStatus') -- see below

            ->addColumn('UserId', 'integer', ['null' => true])
            ->addColumn('ConsumerId', 'integer', ['null' => true])
            ->addColumn('BuildId', 'char', ['limit' => 40])
            ->addColumn('DeploymentId', 'integer', ['null' => true])

            ->addIndex(['UserId'])
            ->addIndex(['BuildId'])
            ->addIndex(['DeploymentId'])
            ->addIndex(['PushCreated'])

            ->save();

        // Phinx (as of 0.4.1) does not support ENUM, so ENUM columns must be manually added
        $statuses = array_map(function($status) {
            return sprintf("'%s'", $status);
        }, PushStatusEnumType::values());

        $tbl = self::TABLE_PUSHES;
        $default = reset($statuses);
        $statuses = implode(", ", $statuses);

        $this->execute("
ALTER TABLE $tbl
ADD COLUMN
    PushStatus ENUM($statuses) NOT NULL DEFAULT $default
    AFTER PushEnd
");
    }

    private function createSubscriptions()
    {
        if ($this->hasTable(self::TABLE_SUBSCRIPTIONS)) {
            return;
        }

        $table = $this->table(self::TABLE_SUBSCRIPTIONS, [
            'id' => 'SubscriptionId'
        ]);

        $table
            ->addColumn('SubscriptionUrl', 'string', ['limit' => 255])
            ->addColumn('SubscriptionEvent', 'string', ['limit' => 24])

            ->addColumn('ConsumerId', 'integer')
            ->addColumn('RepositoryId', 'integer', ['null' => true])
            ->addColumn('EnvironmentId', 'integer', ['null' => true])
            ->addColumn('ServerId', 'integer', ['null' => true])
            ->addColumn('GroupId', 'integer', ['null' => true])

            ->addIndex(['ConsumerId'])
            ->addIndex(['RepositoryId'])
            ->addIndex(['EnvironmentId'])
            ->addIndex(['ServerId'])
            ->addIndex(['GroupId'])

            ->save();
    }

    private function createAuditLogs()
    {
        if ($this->hasTable(self::TABLE_AUDITS)) {
            return;
        }

        $table = $this->table(self::TABLE_AUDITS, [
            'id' => 'AuditLogId'
        ]);

        $table
            ->addColumn('Recorded', 'datetime')
            ->addColumn('Entity', 'string', ['limit' => 255])
            ->addColumn('Action', 'string', ['limit' => 24])
            ->addColumn('Data', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR, 'null' => true])

            ->addColumn('UserId', 'integer')

            ->addIndex(['UserId'])

            ->save();
    }

    private function createEventLogs()
    {
        if ($this->hasTable(self::TABLE_EVENTS)) {
            return;
        }

        $table = $this->table(self::TABLE_EVENTS, [
            'id' => false,
            'primary_key' => 'EventLogId'
        ]);

        $table
            ->addColumn('EventLogId', 'char', ['limit' => 40])
            // ->addColumn('Event') -- see below
            ->addColumn('EventOrder', 'integer')
            ->addColumn('EventLogCreated', 'datetime')
            ->addColumn('EventLogMessage', 'datetime', ['null' => true])
            // ->addColumn('EventLogStatus') -- see below
            ->addColumn('EventLogData', 'text', ['limit' => MysqlAdapter::TEXT_MEDIUM, 'null' => true])

            ->addColumn('BuildId', 'char', ['limit' => 40, 'null' => true])
            ->addColumn('PushId', 'char', ['limit' => 40, 'null' => true])

            // ->addIndex(['Event']) -- see below
            ->addIndex(['BuildId'])
            ->addIndex(['PushId'])

            ->save();

        // Phinx (as of 0.4.1) does not support ENUM, so ENUM columns must be manually added
        $events = array_map(function($event) {
            return sprintf("'%s'", $event);
        }, EventEnumType::values());

        $tbl = self::TABLE_EVENTS;
        $default = end($events); // last enum value is default
        $events = implode(", ", $events);

        $this->execute("
ALTER TABLE $tbl
ADD COLUMN
    Event ENUM($events) NOT NULL DEFAULT $default
    AFTER EventLogId
");

        $statuses = array_map(function($status) {
            return sprintf("'%s'", $status);
        }, EventStatusEnumType::values());

        $default = reset($statuses); // first enum value is default
        $statuses = implode(", ", $statuses);

        $this->execute("
ALTER TABLE $tbl
ADD COLUMN
    EventLogStatus ENUM($statuses) NOT NULL DEFAULT $default
    AFTER EventLogCreated
");

        // Add index on enum column
        $table
            ->addIndex(['Event'])
            ->save();
    }
}
