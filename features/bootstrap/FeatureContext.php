<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Behat\MinkExtension\Context\MinkContext;

/**
 * Features context.
 */
class FeatureContext extends MinkContext
{
    private $params = [];
    private $output;
    private $defaultDriver;

    /**
     * @param array $params
     */
    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * @Then /^I wait for the suggestion box to appear$/
     */
    public function iWaitForTheSuggestionBoxToAppear()
    {
        $this->getSession()->wait(5000,
            "$('.suggestions-results').children().length > 0"
        );
    }


    /** @Given /^I am in a directory "([^"]*)"$/ */
    public function iAmInADirectory($dir)
    {
        if (!file_exists($dir)) {
            mkdir($dir);
        }
        chdir($dir);
    }

    /** @Given /^I have a file named "([^"]*)"$/ */
    public function iHaveAFileNamed($file)
    {
        touch($file);
    }

    /** @When /^I run "([^"]*)"$/ */
    public function iRun($command)
    {
        exec($command, $output);
        $this->output = trim(implode("\n", $output));
    }

    /** @Then /^I should get:$/ */
    public function iShouldGet(PyStringNode $string)
    {
        if ((string) $string !== $this->output) {
            throw new Exception(
                "Actual output is:\n" . $this->output
            );
        }
    }

    /**
     * @Given /^"([^"]*)" starts session$/
     * @param $user
     */
    public function userStartsSession($user)
    {
        // actual starting browser
        $session1 = $this->getSession();
        //$session1->visit('/');
        $driver1 = $session1->getDriver();
        $driver2 = clone $driver1;
        $driver2->setWebDriver(new \WebDriver\WebDriver());
        $session2 = new \Behat\Mink\Session($driver2);
        $this->getMink()->registerSession($user, $session2);
        $session2->start();
    }

    /**
     * @Given /^"([^"]*)" visits "([^"]*)"$/
     * @param $user
     * @param $page
     */
    public function userVisits($user, $page)
    {
        $this->getMink()->setDefaultSessionName($user);
        $this->visit($page);
        $this->getSession()->wait(1000);
        //var_dump($user, $page);
    }
}