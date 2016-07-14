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
    private $discounter;
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
        $this->discounter = new Discounter();
    }

    /**
     * @Given the :offerName offer is enabled
     */
    public function theOfferIsEnabled($offerName)
    {
        $onOffer = $this->discounter->onOffer($offerName);
        $this->offer = [
            'name'    => $offerName,
            'onOffer' => $onOffer
        ];
        PHPUnit_Framework_Assert::assertTrue($this->offer['onOffer']);
    }

    /**
     * @When the following products are put on the order
     */
    public function theFollowingProductsArePutOnTheOrder(TableNode $order)
    {
        $this->order = $order;
        $valid = $this->discounter->validateKeys($this->order);
        PHPUnit_Framework_Assert::assertTrue($valid);
    }

    /**
     * @Then I should get the :item for free
     */
    public function iShouldGetTheForFree($item)
    {
        $validItem = $this->discounter->validateItem($item, $this->offer['name']);
        $this->offer['title'] = $validItem;
        PHPUnit_Framework_Assert::assertEquals($item, $validItem);
    }

    /**
     * @Then the order total should be :amount
     */
    public function theOrderTotalShouldBe($amount)
    {
        $total = $this->discounter->total($this->offer);
        PHPUnit_Framework_Assert::assertEquals($amount, $total);
    }

}
