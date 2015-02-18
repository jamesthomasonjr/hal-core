<?php

use Phinx\Migration\AbstractMigration;

class AddEncryptedProperties extends AbstractMigration
{
    const TABLE_ENCRYPTED = 'EncryptedProperties';
    const TABLE_REPOSITORIES = 'Repositories';
    const TABLE_ENVIRONMENTS = 'Environments';

    /**
     * Migrate Up.
     */
    public function up()
    {
        if ($this->hasTable(self::TABLE_ENCRYPTED)) {
            return;
        }

        $table = $this->table(self::TABLE_ENCRYPTED, [
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
            ->addForeignKey('RepositoryId', self::TABLE_REPOSITORIES, 'RepositoryId', [
                'delete' => 'CASCADE',
                'update'=> 'CASCADE'
            ])
            ->save();

        $table
            ->addForeignKey('EnvironmentId', self::TABLE_ENVIRONMENTS, 'EnvironmentId', [
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
        $this->dropTable(self::TABLE_ENCRYPTED);
    }
}
