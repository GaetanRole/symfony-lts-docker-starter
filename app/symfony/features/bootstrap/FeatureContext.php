<?php

use Behat\Behat\Context\Context;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * @see http://behat.org/en/latest/quick_start.html
 */
class FeatureContext implements Context
{
    /** @var KernelInterface */
    private $kernel;

    /** @var Response|null */
    private $response;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @When a demo scenario sends a request to :path
     *
     * @throws Exception When an Exception occurs during handle processing
     */
    public function aDemoScenarioSendsARequestTo(string $path): void
    {
        $this->response = $this->kernel->handle(Request::create($path));
    }

    /**
     * @Then the response should be received
     */
    public function theResponseShouldBeReceived(): void
    {
        if (null === $this->response) {
            throw new RuntimeException('No response received');
        }
    }
}
