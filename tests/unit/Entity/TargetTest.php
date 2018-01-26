<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Hal\Core\Entity\JobType\Release;
use PHPUnit\Framework\TestCase;
use QL\MCP\Common\Time\TimePoint;

class TargetTest extends TestCase
{
    public function testDefaultValues()
    {
        $target = new Target;

        $this->assertRegExp('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $target->id());
        $this->assertInstanceOf(TimePoint::class, $target->created());

        $this->assertSame('', $target->name());
        $this->assertSame('', $target->url());

        $this->assertSame([], $target->parameters());

        // $this->assertSame(null, $target->application());

        $this->assertSame(null, $target->template());
        $this->assertSame(null, $target->credential());
        $this->assertSame(null, $target->lastJob());
    }

    public function testProperties()
    {
        $application = new Application;
        $template = new TargetTemplate;
        $credential = new Credential;
        $release = new Release;

        $target = (new Target('script', '1234'))
            ->withName('target name')
            ->withURL('http://example.com')

            ->withParameters([
                'name' => 'DemoApplication',
                'group' => 'DemoFleet',
                'configuration' => 'CodeDeployDefault.HalfAtATime'
            ])
            ->withParameter('bucket', 'bucket-name')
            ->withParameter('file', 'myfile/myfile.tar.gz')

            ->withApplication($application)
            ->withTemplate($template)
            ->withLastJob($release)
            ->withCredential($credential);

        $this->assertSame('1234', $target->id());
        $this->assertSame('target name', $target->name());
        $this->assertSame('http://example.com', $target->url());

        $expectedParameters = [
            'name' => 'DemoApplication',
            'group' => 'DemoFleet',
            'configuration' => 'CodeDeployDefault.HalfAtATime',
            'bucket' => 'bucket-name',
            'file' => 'myfile/myfile.tar.gz'

        ];
        $this->assertSame($expectedParameters, $target->parameters());

        $this->assertSame('DemoApplication', $target->parameter('name'));
        $this->assertSame('DemoFleet', $target->parameter('group'));
        $this->assertSame('CodeDeployDefault.HalfAtATime', $target->parameter('configuration'));
        $this->assertSame('bucket-name', $target->parameter('bucket'));
        $this->assertSame('myfile/myfile.tar.gz', $target->parameter('file'));

        $this->assertSame($application, $target->application());
        $this->assertSame($template, $target->template());
        $this->assertSame($credential, $target->credential());
        $this->assertSame($release, $target->lastJob());
    }

    public function testSerialization()
    {
        $application = new Application('1');
        $template = new TargetTemplate('script', '2');
        $credential = new Credential('3');
        $release = new Release('4');
        $environment = new Environment('5');

        $target = (new Target('eb', '1234', new TimePoint(2017, 1, 3, 12, 0, 0, 'UTC')))
            ->withName('deployment name')
            ->withURL('http://example.com')

            ->withParameters([
                'name' => 'DemoApplication',
                'group' => 'DemoFleet',
                'configuration' => 'CodeDeployDefault.HalfAtATime'
            ])
            ->withParameter('bucket', 'bucket-name')

            ->withApplication($application)
            ->withEnvironment($environment)
            ->withTemplate($template)
            ->withCredential($credential)
            ->withLastJob($release);

        $expected = <<<JSON_TEXT
{
    "id": "1234",
    "created": "2017-01-03T12:00:00Z",
    "type": "eb",
    "name": "deployment name",
    "url": "http://example.com",
    "parameters": {
        "name": "DemoApplication",
        "group": "DemoFleet",
        "configuration": "CodeDeployDefault.HalfAtATime",
        "bucket": "bucket-name"
    },
    "application_id": "1",
    "environment_id": "5",
    "credential_id": "3",
    "template_id": "2",
    "job_id": "4"
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($target, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    public function testDefaultSerialization()
    {
        $target = new Target(null, '1', new TimePoint(2017, 1, 3, 12, 0, 0, 'UTC'));
        $target->withApplication(new Application('1234'));

        $expected = <<<JSON_TEXT
{
    "id": "1",
    "created": "2017-01-03T12:00:00Z",
    "type": "rsync",
    "name": "",
    "url": "",
    "parameters": [],
    "application_id": "1234",
    "environment_id": null,
    "credential_id": null,
    "template_id": null,
    "job_id": null
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($target, JSON_PRETTY_PRINT));
    }

    public function testFormatForRsync()
    {
        $target = (new Target('rsync'))
            ->withParameter('path', '/my/app/root');

        $this->assertSame('RSync', $target->formatType());
        $this->assertSame('/my/app/root', $target->formatParameters());
    }

    public function testFormatForEB()
    {
        $target = (new Target('eb'))
            ->withParameter('application', 'BeanstalkApp')
            ->withParameter('environment', 'e-1234abcd');

        $this->assertSame('Elastic Beanstalk', $target->formatType());
        $this->assertSame('e-1234abcd', $target->formatParameters());
    }

    public function testFormatForS3()
    {
        $target = (new Target('s3'))
            ->withParameter('bucket', 'bucket-name')
            ->withParameter('path', 'file.tar.gz');

        $this->assertSame('S3', $target->formatType());
        $this->assertSame('bucket-name/file.tar.gz', $target->formatParameters());
    }

    public function testFormatS3WithoutPath()
    {
        $target = (new Target('s3'))
            ->withParameter('bucket', 'bucket-name');

        $this->assertSame('S3', $target->formatType());
        $this->assertSame('bucket-name', $target->formatParameters());
    }

    public function testFormatS3WithSource()
    {
        $target = (new Target('s3'))
            ->withParameter('bucket', 'bucket-name')
            ->withParameter('path', 'file.tar.gz')
            ->withParameter('source', 'appdist/folder');

        $this->assertSame('S3', $target->formatType());
        $this->assertSame('appdist/folder:bucket-name/file.tar.gz', $target->formatParameters());
    }

    public function testFormatForCD()
    {
        $target = (new Target('cd'))
            ->withParameter('application', 'DemoApp')
            ->withParameter('group', 'DemoFleet')
            ->withParameter('configuration', 'CodeDeploy.HalfAtATime');

        $this->assertSame('CodeDeploy', $target->formatType());
        $this->assertSame('DemoFleet', $target->formatParameters());
    }

    public function testFormatForScript()
    {
        $target = (new Target('script'))
            ->withParameter('context', 'test1');

        $this->assertSame('Script', $target->formatType());
        $this->assertSame('test1', $target->formatParameters());
    }
}
