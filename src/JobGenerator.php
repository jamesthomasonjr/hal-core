<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core;

use InvalidArgumentException;

/**
 * Unique ID Generator
 *
 * Generate random build and deploy IDs
 */
class JobGenerator
{
    const BASE58 = '123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ';

    const BASE58_3CHAR = 3364;
    const BASE58_4CHAR = 195112;
    const BASE58_5CHAR = 11316496;
    const BASE58_6CHAR = 656356768;

    /**
     * @var int
     */
    private $fixedSize;

    /**
     * @var array
     */
    private $alphabet;

    /**
     * @var int
     */
    private $base;

    /**
     * @param string $version
     * @param string $alphabet
     * @param int $fixedSize
     */
    public function __construct($alphabet, $fixedSize = 5)
    {
        $this->fixedSize = $fixedSize;

        $this->alphabet = str_split($alphabet);
        $this->base = count($this->alphabet);

        if ($this->base < 2) {
            throw new InvalidArgumentException('You must use an alphabet with more than 1 character.');
        }

        if ($fixedSize < 3 || $fixedSize > 6) {
            throw new InvalidArgumentException('Randomized hash must be between 3 and 6 characters.');
        }
    }

    /**
     * Template:
     *     b{TIME}{UNIQUE_OF_FIXED_SIZE}
     * Example:
     *     b111155555
     *
     * @return string
     */
    public function generateBuildID()
    {
        $date = $this->timeHash();
        $unique = $this->randomHash($this->fixedSize);

        return sprintf(
            'b%s%s',
            $this->encode($date),
            $this->encode($unique)
        );
    }

    /**
     * Template:
     *     r{TIME}{UNIQUE_OF_FIXED_SIZE}
     * Example:
     *     r111155555
     *
     * @return string
     */
    public function generateReleaseID()
    {
        $date = $this->timeHash();
        $unique = $this->randomHash($this->fixedSize);

        return sprintf(
            'r%s%s',
            $this->encode($date),
            $this->encode($unique)
        );
    }

    /**
     * Encode a number to an arbitrary base.
     *
     * @param int $num
     * @return string
     */
    public function encode($num)
    {
        $encoded = '';
        while ($num > 0) {
            $encoded = $this->alphabet[$num % $this->base] . $encoded;
            $num = floor($num / $this->base);
        }

        return $encoded;
    }

    /**
     * Get a random number that will hash to a certain size in base 58.
     *
     * How a base10 int will hash to base58:
     *
     *      3364 - min 3 char
     *    195112 - min 4 char
     *  11316496 - min 5 char
     * 656356768 - min 6 char
     *
     * 3 char =     191 748 possibilities
     * 4 char =  11 121 384 possibilities
     * 5 char = 645 040 272 possibilities
     *
     * @param int $numChars
     *
     * @return int
     */
    protected function randomHash($numChars)
    {
        if ($numChars == 3) {
            return mt_rand(self::BASE58_3CHAR, self::BASE58_4CHAR - 1);

        } elseif ($numChars == 4) {
            return mt_rand(self::BASE58_4CHAR, self::BASE58_5CHAR - 1);

        } elseif ($numChars == 5) {
            return mt_rand(self::BASE58_5CHAR, self::BASE58_6CHAR - 1);

        } elseif ($numChars == 6) {
            return mt_rand(self::BASE58_6CHAR, mt_getrandmax());
        }

        return mt_rand(0, mt_getrandmax());
    }

    /**
     * Get a number based on year and date.
     *
     * For a consistent prefix that increments and can be used to easily find builds from a certain time set.
     *
     * The 4 digit year is used so it will consistently hash to 4 characters in base 58.
     *
     * @return int
     */
    protected function timeHash()
    {
        $day = date('Y') . str_pad(date('z'), 3, '0', STR_PAD_LEFT);
        return (int) $day;
    }
}
