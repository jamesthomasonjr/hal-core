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
    }

    /**
     * WARNING! This drops all tables in the database! Use only if you want to reset your entire environment!
     *
     * Migrate down.
     */
    public function down()
    {
        $this->table(self::TABLE_EVENTS)
            ->drop();
        $this->table(self::TABLE_AUDITS)
            ->drop();

        $this->table(self::TABLE_PUSHES)
            ->drop();
        $this->table(self::TABLE_BUILDS)
            ->drop();

        $this->table(self::TABLE_DEPLOYMENTS)
            ->drop();
        $this->table(self::TABLE_REPOSITORIES)
            ->drop();
        $this->table(self::TABLE_GROUPS)
            ->drop();

        $this->table(self::TABLE_SERVERS)
            ->drop();
        $this->table(self::TABLE_ENVIRONMENTS)
            ->drop();

        $this->table(self::TABLE_USERS)
            ->drop();
        $this->table(self::TABLE_CONSUMERS)
            ->drop();
        $this->table(self::TABLE_TOKENS)
            ->drop();
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
            ->addColumn('RepositoryDescription', 'string', ['limit' => 64])
            ->addColumn('RepositoryGithubUser', 'string', ['limit' => 48])
            ->addColumn('RepositoryGithubRepo', 'string', ['limit' => 48])
            ->addColumn('RepositoryEmail', 'string', ['limit' => 128, 'null' => true])
            ->addColumn('RepositoryBuildCmd', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('RepositoryBuildTransformCmd', 'string', ['limit' => 255, 'null' => true])
            ->addColumn('RepositoryPrePushCmd', 'string', ['limit' => 128, 'null' => true])
            ->addColumn('RepositoryPostPushCmd', 'string', ['limit' => 128, 'null' => true])

            ->addColumn('GroupId', 'integer')

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
            ->addColumn('BuildCreated', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('BuildStart', 'datetime', ['null' => true])
            ->addColumn('BuildEnd', 'datetime', ['null' => true])
            // ->addColumn('BuildStatus') -- see below
            ->addColumn('BuildBranch', 'string', ['limit' => 64])
            ->addColumn('BuildCommit', 'char', ['limit' => 40])

            ->addColumn('UserId', 'integer', ['null' => true])
            ->addColumn('ConsumerId', 'integer', ['null' => true])
            ->addColumn('RepositoryId', 'integer')
            ->addColumn('EnvironmentId', 'integer')

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
            ->addColumn('PushCreated', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('PushStart', 'datetime', ['null' => true])
            ->addColumn('PushEnd', 'datetime', ['null' => true])
            // ->addColumn('PushStatus') -- see below

            ->addColumn('UserId', 'integer', ['null' => true])
            ->addColumn('ConsumerId', 'integer', ['null' => true])
            ->addColumn('BuildId', 'char', ['limit' => 40])
            ->addColumn('DeploymentId', 'integer', ['null' => true])

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
            ->addColumn('Recorded', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('Entity', 'string', ['limit' => 255])
            ->addColumn('Action', 'string', ['limit' => 24])
            ->addColumn('Data', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR, 'null' => true])

            ->addColumn('UserId', 'integer')

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
            ->addColumn('EventLogCreated', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('EventLogMessage', 'string', ['limit' => 255, 'null' => true])
            // ->addColumn('EventLogStatus') -- see below
            ->addColumn('EventLogData', 'binary', ['null' => true])

            ->addColumn('BuildId', 'char', ['limit' => 40, 'null' => true])
            ->addColumn('PushId', 'char', ['limit' => 40, 'null' => true])

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
    }
}
