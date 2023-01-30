<?php

if ( !Authentication::isLoggedIn() )
{
  header('Location: /signup');
  exit;
}

  $product = Product::getProductById($_GET['id']);

  // step 1: set CSRF token
  CSRF::generateToken( 'add_comment_form' );

  // step 2: make sure post request
  if ( $_SERVER["REQUEST_METHOD"] === 'POST' ) {

    // step 3: do error check
     $rules = [
      'comment' => 'required',
      'recommend' => 'required',
    ];

    $error = FormValidation::validate(
      $_POST,
      $rules
    );

    // make sure there is no error
    if ( !$error ) {

      // step 4 = add new product
      Comment::add(
        $_POST['comment'],
        $_POST['recommend'],
        $_SESSION['user']['id'],
        $product['id']
      );

      // step 5: remove the CSRF token
      CSRF::removeToken( 'add_comment_form' );

      // step 6: redirect to manage products page
      header("Location: /product?id=".$product['id']);
      exit;

    } // end - $error


  } // end - $_SERVER["REQUEST_METHOD"]

require dirname(__DIR__) . '/parts/header.php';

?>

    <div class="container mx-auto my-5 text-white" style="max-width: 700px;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1">Add New Comment</h1>
      </div>
      <div class="card mb-2 p-4">
        <?php require dirname( __DIR__ ) . '/parts/error_box.php'; ?> 
        <form
          method="POST"
          action="<?php echo $_SERVER["REQUEST_URI"]; ?>"
        >
          <div class="mb-3">
            <h3><u><?php echo $_SESSION['user']['name'] ?> comment</u></h3>
          </div>
          <div class="mb-3">
            <label for="post-content" class="form-label">Content</label>
            <textarea
              class="form-control"
              id="post-content"
              rows="10"
              name="comment"
            ></textarea>
          </div>
          <div class="mb-3">
            <label for="post-content" class="form-label">Recommend</label>
            <select class="form-control" id="post-status" name="recommend">
              <option value="1">Recommend</option>
              <option value="-1">Not recommended</option>
            </select>
          </div>
          <div class="text-end">
            <button type="submit" class="btn btn-secondary">Add</button>
          </div>
          <input
            type="hidden"
            name="csrf_token"
            value="<?php echo CSRF::getToken( 'add_comment_form' ); ?>"
          />
        </form>
      </div>
      <div class="text-center">
        <a href="/product?id=<?php echo $product['id']; ?>" class="btn btn-link btn-sm text-decoration-none text-white"
          ><i class="bi bi-arrow-left"></i> Back to Product</a
        >
      </div>
    </div>

    <?php require dirname(__DIR__) . '/parts/footer.php'; ?>
