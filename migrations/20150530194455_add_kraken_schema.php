<?php

use Phinx\Db\Adapter\MysqlAdapter;
use Phinx\Migration\AbstractMigration;
use QL\Kraken\Core\DatabaseMeta;
use QL\Kraken\Core\Type\EnumType\PropertyEnum;

class AddKrakenSchema extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->createAuditLog(DatabaseMeta::DB_LOG_AUDIT);

        $this->createApplication(DatabaseMeta::DB_APPLICATION);
        $this->createEnvironment(DatabaseMeta::DB_ENVIRONMENT);
        $this->createTarget(DatabaseMeta::DB_TARGET);

        $this->createSchema(DatabaseMeta::DB_SCHEMA);
        $this->createProperty(DatabaseMeta::DB_PROPERTY);

        $this->createConfiguration(DatabaseMeta::DB_CONFIGURATION);
        $this->createSnapshot(DatabaseMeta::DB_SNAPSHOT);
    }

    /**
     * WARNING! This drops all tables in the database! Use only if you want to reset your entire environment!
     *
     * Migrate down.
     */
    public function down()
    {
        $this->table(DatabaseMeta::DB_LOG_AUDIT)->drop();

        $this->table(DatabaseMeta::DB_SNAPSHOT)->drop();
        $this->table(DatabaseMeta::DB_CONFIGURATION)->drop();

        $this->table(DatabaseMeta::DB_PROPERTY)->drop();
        $this->table(DatabaseMeta::DB_SCHEMA)->drop();

        $this->table(DatabaseMeta::DB_TARGET)->drop();
        $this->table(DatabaseMeta::DB_APPLICATION)->drop();
        $this->table(DatabaseMeta::DB_ENVIRONMENT)->drop();
    }

    private function createAuditLog($table)
    {
        if ($this->hasTable($table)) {
            return;
        }

        $table = $this->table($table, [
            'id' => false,
            'primary_key' => 'AuditLogID'
        ]);

        $table
            ->addColumn('AuditLogID', 'char', ['limit' => 32])
            ->addColumn('AuditLogCreated', 'datetime')

            ->addColumn('AuditLogEntity', 'string', ['limit' => 100])
            ->addColumn('AuditLogKey', 'string', ['limit' => 150])
            ->addColumn('AuditLogAction', 'string', ['limit' => 20])
            ->addColumn('AuditLogData', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR, 'null' => true])

            ->addColumn('UserID', 'integer')
            ->addColumn('ApplicationID', 'char', ['limit' => 32, 'null' => true])

            ->save();
    }

    private function createApplication($table)
    {
        if ($this->hasTable($table)) {
            return;
        }

        $table = $this->table($table, [
            'id' => false,
            'primary_key' => 'ApplicationID'
        ]);

        $table
            ->addColumn('ApplicationID', 'char', ['limit' => 32])

            ->addColumn('ApplicationCoreID', 'string', ['limit' => 32])
            ->addColumn('ApplicationName', 'string', ['limit' => 64])

            ->addColumn('HalApplicationID', 'integer', ['null' => true])

            ->save();
    }

    private function createEnvironment($table)
    {
        if ($this->hasTable($table)) {
            return;
        }

        $table = $this->table($table, [
            'id' => false,
            'primary_key' => 'EnvironmentID'
        ]);

        $table
            ->addColumn('EnvironmentID', 'char', ['limit' => 32])

            ->addColumn('EnvironmentName', 'string', ['limit' => 40])
            ->addColumn('EnvironmentConsulServer', 'string', ['limit' => 200])
            ->addColumn('EnvironmentConsulToken', 'string', ['limit' => 100])
            ->addColumn('EnvironmentIsProduction', 'boolean', ['default' => false])

            ->save();
    }

    private function createTarget($table)
    {
        if ($this->hasTable($table)) {
            return;
        }

        $table = $this->table($table, [
            'id' => false,
            'primary_key' => 'TargetID'
        ]);

        $table
            ->addColumn('TargetID', 'char', ['limit' => 32])

            ->addColumn('TargetEncryptionKey', 'string', ['limit' => 100])

            ->addColumn('ApplicationID', 'char', ['limit' => 32])
            ->addColumn('EnvironmentID', 'char', ['limit' => 32])
            ->addColumn('ConfigurationID', 'char', ['limit' => 32, 'null' => true])

            ->save();
    }

    private function createSchema($table)
    {
        if ($this->hasTable($table)) {
            return;
        }

        $table = $this->table($table, [
            'id' => false,
            'primary_key' => 'SchemaID'
        ]);

        $table
            ->addColumn('SchemaID', 'char', ['limit' => 32])

            ->addColumn('SchemaKey', 'string', ['limit' => 150])
            ->addColumn('SchemaDataType', 'enum', [
                'values' => PropertyEnum::values(),
                'default' => PropertyEnum::TYPE_STRING
            ])
            ->addColumn('SchemaIsSecure', 'boolean', ['default' => false])
            ->addColumn('SchemaDescription', 'string', ['limit' => 250])
            ->addColumn('SchemaCreated', 'datetime')

            ->addColumn('ApplicationID', 'char', ['limit' => 32])
            ->addColumn('UserID', 'integer')

            ->save();
    }

    private function createProperty($table)
    {
        if ($this->hasTable($table)) {
            return;
        }

        $table = $this->table($table, [
            'id' => false,
            'primary_key' => 'PropertyID'
        ]);

        $table
            ->addColumn('PropertyID', 'char', ['limit' => 32])

            ->addColumn('PropertyValue', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR, 'null' => true])
            ->addColumn('PropertyCreated', 'datetime')

            ->addColumn('SchemaID', 'char', ['limit' => 32])
            ->addColumn('ApplicationID', 'char', ['limit' => 32])
            ->addColumn('EnvironmentID', 'char', ['limit' => 32])
            ->addColumn('UserID', 'integer')

            ->save();
    }

    private function createConfiguration($table)
    {
        if ($this->hasTable($table)) {
            return;
        }

        $table = $this->table($table, [
            'id' => false,
            'primary_key' => 'ConfigurationID'
        ]);

        $table
            ->addColumn('ConfigurationID', 'char', ['limit' => 32])

            ->addColumn('ConfigurationStatus', 'boolean', ['default' => false])
            ->addColumn('ConfigurationAudit', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR, 'null' => true])
            ->addColumn('ConfigurationCreated', 'datetime')

            ->addColumn('ApplicationID', 'char', ['limit' => 32])
            ->addColumn('EnvironmentID', 'char', ['limit' => 32])
            ->addColumn('UserID', 'integer')

            ->save();
    }

    private function createSnapshot($table)
    {
        if ($this->hasTable($table)) {
            return;
        }

        $table = $this->table($table, [
            'id' => false,
            'primary_key' => 'SnapshotID'
        ]);

        $table
            ->addColumn('SnapshotID', 'char', ['limit' => 32])

            ->addColumn('SnapshotKey', 'string', ['limit' => 150])
            ->addColumn('SnapshotDataType', 'enum', [
                'values' => PropertyEnum::values(),
                'default' => PropertyEnum::TYPE_STRING
            ])
            ->addColumn('SnapshotIsSecure', 'boolean', ['default' => false])

            ->addColumn('SnapshotValue', 'text', ['limit' => MysqlAdapter::TEXT_REGULAR, 'null' => true])
            ->addColumn('SnapshotChecksum', 'char', ['limit' => 40])
            ->addColumn('SnapshotCreated', 'datetime', ['null' => true])

            ->addColumn('ConfigurationID', 'char', ['limit' => 32])
            ->addColumn('PropertyID', 'char', ['limit' => 32, 'null' => true])
            ->addColumn('SchemaID', 'char', ['limit' => 32, 'null' => true])

            ->save();
    }
}
