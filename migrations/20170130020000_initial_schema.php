<?php

use Hal\Core\Database\PhinxMigration;
if (!class_exists(PhinxMigration::class)) { require_once __DIR__ . '/../src/Database/PhinxMigration.php'; }

class InitialSchema extends PhinxMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->createUserTables();
        $this->createTargetTables();
        $this->createJobTables();

        // environment
        $this->createUUIDTable('environments')
            ->addColumn('name',          'string',   ['limit' => 20])
            ->addColumn('is_production', 'boolean',  ['default' => false])
            ->update();

        // organization
        $this->createUUIDTable('organizations')
            ->addColumn('identifier', 'string',   ['limit' => 30])
            ->addColumn('name',       'string',   ['limit' => 100])
            ->update();

        // system settings
        $this->createUUIDTable('system_settings')
            ->addColumn('name',   'string',   ['limit' => 100])
            ->addColumn('value',  'text',     $this->textOptions('64kb'))
            ->update();

        // audit events
        $this->createUUIDTable('audit_events')
            ->addColumn('created', 'datetime',  [])
            ->addColumn('action',  'string',    $this->enumOptions('create'))
            ->addColumn('owner',   'string',    ['limit' => 100])
            ->addColumn('entity',  'string',    ['limit' => 100])
            ->addColumn('data',    'text',      $this->textOptions('64kb'))
            ->update();

        // applications
        $this->createUUIDTable('applications')
            ->addColumn('identifier',        'string',  ['limit' => 30])
            ->addColumn('name',              'string',  ['limit' => 100])
            ->addColumn('github_owner',      'string',  ['limit' => 100])
            ->addColumn('github_repository', 'string',  ['limit' => 100])
            ->addColumn('organization_id',   'uuid',    ['null' => true])
            ->update();

        // credentials
        $this->createUUIDTable('credentials')
            ->addColumn('type',                'string',    $this->enumOptions('aws_static'))
            ->addColumn('name',                'string',    ['limit' => 100])
            ->addColumn('is_internal',         'boolean',   ['default' => false])
            ->addColumn('awsstatic_key',       'string',    ['limit' => 100])
            ->addColumn('awsstatic_secret',    'text',      $this->textOptions('64kb'))
            ->addColumn('awsrole_account',     'string',    ['limit' => 25])
            ->addColumn('awsrole_role',        'string',    ['limit' => 100])
            ->addColumn('privatekey_username', 'string',    ['limit' => 100])
            ->addColumn('privatekey_path',     'string',    ['limit' => 200])
            ->addColumn('privatekey_file',     'text',      $this->textOptions('64kb'))
            ->update();

        // encrypted properties
        $this->createUUIDTable('encrypted_properties')
            ->addColumn('name',            'string',  ['limit' => 100])
            ->addColumn('data',            'text',    $this->textOptions('64kb'))
            ->addColumn('application_id',  'uuid',    ['null' => true])
            ->addColumn('environment_id',  'uuid',    ['null' => true])
            ->update();
    }

    public function createUserTables()
    {
        // users
        $this->createUUIDTable('users')
            ->addColumn('username',    'string',   ['limit' => 32])
            ->addColumn('name',        'string',   ['limit' => 100])
            ->addColumn('email',       'string',   ['limit' => 200])
            ->addColumn('is_disabled', 'boolean',  ['default' => false])
            ->update();

        // users - settings
        $this->createUUIDTable('users_settings')
            ->addColumn('favorite_applications', 'json',   [])
            ->addColumn('user_id',               'uuid',   [])
            ->update();

        // users - tokens
        $this->createUUIDTable('users_tokens')
            ->addColumn('name',    'string',    ['limit' => 100])
            ->addColumn('value',   'string',    ['limit' => 100])
            ->addColumn('user_id', 'uuid',      [])
            ->update();

        // users - permissions
        $this->createUUIDTable('users_permissions')
            ->addColumn('type',            'string',  $this->enumOptions('member'))
            ->addColumn('user_id',         'uuid',    [])
            ->addColumn('application_id',  'uuid',    ['null' => true])
            ->addColumn('organization_id', 'uuid',    ['null' => true])
            ->addColumn('environment_id',  'uuid',    ['null' => true])
            ->update();
    }

    public function createTargetTables()
    {
        // groups
        $this->createUUIDTable('groups')
            ->addColumn('type',           'string',    $this->enumOptions('rsync'))
            ->addColumn('name',           'string',    ['limit' => 100])
            ->addColumn('environment_id', 'uuid',      ['null' => true])
            ->update();

        // targets
        $this->createUUIDTable('targets')
            ->addColumn('name',           'string',   ['limit' => 100])
            ->addColumn('url',            'string',   ['limit' => 200])
            ->addColumn('parameters',     'json',     [])
            ->addColumn('application_id', 'uuid',     ['null' => true])
            ->addColumn('group_id',       'uuid',     ['null' => true])
            ->addColumn('credential_id',  'uuid',     ['null' => true])
            ->addColumn('release_id',     'string',   ['limit' => 20, 'null' => true])
            ->update();
    }

    public function createJobTables()
    {
        // jobs - builds
        $builds = $this->table('jobs_builds', [
            'id' => false,
            'primary_key' => 'id'
        ]);

        $builds
            ->addColumn('id', 'string', ['limit' => 20])
            ->create();

        $builds
            ->addColumn('created',        'datetime',   [])
            ->addColumn('start',          'datetime',   ['null' => true])
            ->addColumn('end',            'datetime',   ['null' => true])
            ->addColumn('status',         'string',     $this->enumOptions('pending'))
            ->addColumn('reference',      'string',     ['limit' => 100])
            ->addColumn('commit_sha',     'string',     ['limit' => 40])
            ->addColumn('user_id',        'uuid',       ['null' => true])
            ->addColumn('application_id', 'uuid',       ['null' => true])
            ->addColumn('environment_id', 'uuid',       ['null' => true])
            ->update();

        // jobs - releases
        $releases = $this->table('jobs_releases', [
            'id' => false,
            'primary_key' => 'id'
        ]);

        $releases
            ->addColumn('id', 'string', ['limit' => 20])
            ->create();

        $releases
            ->addColumn('created',        'datetime',   [])
            ->addColumn('start',          'datetime',   ['null' => true])
            ->addColumn('end',            'datetime',   ['null' => true])
            ->addColumn('status',         'string',     $this->enumOptions('pending'))
            ->addColumn('build_id',       'string',     ['limit' => 20])
            ->addColumn('user_id',        'uuid',       ['null' => true])
            ->addColumn('application_id', 'uuid',       ['null' => true])
            ->addColumn('target_id',      'uuid',       ['null' => true])
            ->update();

        // jobs - events
        $this->createUUIDTable('jobs_events')
            ->addColumn('created',        'datetime',   [])
            ->addColumn('stage',          'string',     $this->enumOptions('unknown'))
            ->addColumn('status',         'string',     $this->enumOptions('info'))
            ->addColumn('event_order',    'integer',    [])
            ->addColumn('message',        'string',     ['limit' => 200])
            ->addColumn('parameters',     'binary',     $this->blobOptions('16mb'))
            ->addColumn('parent_id',      'string',     ['limit' => 20])
            ->update();

        // jobs - meta
        $this->createUUIDTable('jobs_meta')
            ->addColumn('created',     'datetime',     [])
            ->addColumn('name',        'string',       ['limit' => 100])
            ->addColumn('value',       'text',         $this->textOptions('64kb'))
            ->addColumn('parent_id',   'string',       ['limit' => 20])
            ->update();

        // job - processes
        $this->createUUIDTable('jobs_processes')
            ->addColumn('created',      'datetime',   [])
            ->addColumn('status',       'string',     $this->enumOptions('pending'))
            ->addColumn('message',      'string',     ['limit' => 200])
            ->addColumn('parameters',   'json',       [])

            ->addColumn('parent_id',    'string',     ['limit' => 20])
            ->addColumn('child_id',     'string',     ['limit' => 20])
            ->addColumn('child_type',   'string',     ['limit' => 40])
            ->addColumn('user_id',      'uuid',       ['null' => true])
            ->update();
    }
}
