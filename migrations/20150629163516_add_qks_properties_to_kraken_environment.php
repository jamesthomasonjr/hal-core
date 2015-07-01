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
            ->addColumn('EnvironmentQKSClientID', 'string', ['limit' => 100, 'after' => 'EnvironmentQKSServiceURL'])
            ->addColumn('EnvironmentQKSClientSecret', 'string', ['limit' => 200, 'after' => 'EnvironmentQKSClientID'])

            ->renameColumn('EnvironmentConsulServer', 'EnvironmentConsulServiceURL')
            ->changeColumn('EnvironmentConsulToken', 'string', ['limit' => 200])

            ->addIndex(['EnvironmentName'], ['unique' => true]);

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
            ->removeColumn('EnvironmentQKSClientID')
            ->removeColumn('EnvironmentQKSClientSecret')

            ->renameColumn('EnvironmentConsulServiceURL', 'EnvironmentConsulServer')
            ->changeColumn('EnvironmentConsulToken', 'string', ['limit' => 100])

            ->removeIndex(['EnvironmentName']);

        $table->save();
    }
}
