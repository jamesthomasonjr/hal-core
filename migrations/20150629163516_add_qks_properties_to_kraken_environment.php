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
            ->addColumn('EnvironmentQKSClientSecret', 'string', ['limit' => 100, 'after' => 'EnvironmentQKSClientID'])

            ->renameColumn('EnvironmentConsulServer', 'EnvironmentConsulServiceURL')
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
            ->removeIndex(['EnvironmentName']);

        $table->save();
    }
}
