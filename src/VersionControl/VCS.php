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
     * @var array
     */
    private $adapters;

    /**
     * @param array $adapters
     */
    public function __construct(array $adapters = [])
    {
        $this->adapters = [];

        foreach ($adapters as $type => $adapter) {
            $this->addAdapter($type, $adapter);
        }
    }

    /**
     * @param string $type
     * @param mixed $adapter
     *
     * @return void
     */
    public function addAdapter(string $type, $adapter): void
    {
        $this->adapters[$type] = $adapter;
    }

    /**
     * The typehint of this needs to change to be less github specific.
     *
     * @param VersionControlProvider $vcs
     *
     * @return mixed|null
     */
    public function authenticate(VersionControlProvider $vcs)
    {
        $adapter = $this->adapters[$vcs->type()] ?? null;
        if (!$adapter) {
            $this->addError(self::ERR_VCS_MISCONFIGURED);
            return null;
        }

        if ($vcs->type() === VCSProviderEnum::TYPE_GITHUB) {
            $vcsService = $adapter->buildClient($vcs);

        } elseif ($vcs->type() === VCSProviderEnum::TYPE_GITHUB_ENTERPRISE) {
            $vcsService = $adapter->buildClient($vcs);

        } else {
            $this->addError(self::ERR_VCS_MISCONFIGURED);
            return null;
        }

        // if ($vcsService instanceof GitHubService) {
        if ($vcsService) {
            return $vcsService;
        }

        $this->importErrors($adapter->errors());
        return null;
    }

    /**
     * The typehint of this needs to change to be less github specific.
     *
     * @param VersionControlProvider $vcs
     *
     * @return mixed|null
     */
    public function downloader(VersionControlProvider $vcs)
    {
        $adapter = $this->adapters[$vcs->type()] ?? null;
        if (!$adapter) {
            $this->addError(self::ERR_VCS_MISCONFIGURED);
            return null;
        }

        if ($vcs->type() === VCSProviderEnum::TYPE_GITHUB) {
            $vcsDownloader = $adapter->buildDownloader($vcs);

        } elseif ($vcs->type() === VCSProviderEnum::TYPE_GITHUB_ENTERPRISE) {
            $vcsDownloader = $adapter->buildDownloader($vcs);

        } else {
            $this->addError(self::ERR_VCS_MISCONFIGURED);
            return null;
        }

        // if ($vcsDownloader instanceof GitHubService) {
        if ($vcsDownloader) {
            return $vcsDownloader;
        }

        $this->importErrors($adapter->errors());
        return null;
    }
}
