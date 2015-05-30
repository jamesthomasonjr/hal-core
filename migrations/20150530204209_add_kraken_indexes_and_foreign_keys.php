<?php

use Phinx\Migration\AbstractMigration;
use QL\Hal\Core\DatabaseMeta as HalDatabaseMeta;
use QL\Kraken\Core\DatabaseMeta;

class AddKrakenIndexesAndForeignKeys extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        // add indexes
        $this->addIndexes();

        // add foreign keys
        $this->addForeignKeys();
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
        $this->table(DatabaseMeta::DB_LOG_AUDIT)
            ->addIndex(['AuditLogEntity'])
            ->save();

        $this->table(DatabaseMeta::DB_SCHEMA)
            ->addIndex(['ApplicationID', 'SchemaKey'], ['unique' => true])
            ->save();

        $this->table(DatabaseMeta::DB_SNAPSHOT)
            ->addIndex(['SnapshotKey'])
            ->save();
    }

    private function addForeignKeys()
    {
        $this->table(DatabaseMeta::DB_LOG_AUDIT)
            ->addForeignKey('UserID', HalDatabaseMeta::DB_USER, 'UserId', $this->fkRestrict())
            ->save();

        $this->table(DatabaseMeta::DB_APPLICATION)
            ->addForeignKey('HalApplicationID', HalDatabaseMeta::DB_REPO, 'RepositoryId', $this->fkNullable())
            ->save();

        $this->table(DatabaseMeta::DB_TARGET)
            ->addForeignKey('ApplicationID', DatabaseMeta::DB_APPLICATION, 'ApplicationID', $this->fkCascade())
            ->addForeignKey('EnvironmentID', DatabaseMeta::DB_ENVIRONMENT, 'EnvironmentID', $this->fkCascade())
            ->addForeignKey('ConfigurationID', DatabaseMeta::DB_CONFIGURATION, 'ConfigurationID', $this->fkNullable())
            ->save();

        // schema + property
        $this->table(DatabaseMeta::DB_SCHEMA)
            ->addForeignKey('ApplicationID', DatabaseMeta::DB_APPLICATION, 'ApplicationID', $this->fkRestrict())
            ->addForeignKey('UserID', HalDatabaseMeta::DB_USER, 'UserId', $this->fkRestrict())
            ->save();

        $this->table(DatabaseMeta::DB_PROPERTY)
            ->addForeignKey('SchemaID', DatabaseMeta::DB_SCHEMA, 'SchemaID', $this->fkCascade())
            ->addForeignKey('ApplicationID', DatabaseMeta::DB_APPLICATION, 'ApplicationID', $this->fkRestrict())
            ->addForeignKey('EnvironmentID', DatabaseMeta::DB_ENVIRONMENT, 'EnvironmentID', $this->fkRestrict())
            ->addForeignKey('UserID', HalDatabaseMeta::DB_USER, 'UserId', $this->fkRestrict())
            ->save();

        // configuration + snapshot
        $this->table(DatabaseMeta::DB_CONFIGURATION)
            ->addForeignKey('ApplicationID', DatabaseMeta::DB_APPLICATION, 'ApplicationID', $this->fkRestrict())
            ->addForeignKey('EnvironmentID', DatabaseMeta::DB_ENVIRONMENT, 'EnvironmentID', $this->fkRestrict())
            ->addForeignKey('UserID', HalDatabaseMeta::DB_USER, 'UserId', $this->fkRestrict())
            ->save();

        $this->table(DatabaseMeta::DB_SNAPSHOT)
            ->addForeignKey('ConfigurationID', DatabaseMeta::DB_CONFIGURATION, 'ConfigurationID', $this->fkCascade())
            ->addForeignKey('PropertyID', DatabaseMeta::DB_PROPERTY, 'PropertyID', $this->fkNullable())
            ->addForeignKey('SchemaID', DatabaseMeta::DB_SCHEMA, 'SchemaID', $this->fkNullable())
            ->save();
    }

    private function removeIndexes()
    {
        $this->table(DatabaseMeta::DB_LOG_AUDIT)
            ->removeIndex(['AuditLogEntity'])
            ->save();

        $this->table(DatabaseMeta::DB_SCHEMA)
            ->removeIndex(['ApplicationID', 'SchemaKey'])
            ->save();

        $this->table(DatabaseMeta::DB_SNAPSHOT)
            ->removeIndex(['SnapshotKey'])
            ->save();
    }

    private function removeForeignKeys()
    {
        $this->table(DatabaseMeta::DB_LOG_AUDIT)
            ->dropForeignKey('UserID')
            ->save();

        $this->table(DatabaseMeta::DB_APPLICATION)
            ->dropForeignKey('HalApplicationID')
            ->save();

        $this->table(DatabaseMeta::DB_TARGET)
            ->dropForeignKey('ApplicationID')
            ->dropForeignKey('EnvironmentID')
            ->dropForeignKey('ConfigurationID')
            ->save();

        $this->table(DatabaseMeta::DB_SCHEMA)
            ->dropForeignKey('ApplicationID')
            ->dropForeignKey('UserID')
            ->save();
        $this->table(DatabaseMeta::DB_PROPERTY)
            ->dropForeignKey('SchemaID')
            ->dropForeignKey('ApplicationID')
            ->dropForeignKey('EnvironmentID')
            ->dropForeignKey('UserID')
            ->save();

        $this->table(DatabaseMeta::DB_CONFIGURATION)
            ->dropForeignKey('ApplicationID')
            ->dropForeignKey('EnvironmentID')
            ->dropForeignKey('UserID')
            ->save();
        $this->table(DatabaseMeta::DB_SNAPSHOT)
            ->dropForeignKey('ConfigurationID')
            ->dropForeignKey('PropertyID')
            ->dropForeignKey('SchemaID')
            ->save();
    }

    private function fkRestrict()
    {
        return $this->fkSettings('RESTRICT');
    }

    private function fkCascade()
    {
        return $this->fkSettings('CASCADE');
    }

    private function fkNullable()
    {
        return $this->fkSettings('SET_NULL');
    }

    private function fkSettings($delete)
    {
        return [
            'delete' => $delete,
            'update'=> 'CASCADE'
        ];
    }
}
