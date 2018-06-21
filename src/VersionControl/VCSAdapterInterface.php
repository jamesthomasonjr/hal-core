<?php
/**
 * @copyright (c) 2018 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\VersionControl;

interface VCSAdapterInterface
{
    /**
     * @return string[] The VCS-types managed by this Adapter
     */
    public function getProvidedTypes(): array;

    /**
     * @param VersionControlProvider $vcs The VCS Configuration
     *
     * @return VCSServiceInterface The configured service for the given type
     *
     * @throws VCSException on error
     */
    public function getService(VersionControlProvider $vcs): VCSServiceInterface;

    /**
     * @param VersionControlProvider $vcs The VCS Configuration
     *
     * @return VCSDownloaderInterface The configured downloader for the given type
     *
     * @throws VCSException on error
     */
    public function getDownloader(VersionControlProvider $vcs): VCSDownloaderInterface;
}
