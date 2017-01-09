<?php
/**
 * @copyright (c) 2016 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace QL\Hal\Core\Entity;

use PHPUnit_Framework_TestCase;

class DeploymentTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $deployment = new Deployment;

        $this->assertSame(null, $deployment->id());
        $this->assertSame('', $deployment->name());
        $this->assertSame('', $deployment->url());

        $this->assertSame(null, $deployment->path());

        $this->assertSame(null, $deployment->cdName());
        $this->assertSame(null, $deployment->cdGroup());
        $this->assertSame(null, $deployment->cdConfiguration());

        $this->assertSame(null, $deployment->ebName());
        $this->assertSame(null, $deployment->ebEnvironment());

        $this->assertSame(null, $deployment->s3bucket());
        $this->assertSame(null, $deployment->s3file());

        $this->assertSame(null, $deployment->application());
        $this->assertSame(null, $deployment->server());
        $this->assertSame(null, $deployment->credential());
    }

    public function testProperties()
    {
        $application = new Application;
        $server = new Server;
        $credential = new Credential;

        $deployment = (new Deployment)
            ->withId(1234)
            ->withName('deployment name')
            ->withURL('http://example.com')
            ->withPath('/my/app/root')

            ->withCDName('DemoApplication')
            ->withCDGroup('DemoFleet')
            ->withCDConfiguration('CodeDeployDefault.HalfAtATime')

            ->withEBName('BeanstalkApp')
            ->withEBEnvironment('e-12345abcd')

            ->withS3Bucket('bucket-name')
            ->withS3File('myfile/myfile.tar.gz')

            ->withApplication($application)
            ->withServer($server)
            ->withCredential($credential);

        $this->assertSame(1234, $deployment->id());
        $this->assertSame('deployment name', $deployment->name());
        $this->assertSame('http://example.com', $deployment->url());
        $this->assertSame('/my/app/root', $deployment->path());

        $this->assertSame('DemoApplication', $deployment->cdName());
        $this->assertSame('DemoFleet', $deployment->cdGroup());
        $this->assertSame('CodeDeployDefault.HalfAtATime', $deployment->cdConfiguration());

        $this->assertSame('BeanstalkApp', $deployment->ebName());
        $this->assertSame('e-12345abcd', $deployment->ebEnvironment());

        $this->assertSame('bucket-name', $deployment->s3Bucket());
        $this->assertSame('myfile/myfile.tar.gz', $deployment->s3File());

        $this->assertSame($application, $deployment->application());
        $this->assertSame($server, $deployment->server());
        $this->assertSame($credential, $deployment->credential());
    }

    public function testSerialization()
    {
        $application = (new Application)
            ->withId(1234);
        $server = (new Server)
            ->withId(1234);
        $credential = new Credential('abcdef');

        $deployment = (new Deployment)
            ->withId(1234)
            ->withName('deployment name')
            ->withURL('http://example.com')
            ->withPath('/my/app/root')

            ->withCDName('DemoApplication')
            ->withCDGroup('DemoFleet')
            ->withCDConfiguration('CodeDeployDefault.HalfAtATime')

            ->withEBName('BeanstalkApp')
            ->withEBEnvironment('e-12345abcd')

            ->withS3Bucket('bucket-name')
            ->withS3File('myfile/myfile.tar.gz')

            ->withApplication($application)
            ->withServer($server)
            ->withCredential($credential);

        $expected = <<<JSON
{
    "id": 1234,
    "name": "deployment name",
    "url": "http://example.com",
    "path": "/my/app/root",
    "cdName": "DemoApplication",
    "cdGroup": "DemoFleet",
    "cdConfiguration": "CodeDeployDefault.HalfAtATime",
    "ebName": "BeanstalkApp",
    "ebEnvironment": "e-12345abcd",
    "s3bucket": "bucket-name",
    "s3file": "myfile/myfile.tar.gz",
    "application": 1234,
    "server": 1234,
    "credential": "abcdef",
    "push": null
}
JSON;

        $this->assertSame($expected, json_encode($deployment, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    public function testDefaultSerialization()
    {
        $deployment = new Deployment;

        $expected = <<<JSON
{
    "id": null,
    "name": "",
    "url": "",
    "path": null,
    "cdName": null,
    "cdGroup": null,
    "cdConfiguration": null,
    "ebName": null,
    "ebEnvironment": null,
    "s3bucket": null,
    "s3file": null,
    "application": null,
    "server": null,
    "credential": null,
    "push": null
}
JSON;

        $this->assertSame($expected, json_encode($deployment, JSON_PRETTY_PRINT));
    }

    public function testPrettyFormatWithName()
    {
        $deployment = (new Deployment)
            ->withName('deployment name')
            ->withPath('/my/app/root');

        $this->assertSame('deployment name', $deployment->formatPretty());
    }

    public function testPrettyFormatWithoutServer()
    {
        $deployment = (new Deployment)
            ->withPath('/my/app/root');

        $this->assertSame('Unknown', $deployment->formatPretty());
    }

    public function testPrettyFormatForRsync()
    {
        $deployment = (new Deployment)
            ->withPath('/my/app/root')
            ->withServer((new Server)->withType('rsync')->withName('hostname'));

        $this->assertSame('hostname', $deployment->formatPretty());
    }

    public function testPrettyFormatForRsyncWithDetails()
    {
        $deployment = (new Deployment)
            ->withPath('/my/app/root')
            ->withServer((new Server)->withType('rsync')->withName('hostname'));

        $this->assertSame('hostname', $deployment->formatPretty(true));
    }

    public function testPrettyFormatForEB()
    {
        $deployment = (new Deployment)
            ->withEBName('BeanstalkApp')
            ->withEBEnvironment('e-1234abcd')
            ->withServer((new Server)->withType('eb')->withName('us-east-1'));

        $this->assertSame('EB (us-east-1)', $deployment->formatPretty());
    }

    public function testPrettyFormatForEBWithDetails()
    {
        $deployment = (new Deployment)
            ->withEBName('BeanstalkApp')
            ->withEBEnvironment('e-1234abcd')
            ->withServer((new Server)->withType('eb')->withName('us-east-1'));

        $this->assertSame('EB (e-1234abcd)', $deployment->formatPretty(true));
    }

    public function testPrettyFormatForS3()
    {
        $deployment = (new Deployment)
            ->withS3Bucket('bucket-name')
            ->withS3File('file.tar.gz')
            ->withServer((new Server)->withType('s3')->withName('us-west-1'));

        $this->assertSame('S3 (us-west-1)', $deployment->formatPretty());
    }

    public function testPrettyFormatForS3WithDetails()
    {
        $deployment = (new Deployment)
            ->withS3Bucket('bucket-name')
            ->withS3File('file.tar.gz')
            ->withServer((new Server)->withType('s3')->withName('us-west-1'));

        $this->assertSame('S3 (bucket-name)', $deployment->formatPretty(true));
    }

    public function testPrettyFormatForCD()
    {
        $deployment = (new Deployment)
            ->withCDName('DemoApp')
            ->withCDGroup('DemoFleet')
            ->withCDConfiguration('CodeDeploy.HalfAtATime')
            ->withServer((new Server)->withType('cd')->withName('us-west-1'));

        $this->assertSame('CD (us-west-1)', $deployment->formatPretty());
    }

    public function testPrettyFormatForCDWithDetails()
    {
        $deployment = (new Deployment)
            ->withCDName('DemoApp')
            ->withCDGroup('DemoFleet')
            ->withCDConfiguration('CodeDeploy.HalfAtATime')
            ->withServer((new Server)->withType('cd')->withName('us-west-1'));

        $this->assertSame('CD (DemoFleet)', $deployment->formatPretty(true));
    }

    public function testFormatMetaWithoutServer()
    {
        $deployment = new Deployment;

        $this->assertSame('Unknown', $deployment->formatMeta());
    }

    public function testFormatMetaRsync()
    {
        $deployment = (new Deployment)
            ->withPath('/var/www/site')
            ->withServer((new Server)->withType('rsync'));

        $this->assertSame('/var/www/site', $deployment->formatMeta());
    }

    public function testFormatMetaEB()
    {
        $deployment = (new Deployment)
            ->withEBEnvironment('e-abcde1234')
            ->withServer((new Server)->withType('eb'));

        $this->assertSame('e-abcde1234', $deployment->formatMeta());
    }

    public function testFormatMetaS3()
    {
        $deployment = (new Deployment)
            ->withS3Bucket('bucket-name')
            ->withServer((new Server)->withType('s3'));

        $this->assertSame('bucket-name', $deployment->formatMeta());
    }

    public function testFormatMetaS3WithFile()
    {
        $deployment = (new Deployment)
            ->withS3Bucket('bucket-name')
            ->withS3File('file.zip')
            ->withServer((new Server)->withType('s3'));

        $this->assertSame('bucket-name/file.zip', $deployment->formatMeta());
    }

    public function testFormatMetaCD()
    {
        $deployment = (new Deployment)
            ->withCDGroup('DemoFleet')
            ->withServer((new Server)->withType('cd'));

        $this->assertSame('DemoFleet', $deployment->formatMeta());
    }
}
