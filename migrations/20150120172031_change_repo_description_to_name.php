<?php

use Phinx\Migration\AbstractMigration;

class ChangeRepoDescriptionToName extends AbstractMigration
{
    const TABLE_REPOSITORIES = 'Repositories';

    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->table(self::TABLE_REPOSITORIES)
            ->renameColumn('RepositoryDescription', 'RepositoryName')
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table(self::TABLE_REPOSITORIES)
            ->renameColumn('RepositoryName', 'RepositoryDescription')
            ->save();
    }
}
