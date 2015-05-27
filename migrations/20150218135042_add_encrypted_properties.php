<?php

use Phinx\Migration\AbstractMigration;
use QL\Hal\Core\DatabaseMeta;

class AddEncryptedProperties extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        if ($this->hasTable(DatabaseMeta::TABLE_ENCRYPTED)) {
            return;
        }

        $table = $this->table(DatabaseMeta::TABLE_ENCRYPTED, [
            'id' => false,
            'primary_key' => 'EncryptedPropertyId'
        ]);

        $table
            ->addColumn('EncryptedPropertyId', 'string', ['limit' => 40])
            ->addColumn('EncryptedPropertyName', 'string', ['limit' => 64])
            ->addColumn('EncryptedPropertyData', 'text')

            ->addColumn('RepositoryId', 'integer')
            ->addColumn('EnvironmentId', 'integer', ['null' => true])

            ->addIndex(['EncryptedPropertyName', 'RepositoryId', 'EnvironmentId'], ['unique' => true])

            ->save();

        // Foreign Keys
        $table
            ->addForeignKey('RepositoryId', DatabaseMeta::DB_REPO, 'RepositoryId', [
                'delete' => 'CASCADE',
                'update'=> 'CASCADE'
            ])
            ->save();

        $table
            ->addForeignKey('EnvironmentId', DatabaseMeta::DB_ENVIRONMENT, 'EnvironmentId', [
                'delete' => 'CASCADE',
                'update'=> 'CASCADE'
            ])
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->dropTable(DatabaseMeta::TABLE_ENCRYPTED);
    }
}
