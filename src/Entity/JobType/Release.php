<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity\JobType;

use Hal\Core\Entity\Application;
use Hal\Core\Entity\Target;
use Hal\Core\Entity\Job;
use QL\MCP\Common\Time\TimePoint;

class Release extends Job
{
    /**
     * @var Build
     */
    protected $build;

    /**
     * @var Application|null
     */
    protected $application;

    /**
     * @var Target|null
     */
    protected $target;

    /**
     * @param string $id
     * @param TimePoint|null $created
     */
    public function __construct($id = '', TimePoint $created = null)
    {
        parent::__construct($id, $created);

        $this->build = null;
        $this->application = null;
        $this->target = null;
    }

    /**
     * @return Build
     */
    public function build()
    {
        return $this->build;
    }

    /**
     * @return Application|null
     */
    public function application()
    {
        return $this->application;
    }

    /**
     * @return Target|null
     */
    public function target()
    {
        return $this->target;
    }

    /**
     * @param Build $build
     *
     * @return self
     */
    public function withBuild(Build $build)
    {
        $this->build = $build;
        return $this;
    }

    /**
     * @param Application|null $application
     *
     * @return self
     */
    public function withApplication(Application $application = null)
    {
        $this->application = $application;
        return $this;
    }

    /**
     * @param Target|null $target
     *
     * @return self
     */
    public function withTarget(Target $target = null)
    {
        $this->target = $target;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        $json = [
            'id' => $this->id(),
            'created' => $this->created(),

            'build_id' => $this->build() ? $this->build()->id() : null,

            'application_id' => $this->application() ? $this->application()->id() : null,

            'target_id' => $this->target() ? $this->target()->id() : null,
        ];

        return $json;
    }
}
