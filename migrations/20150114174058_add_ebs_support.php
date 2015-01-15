<?php

use Phinx\Migration\AbstractMigration;
use QL\Hal\Core\Entity\Type\ServerEnumType;

class AddEbsSupport extends AbstractMigration
{
    const TABLE_SERVERS = 'Servers';
    const TABLE_REPOSITORIES = 'Repositories';

    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->updateServers();
        $this->updateRepositories();
    }

    public function updateServers()
    {
        // Phinx (as of 0.4.1) does not support ENUM, so ENUM columns must be manually added
        $types = array_map(function($type) {
            return sprintf("'%s'", $type);
        }, ServerEnumType::values());

        $tbl = self::TABLE_SERVERS;
        $default = reset($types);
        $types = implode(", ", $types);

        $this->execute("
ALTER TABLE $tbl
ADD COLUMN
    ServerType ENUM($types) NOT NULL DEFAULT $default
    AFTER ServerId
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
        $this->table(self::TABLE_SERVERS)
            ->removeColumn('ServerType')
            ->save();

        $this->table(self::TABLE_REPOSITORIES)
            ->removeColumn('RepositoryEbsName')
            ->save();
    }
}
