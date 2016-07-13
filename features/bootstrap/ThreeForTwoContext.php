<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;

/**
 * Defines application features from the specific context.
 */
class ThreeForTwoContext implements Context, SnippetAcceptingContext
{
    private $code;
    private $discounter;
    private $discount;
    private $offer;
    private $order;
    
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->discounter = new Discounts();
    }
    
    /**
     * @When the following products are put on the order
     */
    public function theFollowingProductsArePutOnTheOrder(TableNode $table)
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

    /**
     * @Given the :arg1 offer is enabled
     */
    public function theOfferIsEnabled($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then I should get the :arg1 for free
     */
    public function iShouldGetTheForFree($arg1)
    {
        throw new PendingException();
    }
}
