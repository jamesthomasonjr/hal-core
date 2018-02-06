<?php

use Hal\Core\Database\PhinxMigration;
if (!class_exists(PhinxMigration::class)) { require_once __DIR__ . '/../src/Database/PhinxMigration.php'; }

class SeparateIdentitiesFromUsers extends PhinxMigration
{
    public function up()
    {
        $this->createIdentitiesTable();
        $this->copyUsersToIdentitiesTable();
        $this->removeIdentityInformationFromUsersTable();
    }

    public function down()
    {
        $this->addIdentityColumnsBackToUsersTable();
        $this->copyIdentityInformationToUsersTable();
        $this->reenableConstraintsForUsersTable();
        $this->dropTable('identities');
    }

    private function createIdentitiesTable()
    {
        $this->createUUIDTable('identities')
            ->addColumn('created',            'datetime',     [])
            ->addColumn('user_id',            'string',       [])
            ->addColumn('provider_unique_id', 'string',       ['limit' => 100])
            ->addColumn('provider_id',        'uuid',         ['null' => false])
            ->addColumn('parameters',         'json',         [])
            ->addColumn('token_parameters',   'json',         ['null' => true])

            ->addIndex(['provider_unique_id', 'provider_id'], ['unique' => true])

            ->addForeignKey('provider_id',   'system_identity_providers',  'id')
            ->addForeignKey('user_id',       'users',                      'id')

            ->save();
    }

    private function copyUsersToIdentitiesTable()
    {
        $users = $this->fetchAll('SELECT * FROM users');

        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'id' => $user['id'],
                'created' => $user['created'],
                'user_id' => $user['id'],
                'parameters' => $user['parameters'],
                'provider_id' => $user['provider_id'],
                'provider_unique_id' => $user['provider_unique_id']
            ];
        }

        $this->table('identities')
            ->insert($data)
            ->saveData();
    }

    private function copyIdentityInformationToUsersTable()
    {
        $pdo = $this->getAdapter()->getConnection();
        $identities = $this->fetchAll('SELECT * FROM identities');

        foreach ($identities as $identity) {
            // Is there a way to do this with phinx?
            $statement = $pdo->prepare('UPDATE `users` SET `provider_id` = :provider_id, `provider_unique_id` = :provider_unique_id, `parameters` = :parameters WHERE `id` = :id;');
            $statement->bindParam(':provider_id', $identity['provider_id']);
            $statement->bindParam(':provider_unique_id', $identity['provider_unique_id']);
            $statement->bindParam(':parameters', $identity['parameters']);
            $statement->bindParam(':id', $identity['id']);
            $statement->execute();
        }
    }

    private function removeIdentityInformationFromUsersTable()
    {
        $users = $this->table('users');

        $users
            ->dropForeignKey('provider_unique_id')
            ->dropForeignKey('provider_id')
            ->removeIndex(['provider_unique_id', 'provider_id'])
            ->save();

        $users
            ->removeColumn('parameters')
            ->removeColumn('provider_unique_id')
            ->removeColumn('provider_id')
            ->save();
    }

    private function addIdentityColumnsBackToUsersTable()
    {
        $this->table('users')
            ->addColumn('parameters',         'json',     [])
            ->addColumn('provider_unique_id', 'string',   ['limit' => 100])
            ->addColumn('provider_id',        'uuid',     [])
            ->save();
    }

    private function reenableConstraintsForUsersTable()
    {
        $this->table('users')
            ->changeColumn('provider_id',      'uuid',    ['null' => false])
            ->addIndex(['provider_unique_id', 'provider_id'], ['unique' => true])
            ->addForeignKey('provider_id', 'system_identity_providers', 'id')
            ->save();
    }
}
