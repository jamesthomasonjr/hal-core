<?php

use Phinx\Migration\AbstractMigration;
use QL\Kraken\Core\DatabaseMeta;

class AddQksPropertiesToKrakenEnvironment extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table(DatabaseMeta::DB_ENVIRONMENT);

        $table
            ->addColumn('EnvironmentQKSServiceURL', 'string', ['limit' => 200, 'after' => 'EnvironmentConsulToken'])
            ->addColumn('EnvironmentQKSEncryptionKey', 'char', ['limit' => 6, 'after' => 'EnvironmentQKSServiceURL'])
            ->addColumn('EnvironmentQKSClientID', 'string', ['limit' => 100, 'after' => 'EnvironmentQKSServiceURL'])
            ->addColumn('EnvironmentQKSClientSecret', 'string', ['limit' => 200, 'after' => 'EnvironmentQKSClientID'])

            ->renameColumn('EnvironmentConsulServer', 'EnvironmentConsulServiceURL')
            ->changeColumn('EnvironmentConsulToken', 'string', ['limit' => 200])

            ->addIndex(['EnvironmentName'], ['unique' => true]);

        $table->save();


        $table = $this->table(DatabaseMeta::DB_TARGET);
        $table
            ->changeColumn('TargetEncryptionKey', 'char', ['limit' => 6]);
        $table->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $table = $this->table(DatabaseMeta::DB_ENVIRONMENT);
        $table
            ->removeColumn('EnvironmentQKSServiceURL')
            ->removeColumn('EnvironmentQKSEncryptionKey')
            ->removeColumn('EnvironmentQKSClientID')
            ->removeColumn('EnvironmentQKSClientSecret')

            ->renameColumn('EnvironmentConsulServiceURL', 'EnvironmentConsulServer')
            ->changeColumn('EnvironmentConsulToken', 'string', ['limit' => 100])

            ->removeIndex(['EnvironmentName']);

        $table->save();

        $table = $this->table(DatabaseMeta::DB_TARGET);
        $table
            ->changeColumn('TargetEncryptionKey', 'string', ['limit' => 100]);
        $table->save();

    }
}
