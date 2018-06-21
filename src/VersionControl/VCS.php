<?php
/**
 * @copyright (c) 2018 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\VersionControl;

use Hal\Core\Entity\System\VersionControlProvider;
use Hal\Core\Type\VCSProviderEnum;
use Hal\Core\Validation\ValidatorErrorTrait;

class VCS
{
    use ValidatorErrorTrait;

    const ERR_VCS_MISCONFIGURED = 'No valid Version Control Provider was found. Hal may be misconfigured.';

    /**
     * @var VCSAdapterInterface[]
     */
    private $adapters;

    /**
     * @param VCSAdapterInterface ...$adapters
     */
    public function __construct(VCSAdapterInterface ...$adapters)
    {
        $this->addAdapters(...$adapters);
    }

    /**
     * @param VCSAdapterInterface ...$adapters
     *
     * @return self
     */
    public function addAdapters(VCSAdapterInterface ...$adapters): self
    {
        foreach ($adapters as $adapter) {
            $this->addAdapter($adapter);
        }

        return $this;
    }

    /**
     * @param VCSAdapterInterface $adapter
     *
     * @return self
     */
    public function addAdapter(VCSAdapterInterface $adapter): self
    {
        $types = $adapter->getProvidedTypes();

        foreach ($types as $type) {
            $this->adapters[$type] = $adapter;
        }

        return $this;
    }

    /**
     * The typehint of this needs to change to be less github specific.
     *
     * @param VersionControlProvider $vcs
     *
     * @return VCSClientInterface|null
     */
    public function service(VersionControlProvider $vcs)
    {
        $adapter = $this->adapters[$vcs->type()] ?? null;
        if (!$adapter) {
            $this->addError(self::ERR_VCS_MISCONFIGURED);
            return null;
        }

        $service = $adapter->getService($vcs);
        if (!$service) {
            $this->addError(self::ERR_VCS_MISCONFIGURED);
            return null;
        }

        return $service;
    }

    /**
     * The typehint of this needs to change to be less github specific.
     *
     * @param VersionControlProvider $vcs
     *
     * @return VCSDownloaderInterface|null
     */
    public function downloader(VersionControlProvider $vcs): ?VCSDownloaderInterface
    {
        $adapter = $this->adapters[$vcs->type()] ?? null;
        if (!$adapter) {
            $this->addError(self::ERR_VCS_MISCONFIGURED);
            return null;
        }

        $downloader = $adapter->getDownloader($vcs);
        if (!$downloader) {
            $this->addError(self::ERR_VCS_MISCONFIGURED);
            return null;
        }

        return $downloader;
    }
}
