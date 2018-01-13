<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Utility;

trait ParameterTrait
{
    /**
     * @var array
     */
    protected $parameters;

    /**
     * @return void
     */
    private function initializeParameters()
    {
        $this->parameters = [];
    }

    /**
     * @return array
     */
    public function parameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    public function parameter(string $name): ?string
    {
        if (isset($this->parameters[$name])) {
            return $this->parameters[$name];
        }

        return null;
    }

    /**
     * @param string $name
     * @param string|null $value
     *
     * @return self
     */
    public function withParameter(string $name, ?string $value): self
    {
        if ($value !== null) {
            $this->parameters[$name] = $value;

        } else {
            unset($this->parameters[$name]);
        }

        return $this;
    }

    /**
     * @param array $parameters
     *
     * @return self
     */
    public function withParameters(array $parameters): self
    {
        $this->parameters = [];
        foreach ($parameters as $name => $value) {
            $this->withParameter($name, $value);
        }

        return $this;
    }
}
