<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Hal\Core\Entity\System\UserIdentityProvider;
use Hal\Core\Utility\EntityTrait;
use Hal\Core\Utility\ParameterTrait;
use JsonSerializable;
use QL\MCP\Common\Time\TimePoint;

class User implements JsonSerializable
{
    use EntityTrait;
    use ParameterTrait;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $isDisabled;

    /**
     * @var array
     */
    protected $settings;

    /**
     * @var UserIdentityProvider|null
     */
    protected $provider;

    /**
     * @var ArrayCollection
     */
    protected $tokens;

    /**
     * @param string $id
     * @param TimePoint|null $created
     */
    public function __construct($id = '', TimePoint $created = null)
    {
        $this->initializeEntity($id, $created);
        $this->initializeParameters();

        $this->name = '';
        $this->isDisabled = false;
        $this->settings = [];

        $this->provider = null;
        $this->tokens = new ArrayCollection;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isDisabled(): bool
    {
        return $this->isDisabled;
    }

    /**
     * @return array
     */
    public function settings(): array
    {
        return $this->settings;
    }

    /**
     * @return UserIdentityProvider|null
     */
    public function provider(): ?UserIdentityProvider
    {
        return $this->provider;
    }

    /**
     * @return ArrayCollection
     */
    public function tokens(): ArrayCollection
    {
        return $this->tokens;
    }

    /**
     * @param string $name
     *
     * @return self
     */
    public function withName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param bool $isDisabled
     *
     * @return self
     */
    public function withIsDisabled(bool $isDisabled): self
    {
        $this->isDisabled = $isDisabled;
        return $this;
    }

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return self
     */
    public function withSetting(string $name, $value): self
    {
        $this->settings[$name] = $value;
        return $this;
    }

    /**
     * @param array $settings
     *
     * @return self
     */
    public function withSettings(array $settings): self
    {
        $this->settings = [];
        foreach ($settings as $name => $value) {
            $this->withSetting($name, $value);
        }

        return $this;
    }

    /**
     * @param UserIdentityProvider|null $provider
     *
     * @return self
     */
    public function withProvider(?UserIdentityProvider $provider): self
    {
        $this->provider = $provider;
        return $this;
    }

    // @todo add token add/remove - arraycollection items should always be removed from the parent

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->id(),
            'created' => $this->created(),

            'name' => $this->name(),
            'is_disabled' => $this->isDisabled(),

            'parameters' => $this->parameters(),
            'settings' => $this->settings(),

            'provider_id' => $this->provider() ? $this->provider()->id() : null,
            'tokens' => $this->tokens()->toArray()
        ];

        return $json;
    }
}
