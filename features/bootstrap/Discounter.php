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

        foreach ($this->all() as $discount) {
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
    public function validateKeys($order) {
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
     * Validates the keys of the order.
     * @param [array] $order
     * @return bool
     */
    public function validateItem($name, $offerName) {
        $orders = $this->find($offerName);

        foreach ($orders as $order) {
            if ( $order['title'] === strtolower($name) ) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns the amount of discount to apply to total
     * @param [string] $offer
     * @param [bool] $onOffer
     * @return float
     */
    public function amount($offer) {
        if ( !$offer['onOffer'] ) {
            return 0;
        }
    }

    /**
     * Returns the new total based on discount
     * @param [string] $offer
     * @param [bool] $onOffer
     * @return float
     */
    public function total($order) {
        $offers = $this->find($order['name']);
        $total = $this->calculate($offers);

        if ( $order['onOffer'] ) {

            foreach ($offers as $offer) {
                if ( $offer['name'] === $order['name'] ) {
                    $total -= $offer['price'];
                    break;
                }
            }
        }
        return (float) $total;
    }

    /**
     * Converts the data into an array
     * @param [json] $order
     * @return array
     */
    public function make($order) {
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
     * Finds the items in the specified offer.
     * @param [string] $name
     * @param [string] $field
     * @return array
     */
    public function find($name, $field = 'name') {
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
     * Returns the promo code
     * @param [string] $name
     * @return string
     */
    private function findCode($name) {
        return $offer[0]['code'];
    }

    /**
     * Fields with read/write permissions.
     * @return array
     */
    private $fields = ['category', 'title', 'price'];

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

    /**
     * Returns the total amount of the order.
     * @return array
     */
    private function calculate($orders) {
        $total = 0;

        foreach ($orders as $order) {
            $total += $order['price'];
        }
        return $total;
    }
}