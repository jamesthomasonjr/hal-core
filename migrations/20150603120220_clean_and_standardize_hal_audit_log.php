<?php

use Phinx\Migration\AbstractMigration;
use QL\Hal\Core\DatabaseMeta;
use QL\Hal\Core\RandomGenerator;

class CleanAndStandardizeHalAuditLog extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->table(DatabaseMeta::DB_LOG_AUDIT)
            ->changeColumn('AuditLogId', 'char', ['limit' => 32])
            ->renameColumn('Recorded', 'AuditLogCreated')
            ->renameColumn('Entity', 'AuditLogEntity')
            ->renameColumn('Action', 'AuditLogAction')
            ->renameColumn('Data', 'AuditLogData')
            ->save();

        $this->deleteBuildAndPushAudits();
        $this->updateAuditIds();
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->table(DatabaseMeta::DB_LOG_AUDIT)
            ->changeColumn('AuditLogId', 'integer', ['identity' => true])
            ->renameColumn('AuditLogCreated', 'Recorded')
            ->renameColumn('AuditLogEntity', 'Entity')
            ->renameColumn('AuditLogAction', 'Action')
            ->renameColumn('AuditLogData', 'Data')
            ->save();
    }

    private function deleteBuildAndPushAudits()
    {
        $deleteBuildAudit = <<<SQL
DELETE FROM AuditLogs
WHERE AuditLogEntity LIKE 'Build:%'
SQL;
        $deletePushAudit = <<<SQL
DELETE FROM AuditLogs
WHERE AuditLogEntity LIKE 'Push:%'
SQL;

        $this->execute($deleteBuildAudit);
        $this->execute($deletePushAudit);
    }

    private function updateAuditIds()
    {
        $select = <<<SQL
SELECT AuditLogId FROM AuditLogs
SQL;
        $insert = <<<SQL
UPDATE AuditLogs
   SET AuditLogId = %s
 WHERE AuditLogId = %s
SQL;

        $generator = new RandomGenerator;

        $audits = $this->fetchAll($select);
        foreach ($audits as $audit) {
            $id = $generator();
            $sql = sprintf(
                $insert,
                $this->pdo()->quote($id),
                $this->pdo()->quote($audit['AuditLogId'])
            );

            $msg = sprintf("[%s] <- <info>%s</info>", $id, $audit['AuditLogId']);
            $this->getOutput()->writeln($msg);

            $this->execute($sql);
        }
    }

    private function pdo()
    {
        return $this->getAdapter()->getConnection();
    }
}
