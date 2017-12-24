<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Hal\Core\Utility\EntityIDTrait;
use JsonSerializable;

class UserSettings implements JsonSerializable
{
    use EntityIDTrait;

    /**
     * @var string
     */
    protected $id;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Application[]
     */
    protected $favoriteApplications;

    /**
     * @param string $id
     */
    public function __construct($id = '')
    {
        $this->id = $id ?: $this->generateEntityID();

        $this->user = null;
        $this->favoriteApplications = [];
    }

    /**
     * @return string
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return Application[]
     */
    public function favoriteApplications()
    {
        return $this->favoriteApplications;
    }

    /**
     * @return User
     */
    public function user()
    {
        return $this->user;
    }

    /**
     * @param string $id
     *
     * @return self
     */
    public function withID($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param array $favorites
     *
     * @return self
     */
    public function withFavoriteApplications(array $favorites)
    {
        $this->favoriteApplications = $favorites;
        return $this;
    }

    /**
     * @param User|null $user
     *
     * @return self
     */
    public function withUser(User $user = null)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Convenience function.
     *
     * @param Application $application
     *
     * @return bool
     */
    public function isFavoriteApplication(Application $application)
    {
        $id = $application->id();

        return in_array($id, $this->favoriteApplications(), true);
    }

    /**
     * Convenience function.
     *
     * @param Application $application
     *
     * @return bool
     */
    public function withFavoriteApplication(Application $application)
    {
        if (!$this->isFavoriteApplication($application)) {
            $apps = $this->favoriteApplications();
            $apps[] = $application->id();
            $this->withFavoriteApplications($apps);
        }

        return $this;
    }

    /**
     * Convenience function.
     *
     * @param Application $application
     *
     * @return bool
     */
    public function withoutFavoriteApplication(Application $application)
    {
        if ($this->isFavoriteApplication($application)) {
            $this->removeApplication($application);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->id(),

            'favorite_applications' => $this->favoriteApplications(),

            'user_id' => $this->user() ? $this->user()->id() : null
        ];

        return $json;
    }

    /**
     * @param Application $application
     *
     * @return void
     */
    private function removeApplication(Application $application)
    {
        $filter = function ($appID) use ($application) {
            // Remove IDs that match
            if ($appID === $application->id()) {
                return false;
            }

            return true;
        };

        $favorites = array_filter($this->favoriteApplications(), $filter);

        $this->withFavoriteApplications(array_values($favorites));
    }
}
