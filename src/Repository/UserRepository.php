<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Repository;

use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use MCP\DataType\Time\TimePoint;
use QL\Hal\Core\Entity\User;

class UserRepository extends EntityRepository
{
    const DQL_RECENT_APPLICATIONS = <<<SQL
   SELECT a
     FROM QL\Hal\Core\Entity\Build b
     JOIN QL\Hal\Core\Entity\Application a WITH a = b.application
    WHERE
        b.user = :user
        AND b.created > :oldestbuild
SQL;

    const DQL_BUILD_COUNT = <<<SQL
   SELECT count(b)
     FROM QL\Hal\Core\Entity\Build b
    WHERE
        b.user = :user
SQL;

    const DQL_PUSH_COUNT = <<<SQL
   SELECT count(p)
     FROM QL\Hal\Core\Entity\Push p
    WHERE
        p.user = :user
SQL;

    const DQL_GET_USERS = <<<SQL
   SELECT u
     FROM QL\Hal\Core\Entity\User u
 ORDER BY u.name DESC
SQL;

    /**
     * Get recent applications the user has built or pushed for
     *
     * @param User $user
     * @param Timepoint|null $oldest
     *
     * @return int
     */
    public function getUsersRecentApplications(User $user, Timepoint $oldest = null)
    {
        $dql = self::DQL_BUILD_COUNT;

        $cacheID = sprintf('user-recent-apps-%s', $user->id());

        if ($oldest) {
            $oldest = $oldest->format('Y-m-d H:i:s', 'UTC');
        } else {
            $oldeset = (new DateTime)
                ->modify('-2 months')
                ->setTimeZone(new DateTimeZone('UTC'))
                ->format('Y-m-d H:i:s');
        }

        $query = $this->getEntityManager()
            ->createQuery(self::DQL_RECENT_APPLICATIONS)
            ->setCacheable(true)
            ->setResultCacheId($cacheID)
            ->setParameter('user', $user)
            ->setParameter('oldestbuild', $oldest);

        return $query->getResult();
    }

    /**
     * @param int $limit
     * @param int $page
     *
     * @return Paginator
     */
    public function getPaginatedUsers($limit = 25, $page = 0)
    {
        $dql = self::DQL_GET_USERS;

        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setMaxResults($limit)
            ->setFirstResult($limit * $page);

        return new Paginator($query);
    }

    /**
     * Get all number of builds for User
     *
     * @param User $user
     *
     * @return int
     */
    public function getBuildCount(User $user)
    {
        $dql = self::DQL_BUILD_COUNT;

        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('user', $user);

        return $this->getCount($query);
    }

    /**
     * Get all number of pushes for User
     *
     * @param User $user
     *
     * @return int
     */
    public function getPushCount(User $user)
    {
        $dql = self::DQL_PUSH_COUNT;

        $query = $this->getEntityManager()
            ->createQuery($dql)
            ->setParameter('user', $user);

        return $this->getCount($query);
    }

    private function getCount($query)
    {
        $result = $query->getOneOrNullResult();

        if (!$result) {
            return 0;
        }

        return (int) array_shift($result);
    }
}
