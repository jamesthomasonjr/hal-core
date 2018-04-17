<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Database\DoctrineUtility;

use Doctrine\Common\Cache\Cache;
use Doctrine\Common\Cache\CacheProvider;
use Predis\Client as Predis;
use Predis\Collection\Iterator\Keyspace;

class DoctrinePredisCache extends CacheProvider
{
    const DEFAULT_DELIMITER = ':';
    const NAMESPACE = 'doctrine';

    /**
     * @var Predis
     */
    private $redis;

    /**
     * @var int
     */
    private $defaultTTL;

    /**
     * @var string
     */
    private $keyPattern;

    /**
     * @param Predis $redis
     * @param int $defaultTTL
     * @param string $keyDelimiter
     */
    public function __construct(Predis $redis, int $defaultTTL, $keyDelimiter = self::DEFAULT_DELIMITER)
    {
        $this->redis = $redis;
        $this->defaultTTL = $defaultTTL;

        $this->keyPattern = self::NAMESPACE . $keyDelimiter . '%s';
    }

    /**
     * {@inheritdoc}
     */
    protected function doFetch($id)
    {
        $key = sprintf($this->keyPattern, $id);

        if (!$cached = $this->redis->get($key)) {
            return false;
        }

        return unserialize($cached);
    }

    /**
     * {@inheritdoc}
     */
    protected function doContains($id)
    {
        $key = sprintf($this->keyPattern, $id);

        return (bool) $this->redis->exists($id);
    }

    /**
     * {@inheritdoc}
     */
    protected function doSave($id, $data, $lifeTime = 0)
    {
        $key = sprintf($this->keyPattern, $id);

        $ttl = ($lifeTime > 0) ? $lifeTime : $this->defaultTTL;
        $serialized = serialize($data);

        $response = $this->redis->setex($key, $ttl, $serialized);
        return ('OK' === (string) $response);
    }

    /**
     * {@inheritdoc}
     */
    protected function doDelete($id)
    {
        $key = sprintf($this->keyPattern, $id);

        $response = $this->redis->del($key);
        return ($response > 0);
    }

    /**
     * {@inheritdoc}
     */
    protected function doFlush()
    {
        $match = '*' . sprintf($this->keyPattern, '*');

        $keys = [];
        foreach (new Keyspace($this->redis, $match) as $key) {
            $keys[] = $key;
        }

        if (!$keys) {
            return false;
        }

        $response = call_user_func_array([$this->redis, 'del'], $keys);
        return ($response > 0);
    }

    /**
     * {@inheritdoc}
     */
    protected function doGetStats()
    {
        $info = $this->redis->info();

        return [
            Cache::STATS_HITS   => false,
            Cache::STATS_MISSES => false,
            Cache::STATS_UPTIME => $info['uptime_in_seconds'],
            Cache::STATS_MEMORY_USAGE      => $info['used_memory'],
            Cache::STATS_MEMORY_AVAILABLE  => false
        ];
    }
}
