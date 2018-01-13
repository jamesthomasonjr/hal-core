<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity;

use Hal\Core\Type\EnumException;
use PHPUnit\Framework\TestCase;
use QL\MCP\Common\Time\TimePoint;

class TargetTemplateTest extends TestCase
{
    public function testDefaultValues()
    {
        $template = new TargetTemplate;

        $this->assertRegExp('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $template->id());
        $this->assertInstanceOf(TimePoint::class, $template->created());

        $this->assertSame('rsync', $template->type());
        $this->assertSame('', $template->name());
        $this->assertSame([], $template->parameters());

        $this->assertSame(null, $template->application());
        $this->assertSame(null, $template->organization());
        $this->assertSame(null, $template->environment());
    }

    public function testProperties()
    {
        $app = new Application;
        $env = new Environment;
        $org = new Organization;

        $template = (new TargetTemplate('rsync', '1234'))
            ->withName('hostname')
            ->withParameter('this', 'that')
            ->withApplication($app)
            ->withEnvironment($env)
            ->withOrganization($org);

        $this->assertSame('1234', $template->id());
        $this->assertSame('rsync', $template->type());
        $this->assertSame('hostname', $template->name());
        $this->assertSame('that', $template->parameter('this'));
    }

    public function testIsAWS()
    {
        $template = new TargetTemplate;

        $this->assertSame(true, $template->withType('cd')->isAWS());
        $this->assertSame(true, $template->withType('eb')->isAWS());
        $this->assertSame(true, $template->withType('s3')->isAWS());

        $this->assertSame(false, $template->withType('rsync')->isAWS());
        $this->assertSame(false, $template->withType('script')->isAWS());
    }

    public function testSerialization()
    {
        $template = (new TargetTemplate('script', '1234', new TimePoint(2018, 1, 1, 12, 0, 0, 'UTC')))
            ->withName('hostname')
            ->withEnvironment(new Environment('9101'));

        $expected = <<<JSON_TEXT
{
    "id": "1234",
    "created": "2018-01-01T12:00:00Z",
    "type": "script",
    "name": "hostname",
    "application_id": null,
    "organization_id": null,
    "environment_id": "9101"
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($template, JSON_PRETTY_PRINT));
    }

    public function testDefaultSerialization()
    {
        $template = new TargetTemplate('eb', '2', new TimePoint(2018, 1, 1, 12, 0, 0, 'UTC'));

        $expected = <<<JSON_TEXT
{
    "id": "2",
    "created": "2018-01-01T12:00:00Z",
    "type": "eb",
    "name": "",
    "application_id": null,
    "organization_id": null,
    "environment_id": null
}
JSON_TEXT;

        $this->assertSame($expected, json_encode($template, JSON_PRETTY_PRINT));
    }

    public function testInvalidEnumThrowsException()
    {
        $this->expectException(EnumException::class);
        $this->expectExceptionMessage('"derp" is not a valid TargetEnum option.');

        $template = new TargetTemplate('derp');
    }

    public function testFormat()
    {
        $template = new TargetTemplate;
        $this->assertSame('RSync', $template->formatType());
        $this->assertSame(false, $template->isAWS());

        $template->withType('rsync');
        $this->assertSame('RSync', $template->formatType());
        $this->assertSame(false, $template->isAWS());

        $template->withType('eb');
        $this->assertSame('Elastic Beanstalk', $template->formatType());
        $this->assertSame(true, $template->isAWS());

        $template->withType('cd');
        $this->assertSame('CodeDeploy', $template->formatType());
        $this->assertSame(true, $template->isAWS());

        $template->withType('s3');
        $this->assertSame('S3', $template->formatType());
        $this->assertSame(true, $template->isAWS());

        $template->withType('script');
        $this->assertSame('Script', $template->formatType());
        $this->assertSame(false, $template->isAWS());
    }
}
