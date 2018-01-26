<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Utility;

use Hal\Core\Entity\Application;
use Hal\Core\Entity\Environment;
use Hal\Core\Entity\Organization;

trait ScopedEntityTrait
{
    /**
     * Restricted to a specific application. Optional.
     *
     * @var Application|null
     */
    protected $application;

    /**
     * Restricted to a specific environment. Optional.
     *
     * @var Environment|null
     */
    protected $environment;

    /**
     * Restricted to a specific organization. Optional.
     *
     * @var Organization|null
     */
    protected $organization;

    /**
     * @return void
     */
    private function initializeScopes()
    {
        $this->application = null;
        $this->environment = null;
        $this->organization = null;
    }

    /**
     * @return Application|null
     */
    public function application(): ?Application
    {
        return $this->application;
    }

    /**
     * @return Organization|null
     */
    public function organization(): ?Organization
    {
        return $this->organization;
    }

    /**
     * @return Environment|null
     */
    public function environment(): ?Environment
    {
        return $this->environment;
    }

    /**
     * @param Application $application
     *
     * @return self
     */
    public function withApplication(?Application $application): self
    {
        $this->application = $application;
        return $this;
    }

    /**
     * @param Organization $organization
     *
     * @return self
     */
    public function withOrganization(?Organization $organization): self
    {
        $this->organization = $organization;
        return $this;
    }

    /**
     * @param Environment|null $environment
     *
     * @return self
     */
    public function withEnvironment(?Environment $environment): self
    {
        $this->environment = $environment;
        return $this;
    }
}
