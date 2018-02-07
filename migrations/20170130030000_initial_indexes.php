<?php

use Hal\Core\Database\PhinxMigration;
if (!class_exists(PhinxMigration::class)) { require_once __DIR__ . '/../src/Database/PhinxMigration.php'; }

class InitialIndexes extends PhinxMigration
{
    public function change()
    {
        // Handle unique columns
        foreach ($this->uniqueColumns() as $table => $columns) {
            $table = $this->table($table);

            foreach ($columns as $column) {
                $table = $table->addIndex($column, ['unique' => true]);
            }

            $table->update();
        }

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

    protected function uniqueColumns()
    {
        return [
            'users_identities' =>    [['provider_unique_id', 'provider_id']],
            // 'environments' =>     ['name'],
        ];
    }

    protected function searchableColumns()
    {
        return [
            'users_tokens' =>       ['value'],
            'users_permissions' =>  ['permission_type'],

            'system_settings' =>    ['name'],
            'audit_events' =>       ['created'],

            'jobs' =>               ['job_type', 'job_status', 'created'],
            'jobs_builds' =>        ['code_reference', 'code_commit_sha'],
            'jobs_events' =>        ['created', 'event_order'],
            'jobs_meta' =>          ['name'],

            'scheduled_actions' =>  ['status', 'created']
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
                'users_identities',
                'users_permissions',
                'users_tokens',
                'jobs',
                'scheduled_actions'
            ],
            'applications/application_id' => [
                'users_permissions',
                'encrypted_properties',
                'targets',
                'targets_templates',
                'jobs_builds',
                'jobs_releases',
                'credentials',
            ],
            'organizations/organization_id' => [
                'users_permissions',
                'applications',
                'encrypted_properties',
                'credentials',
            ],
            'environments/environment_id' => [
                'users_permissions',
                'encrypted_properties',
                'targets',
                'targets_templates',
                'jobs_builds',
                'jobs_releases',
                'credentials',
            ],
            'credentials/credential_id' => [
                'targets',
            ],
            'targets/target_id' => [
                'jobs_releases'
            ],

            'targets_templates/template_id' => [
                'targets'
            ],

            'jobs/job_id' => [
                'jobs_events',
                'jobs_meta',
                'jobs_artifacts'
            ],
            'jobs/trigger_job_id' => [
                'scheduled_actions'
            ],
            'jobs/scheduled_job_id' => [
                'scheduled_actions'
            ],
            'jobs/last_job_id' => [
                'targets'
            ],

            'jobs_builds/build_id' => [
                'jobs_releases',
            ],

            'system_identity_providers/provider_id' => [
                'users_identities',
            ],
            'system_vcs_providers/provider_id' => [
                'applications',
            ],
        ];
    }
}
