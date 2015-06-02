<?php

use Phinx\Migration\AbstractMigration;
use QL\Hal\Core\DatabaseMeta;
use QL\Hal\Core\Type\EnumType\UserTypeEnum;

class AddUserPermissions extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        if ($this->hasTable(DatabaseMeta::DB_USER_TYPE)) {
            return;
        }

        // add UserTypes
        $typesTable = $this->table(DatabaseMeta::DB_USER_TYPE, [
            'id' => false,
            'primary_key' => 'UserTypeId'
        ]);

        $typesTable
            ->addColumn('UserTypeId', 'char', ['limit' => 32])
            ->addColumn('UserType', 'enum', [
                'values' => UserTypeEnum::values(),
                'default' => UserTypeEnum::TYPE_NORMAL
            ])
            ->addColumn('UserId', 'integer')
            ->addColumn('ApplicationId', 'integer', ['null' => true])

            // indexes
            ->addIndex(['UserId'])
            ->addIndex(['ApplicationId'])

            ->save();

        // add UserPermissions
        $permissionsTable = $this->table(DatabaseMeta::DB_USER_PERMISSION, [
            'id' => false,
            'primary_key' => 'UserPermissionId'
        ]);

        $permissionsTable
            ->addColumn('UserPermissionId', 'char', ['limit' => 32])
            ->addColumn('UserPermissionIsProduction', 'boolean', ['default' => false])
            ->addColumn('UserId', 'integer')
            ->addColumn('ApplicationId', 'integer')

            // indexes
            ->addIndex(['UserId'])
            ->addIndex(['ApplicationId'])

            ->save();

        // add FKs
        $typesTable
            ->addForeignKey('ApplicationId', DatabaseMeta::DB_REPO, 'RepositoryId', [
                'delete' => 'CASCADE',
                'update'=> 'CASCADE'
            ])
            ->addForeignKey('UserId', DatabaseMeta::DB_USER, 'UserId', [
                'delete' => 'CASCADE',
                'update'=> 'CASCADE'
            ])
            ->save();

        $permissionsTable
            ->addForeignKey('ApplicationId', DatabaseMeta::DB_REPO, 'RepositoryId', [
                'delete' => 'CASCADE',
                'update'=> 'CASCADE'
            ])
            ->addForeignKey('UserId', DatabaseMeta::DB_USER, 'UserId', [
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
        $this->dropTable(DatabaseMeta::DB_USER_TYPE);
        $this->dropTable(DatabaseMeta::DB_USER_PERMISSION);
    }
}
