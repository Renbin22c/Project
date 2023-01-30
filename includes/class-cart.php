<?php

class Cart{
    public static function listAllProductsinCart()
    {
        $list = [];

        // check if cart is empty or not
        if ( isset( $_SESSION['cart'] ) ) {
            foreach( $_SESSION['cart'] as $product_id => $quantity ) {

                $product = Product::getProductById( $product_id );

                // push product_id and quantity into $list
                $list[] = [
                    'id' => $product_id,
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'total' => $product['price'] * $quantity,
                    'quantity' => $quantity
                ];
            } // end - foreach
        } // end - isset( $_SESSION['cart'] )

        return $list;
    }

    public static function total()
    {
        $cart_total = 0;

        // calculate the cart total
        foreach ( Cart::listAllProductsinCart() as $product ) {
            $cart_total += $product['total'];
        }

        return $cart_total;
    }

    public static function add( $product_id )
    {
        // check if there is existing data in $_SESSION['cart']
        if ( isset( $_SESSION['cart'] ) ) {
            // assign $_SESSION['cart'] to $cart
           $cart = $_SESSION['cart']; 
        } else {
            // if no existing data, create empty array for $cart
            $cart = [];
        }

        // add product_id to $cart
        // check if given product_id is already exists in the cart or not
        if ( isset( $cart[ $product_id ] ) )
        {
            // add one quantity into the existing value
            $cart[ $product_id ] += 1; // plus one quantity
        } else {
            $cart[ $product_id ] = 1; // quantity
        }
        
        // assign $cart to $_SESSION['cart']
        $_SESSION['cart'] = $cart;
    }

    /**
     * remove product from cart
     */
    public static function removeProductFromCart( $product_id )
    {
        // make sure the product_id is already in the cart session data
        if ( isset( $_SESSION['cart'][$product_id] ) ) {
            // unset it (means delete the selected product data)
            unset( $_SESSION['cart'][$product_id] );
        }
    }

    /**
     * Empty the cart
     */
    public static function emptyCart()
    {
        unset( $_SESSION['cart'] );
    }
}

?>