<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\AWS;

use Aws\CodeDeploy\CodeDeployClient;
use Aws\Ec2\Ec2Client;
use Aws\ElasticBeanstalk\ElasticBeanstalkClient;
use Aws\ElasticLoadBalancing\ElasticLoadBalancingClient;
use Aws\S3\S3Client;
use Aws\Sdk;
use Doctrine\ORM\EntityManagerInterface;
use Hal\Core\Crypto\Encryption;
use Hal\Core\Entity\Credential;
use Hal\Core\Entity\Credential\AWSRoleCredential;
use Hal\Core\Entity\Credential\AWSStaticCredential;
use Mockery;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Hal\Core\Crypto\CryptoException;

class CredentialProviderTest extends TestCase
{
    private $logger;
    private $decrypter;
    private $em;
    private $aws;
    public function setUp()
    {
        $this->logger = Mockery::mock(LoggerInterface::class);
        $this->decrypter = Mockery::mock(Encryption::class);
        $this->em = Mockery::mock(EntityManagerInterface::class);
        // can't mock :(
        $this->aws = new Sdk(['version' => 'latest']);
    }
    public function testCredentialsSecretIsEmpty()
    {
        $credential = new AWSStaticCredential('key1234', '');
        $this->logger
            ->shouldReceive('critical')
            ->with(CredentialProvider::ERR_INVALID_SECRET)
            ->once();
        $provider = new CredentialProvider($this->logger, $this->decrypter, $this->em, $this->aws);
        $actual = $provider->getStaticCredentials($credential);
        $this->assertSame(null, $actual);
    }
    public function testCredentialsCannotBeDecrypted()
    {
        $credential = new AWSStaticCredential('key1234', 'secret1234');
        $this->decrypter
            ->shouldReceive('decrypt')
            ->with('secret1234')
            ->andReturn(null);
        $this->logger
            ->shouldReceive('critical')
            ->with(CredentialProvider::ERR_INVALID_SECRET)
            ->once();
        $provider = new CredentialProvider($this->logger, $this->decrypter, $this->em, $this->aws);
        $actual = $provider->getStaticCredentials($credential);
        $this->assertSame(null, $actual);
    }
    public function testStaticCredentialsIsSuccess()
    {
        $credential = new AWSStaticCredential('key1234', 'secret1234');
        $this->decrypter
            ->shouldReceive('decrypt')
            ->with('secret1234')
            ->andReturn('lolok');
        $provider = new CredentialProvider($this->logger, $this->decrypter, $this->em, $this->aws);
        $actual = $provider->getStaticCredentials($credential);
        $this->assertSame(['key' => 'key1234', 'secret' => 'lolok'], $actual);
    }
    public function testInternalCredentialsMissing()
    {
        $details = new AWSRoleCredential('123456789', 'role/my-custom-role');
        $this->em
            ->shouldReceive('getRepository->findOneBy')
            ->with(['isInternal' => true, 'name' => 'derpherp'])
            ->andReturnNull();
        $this->logger
            ->shouldReceive('critical')
            ->with('Missing internal credentials. Searched for "derpherp".')
            ->once();
        $provider = new CredentialProvider($this->logger, $this->decrypter, $this->em, $this->aws);
        $provider->setInternalCredentialName('derpherp');
        $actual = $provider->getRoleCredentials($details, 'us-east-1');
        $this->assertSame(null, $actual);
    }
    public function testInternalCredentialWrongType()
    {
        $details = new AWSRoleCredential('123456789', 'role/my-custom-role');
        $internal = new AWSRoleCredential('123456789', 'role-name');
        $credentials = (new Credential)->withDetails($internal);
        $this->em
            ->shouldReceive('getRepository->findOneBy')
            ->with(['isInternal' => true, 'name' => 'Hal Internal Credentials - AWS'])
            ->andReturn($credentials);
        $this->logger
            ->shouldReceive('critical')
            ->with('Missing internal credentials. Searched for "Hal Internal Credentials - AWS".')
            ->once();
        $provider = new CredentialProvider($this->logger, $this->decrypter, $this->em, $this->aws);
        $actual = $provider->getRoleCredentials($details, 'us-east-1');
        $this->assertSame(null, $actual);
    }
    public function testAssumeCredentialsIsSuccess()
    {
        $details = new AWSRoleCredential('123456789', 'role/my-custom-role');
        $internal = new AWSStaticCredential('key1234', 'secretabcd');
        $credentials = (new Credential)->withDetails($internal);
        $this->em
            ->shouldReceive('getRepository->findOneBy')
            ->with(['isInternal' => true, 'name' => 'Hal Internal Credentials - AWS'])
            ->andReturn($credentials);
        $this->decrypter
            ->shouldReceive('decrypt')
            ->with('secretabcd')
            ->andReturn('secret1234');
        $provider = new CredentialProvider($this->logger, $this->decrypter, $this->em, $this->aws);
        $actual = $provider->getRoleCredentials($details, 'us-east-1');
        $this->assertTrue(is_callable($actual));
    }
}
