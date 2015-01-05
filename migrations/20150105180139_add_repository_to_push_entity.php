<?php

use Phinx\Migration\AbstractMigration;

class AddRepositoryToPushEntity extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     *
    public function change()
    {
    }
    */

    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->updateTable();

        $select = <<<SQL
SELECT
    PushId, Builds.RepositoryId
FROM
    Pushes
LEFT JOIN Builds ON Builds.BuildId = Pushes.BuildId
WHERE
    Pushes.RepositoryId IS NULL
SQL;
        $insert = <<<SQL
UPDATE
    Pushes
SET
    RepositoryId = %d
WHERE
    PushId = %s
SQL;
        $pushes = $this->fetchAll($select);
        foreach ($pushes as $push) {
            // skip invalid repositories
            if (!$push['RepositoryId']) {
                continue;
            }

            $sql = sprintf(
                $insert,
                $push['RepositoryId'],
                $this->pdo()->quote($push['PushId'])
            );

            $this->execute($sql);
        }
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }

    private function updateTable()
    {
        // Add RepositoryId to Pushes
        $table = $this->table('Pushes');
        $table
            ->addColumn('RepositoryId', 'integer', ['after' => 'DeploymentId', 'null' => true, 'signed' => false])
            ->addIndex(['RepositoryId'])
            ->save();

        // Add foreign key for RepositoryId, must be separate query
        $table
            ->addForeignKey('RepositoryId', 'Repositories', 'RepositoryId', [
                'update'=> 'CASCADE',
                'delete' => 'SET_NULL'
            ])
            ->save();
    }

    private function pdo()
    {
        return $this->getAdapter()->getConnection();
    }
}
