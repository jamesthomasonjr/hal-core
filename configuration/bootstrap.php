<?php
/**
 * @copyright ©2014 Quicken Loans Inc. All rights reserved. Trade Secret,
 *    Confidential and Proprietary. Any dissemination outside of Quicken Loans
 *    is strictly prohibited.
 */

namespace QL\Hal\Bootstrap;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

$root = __DIR__ . '/../';
require_once $root . '/vendor/autoload.php';

// Set Timezone to UTC
ini_set('date.timezone', 'UTC');
date_default_timezone_set('UTC');
error_reporting(E_ALL | E_STRICT);

$container = new ContainerBuilder;
$builder = new YamlFileLoader($container, new FileLocator($root));
$builder->load('configuration/hal-core.yml');

$container->compile();

return $container;
