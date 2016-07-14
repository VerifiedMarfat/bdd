<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Tester\Exception\PendingException;

/**
 * Defines application features from the specific context.
 */
class HalfPriceContext implements Context, SnippetAcceptingContext
{
    private $discounter;

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
     * @Given the :offer offer is enabled
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
     * @Then I should get a :discount discount on :item
     */
    public function iShouldGetADiscountOn($discount, $item)
    {
        $validItem = $this->discounter->validateItem($item, $this->offer['name']);
        $amount = $this->discounter->amount($this->offer);
        PHPUnit_Framework_Assert::assertEquals($item, $validItem);
        PHPUnit_Framework_Assert::assertEquals($discount, $amount);
    }

    /**
     * @Then the order total should be :amount
     */
    public function theOrderTotalShouldBe($amount)
    {
        $code = $this->discounter->findcode($this->offer['name']);
        $total = $this->discounter->total($code, true);
        PHPUnit_Framework_Assert::assertEquals($amount, $total);
    }
}
