<?php

if ( !Authentication::isLoggedIn() )
{
  header('Location: /login');
  exit;
}

if ( $_SERVER["REQUEST_METHOD"] == 'POST' ) {

  // if $_POST['action] is remove, then proceed removeProductFromCart function
  if ( isset( $_POST['action'] ) && $_POST['action'] == 'remove' ) {
      // remove product from cart
      Cart::removeProductFromCart( $_POST['product_id'] );
  } else {
      
      // make sure product_id is available in $_POST
      if ( isset( $_POST['product_id'] ) ) 
      {
          // add product_id into cart
          Cart::add( $_POST['product_id'] );
      }

  }

}

require dirname(__DIR__) . '/parts/header.php';

?>
    <div class="container mt-5 mb-2 mx-auto" style="max-width: 900px">
      <div class="min-vh-100">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h1 class="h1 text-white"><?php echo $_SESSION['user']['name'] ?>'s cart</h1>
        </div>

        <!-- List of products user added to cart -->
        <table
          class="table table-hover table-bordered table-striped table-dark border-white"
        >
          <thead>
            <tr>
              <th scope="col">Product</th>
              <th scope="col">Price</th>
              <th scope="col">Quantity</th>
              <th scope="col">Total</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
          <tbody class="table-group-divider">
            <?php if ( empty( Cart::listAllProductsinCart() ) ) : ?>
              <tr>
                <td colspan="5">Your cart is empty.</td>
              </tr>
            <?php else : ?>
              <?php foreach( Cart::listAllProductsinCart() as $product ) : ?>
                <tr>
                  <td><?php echo $product['name']; ?></td>
                  <td>$ <?php echo $product['price']; ?></td>
                  <td><?php echo $product['quantity']; ?></td>
                  <td>$ <?php echo $product['total']; ?></td>
                  <td>
                    <form
                      method="POST"
                      action="<?php echo $_SERVER["REQUEST_URI"]; ?>"
                    >
                      <input 
                        type="hidden" 
                        name="action" 
                        value="remove" 
                      />
                      <input 
                        type="hidden"
                        name="product_id"
                        value="<?php echo $product['id']; ?>"
                      />
                      <button class="btn btn-danger btn-sm">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  </td>
                </tr>
                <?php endforeach; ?>
                <tr>
                  <td colspan="3" class="text-end">Total</td>
                  <td colspan="2">$ <?php echo Cart::total(); ?></td>
                </tr>
            <?php endif; ?>
          </tbody>
        </table>

        <div class="d-flex justify-content-between align-items-center my-3">
          <a href="/home" class="btn btn-secondary btn-sm"
            >Continue Shopping</a
          >
          <?php if ( !empty( Cart::listAllProductsinCart() ) ) : ?>
            <form 
              method="POST"
              action="/checkout"
            >
              <button class="btn btn-light">Checkout</a>
            </form>
          </div>
            <?php endif; ?>
        </div>
      </div>
<?php require dirname(__DIR__) . '/parts/footer.php'; ?>
