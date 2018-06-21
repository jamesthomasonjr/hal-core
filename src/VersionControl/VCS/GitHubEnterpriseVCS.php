<?php
/**
 * @copyright (c) 2018 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\VersionControl\VCS;

use Github\Client;
use Github\HttpClient\Builder;
use Github\ResultPager;
use GuzzleHttp\Client as GuzzleClient;
use Hal\Core\Entity\System\VersionControlProvider;
use Hal\Core\Parameters;
use Hal\Core\Type\VCSProviderEnum;
use Hal\Core\Validation\ValidatorErrorTrait;
use Hal\Core\VersionControl\Downloader\GitHubDownloader;
use Hal\Core\VersionControl\GitHub\MCPCachePlugin;
use Hal\Core\VersionControl\VCSAdapterInterface;
use QL\MCP\Cache\CachingTrait;

class GitHubEnterpriseVCS extends VCSAdapterInterface
{
    use CachingTrait;
    use ValidatorErrorTrait;

    const ERR_VCS_MISCONFIGURED = 'GitHub Enterprise Version Control Provider is misconfigured.';

    /**
     * @var MCPCachePlugin
     */
    private $cachePlugin;

    /**
     * @var Builder
     */
    private $httpClientBuilder;

    /**
     * @var bool
     */
    private $isCachedAdded;

    /**
     * @var array
     */
    private $defaultGuzzleOptions;

    /**
     * @param MCPCachePlugin $cachePlugin
     * @param Builder $httpClientBuilder
     */
    public function __construct(MCPCachePlugin $cachePlugin, Builder $httpClientBuilder)
    {
        $this->cachePlugin = $cachePlugin;
        $this->httpClientBuilder = $httpClientBuilder;

        $this->isCachedAdded = false;
        $this->defaultGuzzleOptions = [];
    }

    /**
     * @{inheritdoc}
     */
    public function getProvidedTypes(): array
    {
        return [VCSProviderEnum::TYPE_GITHUB_ENTERPRISE];
    }

    /**
     * @var array
     */
    public function setDefaultDownloaderOptions(array $options)
    {
        $this->defaultGuzzleOptions = $options;
    }

    /**
     * @param VersionControlProvider $vcs
     *
     * @return Client|null
     */
    public function getService(VersionControlProvider $vcs): ?Client
    {
        if ($vcs->type() !== VCSProviderEnum::TYPE_GITHUB_ENTERPRISE) {
            $this->addError(self::ERR_VCS_MISCONFIGURED);
            return null;
        }

        $key = sprintf('vcs_clients:%s:%s', $vcs->type(), $vcs->id());

        $client = $this->getFromCache($key);
        if ($client instanceof Client) {
            return $client;
        }

        $enterpriseURL = $vcs->parameter(Parameters::VCS_GHE_URL);
        $token = $vcs->parameter(Parameters::VCS_GHE_TOKEN);
        if (!$enterpriseURL || !$token) {
            $this->addError(self::ERR_VCS_MISCONFIGURED);
            return null;
        }

        $client = new Client($this->httpClientBuilder, null, $enterpriseURL);
        $client->authenticate($token, null, Client::AUTH_HTTP_TOKEN);

        if (!$this->isCachedAdded) {
            //Since the github client's cache only supports PSR6 and we don't have a PSR6 cache we need
            //to make sure that the cache plugin we've written is placed at the end of the plugins to run.
            $this->httpClientBuilder->addPlugin($this->cachePlugin);
            $this->isCachedAdded = true;
        }

        // Should only be in memory
        $this->setToCache($key, $client, 60 * 60);

        return $client;
    }

    /**
     * @param VersionControlProvider $vcs
     *
     * @return GitHubDownloader|null
     */
    public function getDownloader(VersionControlProvider $vcs): ?GitHubDownloader
    {
        if ($vcs->type() !== VCSProviderEnum::TYPE_GITHUB_ENTERPRISE) {
            $this->addError(self::ERR_VCS_MISCONFIGURED);
            return null;
        }

        $baseURL = $vcs->parameter(Parameters::VCS_GHE_URL);
        $token = $vcs->parameter(Parameters::VCS_GHE_TOKEN);
        if (!$baseURL || !$token) {
            $this->addError(self::ERR_VCS_MISCONFIGURED);
            return null;
        }

        $baseURL = $baseURL . '/api/v3/';

        $options = $this->defaultGuzzleOptions + [
            'base_uri' => $baseURL,
            'headers' => [
                'Authorization' => sprintf('token %s', $token)
            ],

            'allow_redirects' => true,
            'connect_timeout' => 5,
            'timeout' => 300, # 5 minutes seems like a reasonable amount of time?
            'http_errors' => false,
        ];

        $guzzle = new GuzzleClient($options);

        return new GitHubDownloader($guzzle);
    }
}
