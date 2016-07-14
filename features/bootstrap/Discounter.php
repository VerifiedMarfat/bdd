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

        $offers = $this->find($name);

        return ( 0 < count($offers) );
    }

    /**
     * Validates the keys of the order.
     * @param [array] $order
     * @return bool
     */
    public function validateKeys($order) {
        $order = $this->make($order, false);

        foreach ( $this->fields as $field ) {
            foreach ( $order as $item ) {
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

        foreach ( $orders as $order ) {
            if ( strtolower($order['title']) === strtolower($name) ) {
                return $order['title'];
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

        $code = $this->findCode($offer['name']);

        switch ( $code ) {
            case 'halfprice':
                return '50%';
            default:
                return false;
        }
    }

    /**
     * Returns the new total based on discount
     * @param [string] $offer
     * @param [bool] $onOffer
     * @return float
     */
    public function total($code, $onOffer = false) {
        $offers = $this->find($code, 'code');
        $total = $this->calculate($offers);
        $isDiscounted = ( $onOffer  && 0 < count($offers) );

        if ( $isDiscounted ) {
            foreach ( $offers as $offer ) {
                switch ( $offer['code'] ) {
                    case '3for2':
                        if ( strtolower('Rimmel Lasting Finish Lipstick 4g') === strtolower($offer['title']) ) {
                            $total -= $offer['price'];
                        }
                        break;
                    case 'halfprice':
                        if ( 'conditioner' === $offer['category']) {
                            $total -= ($offer['price'] / 2);
                        }
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
    public function make($order, $isXML = true) {
        $items = [];

        if ( $isXML ) {

            if (  isset($order->products->product) ) {
                $products = $order->products->product;

                foreach ($products as $product) {
                    if ( isset($product->category) && null !== $product->attributes() ) {
                        $items[] = [
                            'category' => strtolower($product->category),
                            'title'    => $product['title'],
                            'price'    => (float) $product['price'],
                        ];
                    }
                }
            }

        } else if ( !$isXML ) {

            foreach ( $order as $item ) {
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
            if ( !isset($item[$field]) ) {
                break;
            }
            if ( strtolower($name) === strtolower($item[$field]) ) {
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
    public function findCode($name) {
        $offers = $this->find($name);

        return $offers[0]['code'];
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
            'name'     => '3 for the price of 2',
            'code'     => '3for2',
            'category' => 'lipstick',
            'title'    => 'Rimmel Lasting Finish Lipstick 4g',
            'price'    => 4.99,
        ];

        $items[] = [
            'name'     => '3 for the price of 2',
            'code'     => '3for2',
            'category' => 'lipstick',
            'title'    => 'bareMinerals Marvelous Moxie Lipstick 3.5g',
            'price'    => 13.95,
        ];

        $items[] = [
            'name'     => '3 for the price of 2',
            'code'     => '3for2',
            'category' => 'lipstick',
            'title'    => 'Rimmel Kate Lasting Finish Matte Lipstick',
            'price'    => 5.49,
        ];

        $items[] = [
            'name'     => 'Buy Shampoo & get Conditioner for 50% off',
            'code'     => 'halfprice',
            'category' => 'shampoo',
            'title'    => 'Sebamed Anti-Dandruff Shampoo 200ml',
            'price'    => 4.99,
        ];

        $items[] = [
            'name'     => 'Buy Shampoo & get Conditioner for 50% off',
            'code'     => 'halfprice',
            'category' => 'conditioner',
            'title'    => 'L\'Oréal Paris Hair Conditioner 250ml',
            'price'    => 5.5,
        ];

        return $items;
    }

    /**
     * Returns the total amount of the order.
     * @return array
     */
    private function calculate($orders) {
        $total = 0;

        foreach ( $orders as $order ) {
            $total += $order['price'];
        }

        return $total;
    }
}