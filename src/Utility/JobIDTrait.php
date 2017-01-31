<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Utility;

use Hal\Core\JobGenerator;

trait JobIDTrait
{
    /**
     * @var JobGenerator
     */
    private static $jobGenerator;

    /**
     * @return string
     */
    public function generateBuildID()
    {
        if (!self::$jobGenerator) {
            self::$jobGenerator = new JobGenerator(JobGenerator::BASE58, 5);
        }

        return self::$jobGenerator->generateBuildID();
    }

    /**
     * @return string
     */
    public function generateReleaseID()
    {
        if (!self::$jobGenerator) {
            self::$jobGenerator = new JobGenerator(JobGenerator::BASE58, 5);
        }

        return self::$jobGenerator->generateReleaseID();
    }
}
