<?php
/**
 * @copyright (c) 2017 Quicken Loans Inc.
 *
 * For full license information, please view the LICENSE distributed with this source code.
 */

namespace Hal\Core\Entity\JobType;

use Hal\Core\Entity\Application;
use Hal\Core\Entity\Environment;
use Hal\Core\Entity\Job;
use QL\MCP\Common\Time\TimePoint;

class Build extends Job
{
    /**
     * @var string
     */
    protected $reference;
    protected $commit;

    /**
     * @var Application|null
     */
    protected $application;

    /**
     * @var Environment|null
     */
    protected $environment;

    /**
     * @param string $id
     * @param TimePoint|null $created
     */
    public function __construct($id = '', TimePoint $created = null)
    {
        parent::__construct($id, $created);

        $this->reference = '';
        $this->commit = '';

        $this->application = null;
        $this->environment = null;
    }

    /**
     * @return string
     */
    public function reference()
    {
        return $this->reference;
    }

    /**
     * @return string
     */
    public function commit()
    {
        return $this->commit;
    }

    /**
     * @return Application|null
     */
    public function application()
    {
        return $this->application;
    }

    /**
     * @return Environment|null
     */
    public function environment()
    {
        return $this->environment;
    }

    /**
     * @param string $ref
     *
     * @return self
     */
    public function withReference($ref)
    {
        $this->reference = $ref;
        return $this;
    }

    /**
     * @param string $commit
     *
     * @return self
     */
    public function withCommit($commit)
    {
        $this->commit = $commit;
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
     * @param Environment|null $environment
     *
     * @return self
     */
    public function withEnvironment(Environment $environment = null)
    {
        $this->environment = $environment;
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

            'reference' => $this->reference(),
            'commit' => $this->commit(),

            'application_id' => $this->application() ? $this->application()->id() : null,
            'environment_id' => $this->environment() ? $this->environment()->id() : null,
        ];

        return $json;
    }
}
