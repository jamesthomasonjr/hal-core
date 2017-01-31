<?php

use Hal\Core\Database\PhinxMigration;

class InitialIndexesFor3 extends PhinxMigration
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
        // Handle searchable columns
        foreach ($this->searchableColumns() as $table => $columns) {
            $table = $this->table($table);

            foreach ($columns as $column) {
                $table = $table->addIndex([$column]);
            }

            $table->update();
        }

        // Handle foreign keys
        foreach ($this->foreignKeyColumns() as $parent => $children) {
            list($parentTable, $relationColumn) = explode('/', $parent);

            foreach ($children as $childTable) {
                $this->table($childTable)
                    ->addForeignKey($relationColumn, $parentTable, 'id')
                    ->update();
            }
        }
    }

    protected function searchableColumns()
    {
        return [
            'users' =>            ['username'],
            'users_tokens' =>     ['value'],
            'applications' =>     ['identifier'],
            'organizations' =>    ['identifier'],
            'environments' =>     ['name'],
            'system_settings' =>  ['name'],

            'audit_events' =>     ['created'],
            'jobs_builds' =>      ['created', 'status', 'reference', 'commit_sha'],
            'jobs_releases' =>    ['created', 'status'],
            'jobs_events' =>      ['created', 'parent_id'],
            'jobs_meta' =>        ['parent_id'],
            'jobs_processes' =>   ['parent_id', 'child_id']
        ];
    }

    /**
     * The parent table maps to this foreign key:
     *
     *     'users/user_id' => ['job']
     *
     *     job.user_id => users.id
     *
     */
    protected function foreignKeyColumns()
    {
        return [
            'users/user_id' => [
                'users_settings',
                'users_tokens',
                'users_permissions',
                'targets_views',
                'jobs_builds',
                'jobs_releases',
                'jobs_processes'
            ],
            'applications/application_id' => [
                'users_permissions',
                'encrypted_properties',
                'targets',
                'targets_views',
                'jobs_builds',
                'jobs_releases',
            ],
            'organizations/organization_id' => [
                'users_permissions',
                'applications',
            ],
            'environments/environment_id' => [
                'users_permissions',
                'encrypted_properties',
                'groups',
                'targets_views',
                'jobs_builds',
            ],
            'credentials/credential_id' => [
                'targets'
            ],
            'targets/target_id' => [
                'targets_pools_targets',
                'jobs_releases',
            ],

            'jobs_builds/build_id' => [
                'jobs_releases'
            ],
            'jobs_releases/release_id' => [
                'targets'
            ],
            'targets_views/view_id' => [
                'targets_pools'
            ],
            'targets_pools/pool_id' => [
                'targets_pools_targets'
            ]
        ];
    }
}
