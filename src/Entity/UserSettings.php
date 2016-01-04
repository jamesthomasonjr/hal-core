<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Entity;

use JsonSerializable;

class UserSettings implements JsonSerializable
{
    /**
     * @type string
     */
    protected $id;

    /**
     * @type User
     */
    protected $user;

    /**
     * @type array
     */
    protected $favoriteApplications;

    /**
     * @param string $id
     */
    public function __construct($id = '')
    {
        $this->id = $id;
        $this->user = null;
        $this->favoriteApplications = [];
    }

    /**
     * @return int
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * @return array
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
    public function withId($id)
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

            'favoriteApplications' => $this->favoriteApplications(),

            'user' => $this->user() ? $this->user()->id() : null
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
        $filter = function($appID) use ($application) {
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
