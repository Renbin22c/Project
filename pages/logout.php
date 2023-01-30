<?php

// make sure user is logged in
if ( Authentication::isLoggedIn() ) {
    $cart = new Cart();
    $cart->emptyCart();
    // only if the user is logged-in, then only you trigger logout
    Authentication::logout();
}

header('Location: /login');
exit;

?>