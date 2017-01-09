<?php

use Phinx\Migration\AbstractMigration;
use QL\Hal\Core\DatabaseMeta;

class RemoveDeploymentUniquenessIndex extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table(DatabaseMeta::DB_DEPLOYMENT)
            ->removeIndex(['ServerId', 'DeploymentPath'])
            ->save();

        $this->table(DatabaseMeta::DB_DEPLOYMENT)
            ->addIndex(['ServerId', 'DeploymentPath']);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $table = $this->table(DatabaseMeta::DB_DEPLOYMENT)
            ->removeIndex(['ServerId', 'DeploymentPath'])
            ->save();

        $this->table(DatabaseMeta::DB_DEPLOYMENT)
            ->addIndex(['ServerId', 'DeploymentPath'], ['unique' => true])
            ->save();
    }
}
