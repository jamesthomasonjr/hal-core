<?php
/**
 * @copyright ©2015 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Kraken\Core;

class DatabaseMeta
{
    const DB_LOG_AUDIT = 'KrakenAuditLog';

    const DB_APPLICATION = 'KrakenApplication';
    const DB_ENVIRONMENT = 'KrakenEnvironment';
    const DB_TARGET = 'KrakenTarget';

    const DB_CONFIGURATION = 'KrakenConfiguration';
    const DB_SNAPSHOT = 'KrakenSnapshot';

    const DB_SCHEMA = 'KrakenSchema';
    const DB_PROPERTY = 'KrakenProperty';
}
