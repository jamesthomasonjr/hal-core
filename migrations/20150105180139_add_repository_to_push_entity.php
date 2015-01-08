<?php

use Phinx\Migration\AbstractMigration;

class AddRepositoryToPushEntity extends AbstractMigration
{
    const TABLE_PUSHES = 'Pushes';
    const TABLE_REPOSITORIES = 'Repositories';

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

    private function updateTable()
    {
        // Add RepositoryId to Pushes
        $this->table(self::TABLE_PUSHES)
            ->addColumn('RepositoryId', 'integer', ['after' => 'DeploymentId', 'null' => true])
            ->addIndex(['RepositoryId'])
            ->save();

        // Add foreign key for RepositoryId, must be separate query
        $this->table(self::TABLE_PUSHES)
            ->addForeignKey('RepositoryId', self::TABLE_REPOSITORIES, 'RepositoryId', [
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
