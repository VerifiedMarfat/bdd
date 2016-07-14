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
    private $discounter;
    private $offer;
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
     * @Given the :offerName offer is disabled
     */
    public function theOfferIsDisabled($offerName)
    {
        $onOffer = $this->discounter->onOffer($offerName, false);
        $this->offer = [
            'name'    => $offerName,
            'onOffer' => $onOffer
        ];
        PHPUnit_Framework_Assert::assertTrue(!$this->offer['onOffer']);
    }

    /**
     * @When the following products are put on the order
     */
    public function theFollowingProductsArePutOnTheOrder(TableNode $order)
    {
        $valid = $this->discounter->validateKeys($order);
        PHPUnit_Framework_Assert::assertTrue($valid);
    }

    /**
     * @Then I should not get anything for free
     */
    public function iShouldNotGetAnythingForFree()
    {
        $discount = $this->discounter->amount($this->offer);
        PHPUnit_Framework_Assert::assertEquals(0, $discount);
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
