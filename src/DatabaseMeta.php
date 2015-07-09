<?php
/**
 * @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Core;

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

    const DB_USER = 'Users';
    const DB_USER_TYPE = 'UserTypes';
    const DB_USER_PERMISSION = 'UserPermissions';
    const DB_TOKEN = 'Tokens';

    const DB_DEPLOYMENT_POOL = 'DeploymentPools';
    const DB_DEPLOYMENT_VIEW = 'DeploymentViews';
}
