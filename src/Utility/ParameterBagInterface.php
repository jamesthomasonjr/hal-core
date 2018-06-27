<?php
/**
 * @copyright (c) 2018 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Utility;

interface ParameterBagInterface
{
    /**
     * @return array
     */
    public function parameters(): array;

    /**
     * @param string $name
     *
     * @return string|null
     */
    public function parameter(string $name): ?string;

    /**
     * @param string $name
     * @param string|null $value
     */
    public function withParameter(string $name, ?string $value);

    /**
     * @param array $parameters
     */
    public function withParameters(array $parameters);
}
