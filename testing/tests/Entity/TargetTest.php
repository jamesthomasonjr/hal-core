<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use PHPUnit_Framework_TestCase;

class TargetTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultValues()
    {
        $target = new Target;

        $this->assertStringMatchesFormat('%x', $target->id());
        $this->assertSame('', $target->name());
        $this->assertSame('', $target->url());

        $this->assertSame([], $target->parameters());

        $this->assertSame(null, $target->application());
        $this->assertSame(null, $target->group());

        $this->assertSame(null, $target->credential());
        $this->assertSame(null, $target->release());
    }

    public function testProperties()
    {
        $application = new Application;
        $group = new Group;
        $credential = new Credential;
        $release = new Release;

        $target = (new Target('1234'))
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
            ->withGroup($group)
            ->withRelease($release)
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
        $this->assertSame($group, $target->group());
        $this->assertSame($credential, $target->credential());
        $this->assertSame($release, $target->release());
    }

    public function testSerialization()
    {
        $application = new Application('1');
        $group = new Group('2');
        $credential = new Credential('3');
        $release = new Release('4');

        $target = (new Target)
            ->withID('1234')
            ->withName('deployment name')
            ->withURL('http://example.com')

            ->withParameters([
                'name' => 'DemoApplication',
                'group' => 'DemoFleet',
                'configuration' => 'CodeDeployDefault.HalfAtATime'
            ])
            ->withParameter('bucket', 'bucket-name')

            ->withApplication($application)
            ->withGroup($group)
            ->withCredential($credential)
            ->withRelease($release);

        $expected = <<<JSON
{
    "id": "1234",
    "name": "deployment name",
    "url": "http://example.com",
    "parameters": {
        "name": "DemoApplication",
        "group": "DemoFleet",
        "configuration": "CodeDeployDefault.HalfAtATime",
        "bucket": "bucket-name"
    },
    "application_id": "1",
    "group_id": "2",
    "credential_id": "3",
    "release_id": "4"
}
JSON;

        $this->assertSame($expected, json_encode($target, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    public function testDefaultSerialization()
    {
        $target = new Target('1');

        $expected = <<<JSON
{
    "id": "1",
    "name": "",
    "url": "",
    "parameters": [],
    "application_id": null,
    "group_id": null,
    "credential_id": null,
    "release_id": null
}
JSON;

        $this->assertSame($expected, json_encode($target, JSON_PRETTY_PRINT));
    }

    public function testFormatWithName()
    {
        $target = (new Target)
            ->withName('target name')
            ->withParameter('path', '/my/app/root');

        $this->assertSame('target name', $target->format());
    }

    public function testFormatWithoutGroup()
    {
        $target = (new Target)
            ->withParameter('path', '/my/app/root');

        $this->assertSame('Unknown', $target->format());
        $this->assertSame('Unknown', $target->formatParameters());
    }

    public function testFormatForRsync()
    {
        $group = (new Group(null, 'rsync'))->withName('hostname');
        $target = (new Target)
            ->withParameter('path', '/my/app/root')
            ->withGroup($group);

        $this->assertSame('RSync (/my/app/root)', $target->format());
        $this->assertSame('RSync', $target->format(true));
        $this->assertSame('/my/app/root', $target->formatParameters());
    }

    public function testFormatForEB()
    {
        $group = (new Group(null, 'eb'))->withName('us-east-1');
        $target = (new Target)
            ->withParameter('application', 'BeanstalkApp')
            ->withParameter('environment', 'e-1234abcd')
            ->withGroup($group);

        $this->assertSame('EB (e-1234abcd)', $target->format());
        $this->assertSame('Elastic Beanstalk', $target->format(true));
        $this->assertSame('e-1234abcd', $target->formatParameters());
    }

    public function testFormatForS3()
    {
        $group = (new Group(null, 's3'))->withName('us-west-1');
        $target = (new Target)
            ->withParameter('bucket', 'bucket-name')
            ->withParameter('path', 'file.tar.gz')
            ->withGroup($group);

        $this->assertSame('S3 (bucket-name/file.tar.gz)', $target->format());
        $this->assertSame('S3', $target->format(true));
        $this->assertSame('bucket-name/file.tar.gz', $target->formatParameters());
    }

    public function testFormatS3WithoutPath()
    {
        $group = (new Group(null, 's3'))->withName('us-west-1');
        $target = (new Target)
            ->withParameter('bucket', 'bucket-name')
            ->withGroup($group);

        $this->assertSame('S3 (bucket-name)', $target->format());
        $this->assertSame('S3', $target->format(true));
        $this->assertSame('bucket-name', $target->formatParameters());
    }

    public function testFormatS3WithSource()
    {
        $group = (new Group(null, 's3'))->withName('us-west-1');
        $target = (new Target)
            ->withParameter('bucket', 'bucket-name')
            ->withParameter('path', 'file.tar.gz')
            ->withParameter('source', 'appdist/folder')
            ->withGroup($group);

        $this->assertSame('S3 (appdist/folder:bucket-name/file.tar.gz)', $target->format());
        $this->assertSame('S3', $target->format(true));
        $this->assertSame('appdist/folder:bucket-name/file.tar.gz', $target->formatParameters());
    }

    public function testFormatForCD()
    {
        $group = (new Group(null, 'cd'))->withName('us-west-1');
        $target = (new Target)
            ->withParameter('application', 'DemoApp')
            ->withParameter('group', 'DemoFleet')
            ->withParameter('configuration', 'CodeDeploy.HalfAtATime')
            ->withGroup($group);

        $this->assertSame('CD (DemoFleet)', $target->format());
        $this->assertSame('CodeDeploy', $target->format(true));
        $this->assertSame('DemoFleet', $target->formatParameters());
    }

    public function testFormatForScript()
    {
        $target = (new Target)
            ->withParameter('context', 'test1')
            ->withGroup(new Group(null, 'script'));

        $this->assertSame('Script (test1)', $target->format());
        $this->assertSame('Script', $target->format(true));
        $this->assertSame('test1', $target->formatParameters());
    }
}
