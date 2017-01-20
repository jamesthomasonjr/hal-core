<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core;

class DatabaseMeta
{
    const DB_LOG_AUDIT = 'AuditLogs';
    const DB_LOG_EVENT = 'EventLogs';

    const DB_GROUP = 'Groups';
    const DB_REPO = 'Repositories';
    const DB_ENCRYPTED = 'EncryptedProperties';

    const DB_ENVIRONMENT = 'Environments';
    const DB_SERVER = 'Servers';
    const DB_DEPLOYMENT = 'Deployments';

    const DB_BUILD = 'Builds';
    const DB_PUSH = 'Pushes';
    const DB_PROCESS = 'Processes';

    const DB_USER = 'Users';
    const DB_USER_TYPE = 'UserTypes';
    const DB_USER_PERMISSION = 'UserPermissions';
    const DB_USER_SETTING = 'UserSettings';
    const DB_TOKEN = 'Tokens';

    const DB_CREDENTIAL = 'Credentials';

    const DB_DEPLOYMENT_VIEW = 'DeploymentViews';
    const DB_DEPLOYMENT_POOL = 'DeploymentPools';
    const DB_DEPLOYMENT_POOL_M2M = 'DeploymentPools_Deployments';
}
