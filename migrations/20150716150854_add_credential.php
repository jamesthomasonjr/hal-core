<?php

use Phinx\Migration\AbstractMigration;
use QL\Hal\Core\DatabaseMeta;
use QL\Hal\Core\Type\EnumType\CredentialEnum;

class AddCredential extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $table = $this->table(DatabaseMeta::DB_CREDENTIAL, [
            'id' => false,
            'primary_key' => 'CredentialId'
        ]);

        $types = CredentialEnum::values();

        $table
            ->addColumn('CredentialId', 'char', ['limit' => 32])
            ->addColumn('CredentialType', 'enum', [
                'values' => $types,
                'default' => array_shift($types)
            ])
            ->addColumn('CredentialName', 'string', ['limit' => 100])

            ->addColumn('CredentialAWSKey', 'string', ['limit' => 100])
            // encrypted
            ->addColumn('CredentialAWSSecret', 'text')

            ->addColumn('CredentialPrivateKeyUsername', 'string', ['limit' => 100])
            ->addColumn('CredentialPrivateKeyPath', 'string', ['limit' => 200])

            // encrypted
            ->addColumn('CredentialPrivateKeyFile', 'text')
            ->save();

        $table = $this->table(DatabaseMeta::DB_DEPLOYMENT);
        $table
            ->addColumn('CredentialId', 'char', ['limit' => 32, 'null' => true])
            ->save();

        // fked
        $this->table(DatabaseMeta::DB_DEPLOYMENT)
            ->addForeignKey('CredentialId', DatabaseMeta::DB_CREDENTIAL, 'CredentialId', [
                'delete' => 'SET_NULL',
                'update'=> 'CASCADE'
            ])
            ->save();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        // de-fked
        $this->table(DatabaseMeta::DB_DEPLOYMENT)
            ->removeForeignKey('CredentialId')
            ->save();

        // tables
        $table = $this->table(DatabaseMeta::DB_DEPLOYMENT);
        $table
            ->removeColumn('CredentialId')
            ->save();

        $this->table(DatabaseMeta::DB_CREDENTIAL)
            ->drop();
    }
}
