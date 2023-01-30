<?php

$product = Product::getProductById($_GET['id']);

if ( !Authentication::isLoggedIn() )
{
  header('Location: /login');
  exit;
}

CSRF::generateToken( 'delete_comment_form_2' );

// Step 2: make sure it's POST request
if ( $_SERVER["REQUEST_METHOD"] === 'POST' ) {

  // step 3: do error check
  $error = FormValidation::validate(
    $_POST,
    [
      'comment_id' => 'required',
      'csrf_token' => 'delete_comment_form_2_csrf_token'
    ]
  );

  // make sure there is no error
  if ( !$error ) {
    // step 4: delete user
    Comment::delete( $_POST['comment_id'] );

    // step 5: remove CSRF token
    CSRF::removeToken( 'delete_comment_form_2' );

    // step 6: redirect back to the same page
    header("Location: /product?id=".$product['id']);
    exit;

  } // end - $error

} // end - $_SERVER["REQUEST_METHOD"]

require dirname(__DIR__) . '/parts/header.php';

?>
    <div class="container mt-5 mb-2 mx-auto text-white" style="max-width: 1000px;">
      <div class="min-vh-100">
        <div class="d-flex justify-content-between align-items-center mb-4 py-3">
          <h1 class="h1"><?php echo $product['name']; ?></h1>
          <div class="d-flex align-items-center justify-content-end">
            <a href="/cart" class="btn btn-light" style="font-size: 20px">
              <i class="bi bi-cart4"></i>
            </a>
          </div>
        </div>

        <!-- products -->
        <div class="row row-cols-1 bg-dark py-5 px-3 rounded">
          <div class="d-flex justify-content-center">
            <img
            src="<?php echo $product['img_url']; ?>"
            class="rounded border border-light col-5 m-auto"
            style="width: 350px; height: 420px;"
            />
            <div class="px-4 col-7 py-2">
                <h3 class="title">Name : <?php echo $product['name']; ?></h3>
                <br>
                <h4 class="type">Type : <?php echo $product['type']; ?></h4>
                <h4 class="text">Price : $<?php echo $product['price']; ?></h4>
                <br>
                <h5 class="developer">Developer : <?php echo $product['developer']; ?></h5>
                <h5 class="publisher">Publisher : <?php echo $product['publisher']; ?></h5>
                <br>
                <h6 class="time">Release Time : <?php echo $product['release_time']; ?></h6>
                <br>
                <form method="POST" action="/cart">
                  <input 
                    type="hidden"
                    name="product_id"
                    value="<?php echo $product['id']; ?>"
                  >
                  <button class="btn btn-secondary">Add to cart</button>
                </form>
            </div>
          </div>
          <div class="px-5 pt-5"><?php echo nl2br($product['introduce']); ?></div>
        </div>
        <br>
        <br>  

        <?php foreach (Comment::getCommentByProductId($product['id']) as $comment) : ?>
        <div class="row">
          <div class="d-flex px-5 py-3 col-9  bg-dark rounded">
            <div class="d-flex flex-column justify-content-center align-items-center col-2">
              <i class="bi bi-file-person" style="font-size:120px;"></i>
              <h4><?php echo User::getUserNameById($comment['user_id'])['name']; ?></h4>
            </div>
            <div class="ps-4 col-10 pt-3">
              <div class="recommend bg-secondary rounded px-3 py-1 d-flex align-items-center m-auto">
                <?php 
                switch($comment['recommend']){
                  case '1':
                    echo '<i class="bi bi-emoji-smile fs-3 pe-3"></i>
                          <h3 class="mt-2">Recommend</h3>';
                    break;
                  case '-1':
                    echo '<i class="bi bi-emoji-smile-upside-down fs-3 pe-3"></i>
                          <h3 class="mt-2">Not Recommend</h3>';
                    break;
                }
                ?>
              </div>
              <div class="pt-4">
                <p><?php echo nl2br($comment['comment']); ?></p>
                <div class="d-flex justify-content-between">
                  <p><?php echo $comment['created_at']; ?></p>
                  <?php if ( $_SESSION['user']['id'] == $comment['user_id'] ) : ?>
                  <button 
                    type="button" 
                    class="btn btn-danger btn-sm" 
                    data-bs-toggle="modal" 
                    data-bs-target="#post-<?php echo $comment['id']; ?>">
                    <i class="bi bi-trash"></i>
                  </button>

                  <!-- Modal -->
                  <div class="modal fade" id="post-<?php echo $comment['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content bg-dark">
                        <div class="modal-header">
                          <h1 class="modal-title fs-5" id="exampleModalLabel">Delete User</h1>
                          <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start">
                          Are you sure you want to delete this comment ?
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <form
                            method="POST"
                            action="<?php echo $_SERVER["REQUEST_URI"]; ?>"
                          >
                            <input 
                              type="hidden" 
                              name="comment_id" 
                              value="<?php echo $comment['id']; ?>" 
                            />
                            <input 
                              type="hidden" 
                              name="csrf_token" 
                              value="<?php echo CSRF::getToken( 'delete_comment_form_2' ); ?>"
                            />
                            <button type="submit" class="btn btn-danger">Delete</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <br>
        <?php endforeach; ?>

        <div class="row">
          <div class="col-2 bg-dark rounded">
              <a href="/comment?id=<?php echo $product['id']; ?>">
                <button>Do you want to comment</button>
              </a>
          </div>
        </div>

        <div class="row bg-dark col-3">

        </div>
      </div>

      <!-- footer -->
      <div class="d-flex justify-content-between align-items-center pt-4 pb-2">
        <div class="text-muted small btn btn-light">
          © 2022 <a href="/home" class="text-muted">My Store</a>
        </div>
        <div class="d-flex align-items-center gap-3">
        <?php if ( Authentication::isLoggedIn() ) : ?>
          <a href="/dashboard" class="btn btn-light btn-sm">Dashboard</a>
          <a href="/orders" class="btn btn-light btn-sm">My Orders</a>
          <a href="/logout" class="btn btn-light btn-sm">Logout</a>
        <?php else : ?>
          <a href="/login" class="btn btn-light btn-sm">Login</a>
          <a href="/signup" class="btn btn-light btn-sm">Sign Up</a>
        <?php endif; ?>
        </div>
      </div>
    </div>

<?php require dirname(__DIR__) . '/parts/footer.php'; ?>