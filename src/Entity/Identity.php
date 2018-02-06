<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Hal\Core\Entity\System\UserIdentityProvider;
use Hal\Core\Utility\EntityTrait;
use Hal\Core\Utility\ParameterTrait;
use JsonSerializable;
use QL\MCP\Common\Time\TimePoint;

class Identity implements JsonSerializable
{
    use EntityTrait;
    use ParameterTrait;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var array
     */
    protected $tokens;

    /**
     * @var string
     */
    protected $providerUniqueID;

    /**
     * @var UserIdentityProvider|null
     */
    protected $provider;

    /**
     * @param string $id
     * @param TimePoint|null $created
     */
    public function __construct($id = '', TimePoint $created = null)
    {
        $this->initializeEntity($id, $created);
        $this->initializeParameters();
        $this->tokens = [];

        $this->user = null;

        $this->providerUniqueID = '';

        $this->provider = null;
    }

    /**
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function user(): User
    {
        return $this->user;
    }

    /**
     * @return array
     */
    public function tokens(): array
    {
        return $this->tokens;
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    public function token(string $name): ?string
    {
        if (isset($this->tokens[$name])) {
            return $this->tokens[$name];
        }

        return null;
    }

    /**
     * @return string
     */
    public function providerUniqueID(): string
    {
        return $this->providerUniqueID;
    }

    /**
     * @return UserIdentityProvider|null
     */
    public function provider(): ?UserIdentityProvider
    {
        return $this->provider;
    }

    /**
     * @param User $user
     *
     * @return self
     */
    public function withUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @param string $name
     * @param string|null $value
     *
     * @return self
     */
    public function withToken(string $name, ?string $value): self
    {
        if ($value !== null) {
            $this->parameters[$name] = $value;
        } else {
            unset($this->parameters[$name]);
        }

        return $this;
    }

    /**
     * @param array $tokens
     *
     * @return self
     */
    public function withTokens(array $tokens): self
    {
        $this->tokens = [];
        foreach ($tokens as $name => $value) {
            $this->withToken($name, $value);
        }

        return $this;
    }

    /**
     * @param string $id
     *
     * @return self
     */
    public function withProviderUniqueID(string $id): self
    {
        $this->providerUniqueID = $id;
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

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->id(),
            'created' => $this->created(),

            'parameters' => $this->parameters(),
            // 'tokens' => $this->tokens(),

            'provider_unique_id' => $this->providerUniqueID(),
            'provider_id' => $this->provider() ? $this->provider()->id() : null
        ];

        return $json;
    }
}
