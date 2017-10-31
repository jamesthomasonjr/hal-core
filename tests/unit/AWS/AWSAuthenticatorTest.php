<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\AWS;

use Aws\CodeDeploy\CodeDeployClient;
use Aws\CodePipeline\CodePipelineClient;
use Aws\Ec2\Ec2Client;
use Aws\ElasticBeanstalk\ElasticBeanstalkClient;
use Aws\ElasticLoadBalancing\ElasticLoadBalancingClient;
use Aws\S3\S3Client;
use Aws\Sdk;
use Hal\Core\Entity\Credential\AWSRoleCredential;
use Hal\Core\Entity\Credential\AWSStaticCredential;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class AWSAuthenticatorTest extends TestCase
{
    private $logger;
    private $provider;
    private $aws;

    public function setUp()
    {
        $this->logger = Mockery::mock(LoggerInterface::class);
        $this->provider = Mockery::mock(CredentialProvider::class);
        // can't mock :(
        $this->aws = new Sdk(['version' => 'latest']);
    }

    public function testRegionInvalid()
    {
        $this->logger
            ->shouldReceive('critical')
            ->with(AWSAuthenticator::ERR_INVALID_REGION, [
                'specified_region' => 'badregion'
            ])
            ->once();
        $authenticator = new AWSAuthenticator($this->logger, $this->provider, $this->aws);
        $service = $authenticator->getEB('badregion', null);
        $this->assertSame(null, $service);
    }

    public function testCredentialsInvalidType()
    {
        $this->logger
            ->shouldReceive('critical')
            ->with(AWSAuthenticator::ERR_INVALID_CREDENTIAL)
            ->once();
        $authenticator = new AWSAuthenticator($this->logger, $this->provider, $this->aws);
        $service = $authenticator->getEC2('us-east-1', null);
        $this->assertSame(null, $service);
    }

    public function testGetCD()
    {
        $credentials = new AWSStaticCredential;
        $this->provider
            ->shouldReceive('getStaticCredentials')
            ->with($credentials)
            ->andReturn(['key' => '1234', 'secret' => 'abcd'])
            ->once();
        $authenticator = new AWSAuthenticator($this->logger, $this->provider, $this->aws);
        $service = $authenticator->getCD('us-west-1', $credentials);
        $this->assertInstanceOf(CodeDeployClient::class, $service);
    }

    public function testGetEB()
    {
        $credentials = new AWSStaticCredential;
        $this->provider
            ->shouldReceive('getStaticCredentials')
            ->andReturn(['key' => '1234', 'secret' => 'abcd']);
        $authenticator = new AWSAuthenticator($this->logger, $this->provider, $this->aws);
        $service = $authenticator->getEB('us-east-1', $credentials);
        $this->assertInstanceOf(ElasticBeanstalkClient::class, $service);
    }

    public function testGetEC2()
    {
        $credentials = new AWSStaticCredential;
        $this->provider
            ->shouldReceive('getStaticCredentials')
            ->andReturn(['key' => '1234', 'secret' => 'abcd']);
        $authenticator = new AWSAuthenticator($this->logger, $this->provider, $this->aws);
        $service = $authenticator->getEC2('us-east-1', $credentials);
        $this->assertInstanceOf(Ec2Client::class, $service);
    }

    public function testGetS3()
    {
        $credentials = new AWSRoleCredential();
        $this->provider
            ->shouldReceive('getRoleCredentials')
            ->with($credentials, 'us-east-1')
            ->andReturn(['key' => '1234', 'secret' => 'abcd'])
            ->once();
        $authenticator = new AWSAuthenticator($this->logger, $this->provider, $this->aws);
        $service = $authenticator->getS3('us-east-1', $credentials);
        $this->assertInstanceOf(S3Client::class, $service);
    }

    public function testGetELB()
    {
        $credentials = new AWSRoleCredential();
        $this->provider
            ->shouldReceive('getRoleCredentials')
            ->andReturn(['key' => '1234', 'secret' => 'abcd']);
        $authenticator = new AWSAuthenticator($this->logger, $this->provider, $this->aws);
        $service = $authenticator->getELB('us-east-1', $credentials);
        $this->assertInstanceOf(ElasticLoadBalancingClient::class, $service);
    }

    public function testGetCodePipeline()
    {
        $credentials = new AWSRoleCredential();
        $this->provider
            ->shouldReceive('getRoleCredentials')
            ->andReturn(['key' => '1234', 'secret' => 'abcd']);
        $authenticator = new AWSAuthenticator($this->logger, $this->provider, $this->aws);
        $service = $authenticator->getCodePipeline('us-east-1', $credentials);
        $this->assertInstanceOf(CodePipelineClient::class, $service);
    }
}
