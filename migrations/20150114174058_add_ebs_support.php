<?php

use Phinx\Migration\AbstractMigration;
use QL\Hal\Core\Entity\Type\DeploymentEnumType;

class AddEbsSupport extends AbstractMigration
{
    const TABLE_DEPLOYMENTS = 'Deployments';
    const TABLE_REPOSITORIES = 'Repositories';

    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->updateDeployments();
        $this->updateRepositories();
    }

    public function updateDeployments()
    {
        $this->table(self::TABLE_DEPLOYMENTS)
            ->changeColumn('ServerId', 'integer', ['null' => true])
            ->addColumn('DeploymentEbsEnvironment', 'string', ['limit' => 100, 'after' => 'DeploymentUrl'])
            ->save();

        // Phinx (as of 0.4.1) does not support ENUM, so ENUM columns must be manually added
        $types = array_map(function($type) {
            return sprintf("'%s'", $type);
        }, DeploymentEnumType::values());

        $tbl = self::TABLE_DEPLOYMENTS;
        $default = reset($types);
        $types = implode(", ", $types);

        $this->execute("
ALTER TABLE $tbl
ADD COLUMN
    DeploymentType ENUM($types) NOT NULL DEFAULT $default
    AFTER DeploymentId
");
    }

    public function updateRepositories()
    {
        $this->table(self::TABLE_REPOSITORIES)
            ->addColumn('RepositoryEbsName', 'string', ['limit' => 255, 'after' => 'RepositoryPostPushCmd'])

            // make cmds not null
            ->changeColumn('RepositoryBuildCmd', 'string', ['limit' => 255])
            ->changeColumn('RepositoryBuildTransformCmd', 'string', ['limit' => 255])
            ->changeColumn('RepositoryPrePushCmd', 'string', ['limit' => 255])
            ->changeColumn('RepositoryPostPushCmd', 'string', ['limit' => 255])

            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table(self::TABLE_DEPLOYMENTS)
            ->removeColumn('DeploymentType')
            ->removeColumn('DeploymentEbsEnvironment')
            ->save();

        $this->table(self::TABLE_REPOSITORIES)
            ->removeColumn('RepositoryEbsName')
            ->save();
    }
}
