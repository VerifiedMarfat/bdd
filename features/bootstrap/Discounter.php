<?php

final class Discounter {

    /**
     * Checks whether the offer is enabled.
     * @param [string] $name
     * @param [bool] $default
     * @return bool
     */
    public function onOffer($name, $default = null) {
        if ( isset($default) ) {
            return $default;
        }

        $discounts = $this->all();

        foreach ($discounts as $discount) {
            if ($name === $discount['name']) {
                return true;
            }
        }
        return false;
    }

    /**
     * Validates the keys of the order.
     * @param [array] $order
     * @return bool
     */
    public function validate($order) {
        $order = $this->make($order);

        foreach ($this->fields as $field) {
            foreach ($order as $item) {
                if ( !array_key_exists($field, $item) ) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Returns the amount of discount to apply to total
     * @param [string] $offer
     * @param [bool] $onOffer
     * @return float
     */
    public function amount($offer, $onOffer) {
        if ( !$onOffer ) {
            return 0;
        }

        $offer = $this->find($offer);

        switch ($offer[0]['code']) {
            case '3for2':
                return 4.99;
            default:
                return 0;
                break;
        }
    }

    /**
     * Returns the new total based on discount
     * @param [string] $offer
     * @param [bool] $onOffer
     * @return float
     */
    public function total($offer, $onOffer) {
        $offers = $this->find($offer);
        $total = 0;

        if ( !$onOffer ) {

            foreach ($offers as $offer) {
                $total += $offer['price'];
            }
        }
        return (float) $total;
    }

    /**
     * Converts the data into an array
     * @param [json] $order
     * @return array
     */
    private function make($order) {
        $items = [];

        foreach ($order as $item) {
            if ( !isset($item['Category']) || !isset($item['Title']) ) {
                break;
            }

            $price = ( isset($item['Price']) ) ? $item['Price'] : 0;
            $items[] = [
                'category' => $item['Category'],
                'title'    => $item['Title'],
                'price'    => (float) $price,
            ];
        }
        return $items;
    }

    /**
     * Fields with read/write permissions.
     * @return array
     */
    private $fields = ['category', 'title', 'price'];

    /**
     * Finds the items in the specified offer.
     * @param [string] $name
     * @param [string] $field
     * @return array
     */
    private function find($name, $field = 'name') {
        $items = [];
        $offers = $this->all();

        foreach ( $offers as $item ) {
            if ( strtolower($name) === $item[$field] ) {
                $items[] = $item;
            }
        }
        return $items;
    }

    /**
     * Returns all the available offers with the related items.
     * @return array
     */
    private function all() {
        $items = [];
        $items[] = [
            'name'     => strtolower('3 for the price of 2'),
            'code'     => '3for2',
            'category' => 'lipstick',
            'title'    => strtolower('Rimmel Lasting Finish Lipstick 4g'),
            'price'    => 4.99,
        ];
        $items[] = [
            'name'     => strtolower('3 for the price of 2'),
            'code'     => '3for2',
            'category' => 'lipstick',
            'title'    => strtolower('bareMinerals Marvelous Moxie Lipstick 3.5g'),
            'price'    => 13.95,
        ];
        $items[] = [
            'name'     => strtolower('3 for the price of 2'),
            'code'     => '3for2',
            'category' => 'lipstick',
            'title'    => strtolower('Rimmel Kate Lasting Finish Matte Lipstick'),
            'price'    => 5.49,
        ];
        $items[] = [
            'name'     => strtolower('Buy Shampoo & get Conditioner for 50% off'),
            'code'     => 'halfprice',
            'category' => 'shampoo',
            'title'    => strtolower('Sebamed Anti-Dandruff Shampoo 200ml'),
        ];
        $items[] = [
            'name'     => strtolower('Buy Shampoo & get Conditioner for 50% off'),
            'code'     => 'halfprice',
            'category' => 'conditioner',
            'title'    => strtolower('L\'Oréal Paris Hair Conditioner 250ml'),
        ];

        return $items;
    }

}