<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given the :arg1 offer is disabled
     */
    public function theOfferIsDisabled($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When the following products are put on the order
     */
    public function theFollowingProductsArePutOnTheOrder(TableNode $table)
    {
        throw new PendingException();
    }

    /**
     * @Then I should not get anything for free
     */
    public function iShouldNotGetAnythingForFree()
    {
        throw new PendingException();
    }

    /**
     * @Then the order total should be :arg1
     */
    public function theOrderTotalShouldBe($arg1)
    {
        throw new PendingException();
    }

}
