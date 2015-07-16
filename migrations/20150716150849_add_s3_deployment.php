<?php

use Phinx\Migration\AbstractMigration;
use QL\Hal\Core\DatabaseMeta;
use QL\Hal\Core\Type\EnumType\ServerEnum;

class AddS3Deployment extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table(DatabaseMeta::DB_DEPLOYMENT);
        $table
            ->changeColumn('DeploymentUrl', 'string', ['limit' => 200])
            ->changeColumn('DeploymentPath', 'string', ['limit' => 200, 'null' => true])

            ->addColumn('DeploymentName', 'string', ['after' => 'DeploymentId', 'limit' => 80])
            ->addColumn('DeploymentS3Bucket', 'string', ['after' => 'DeploymentEc2Pool', 'limit' => 100, 'null' => true])
            ->addColumn('DeploymentS3File', 'string', ['after' => 'DeploymentS3Bucket', 'limit' => 100, 'null' => true])
            ->addIndex(['DeploymentS3Bucket', 'DeploymentS3File'])
            ->save();

        $types = ServerEnum::values();

        $table = $this->table(DatabaseMeta::DB_SERVER);
        $table
            ->changeColumn('ServerType', 'enum', [
                'values' => $types,
                'default' => array_shift($types)
            ])
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $table = $this->table(DatabaseMeta::DB_DEPLOYMENT);
        $table
            ->changeColumn('DeploymentUrl', 'string', ['limit' => 255])
            ->changeColumn('DeploymentPath', 'string', ['limit' => 255])

            ->removeColumn('DeploymentName')
            ->removeColumn('DeploymentS3Bucket')
            ->removeColumn('DeploymentS3File')
            ->save();
    }
}
