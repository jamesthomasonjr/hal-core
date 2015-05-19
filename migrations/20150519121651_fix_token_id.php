<?php

use Phinx\Migration\AbstractMigration;
use QL\Hal\Core\DatabaseMeta;

class FixTokenId extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->table(DatabaseMeta::DB_TOKEN)
            ->dropForeignKey('UserId')
            ->save();

        $this->table(DatabaseMeta::DB_TOKEN)
            ->changeColumn('TokenId', 'string', ['limit' => 32])
            ->changeColumn('UserId', 'integer', ['null' => false])
            ->save();

        $this->table(DatabaseMeta::DB_TOKEN)
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

    }
}
