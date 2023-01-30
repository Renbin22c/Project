<?php

// load post data
$product = Product::getProductById($_GET['id']);

// step 1: set CSRF token
CSRF::generateToken('edit_product_form');

// step 2: make sure post request
if ( $_SERVER["REQUEST_METHOD"] === 'POST' ) {

  // step 3: do error check
  $rules = [
    'name' => 'required',
    'price' => 'required',
    'type' => 'required',
    'status' => 'required',
    'img_url' => 'required',
    'release_time' => 'required',
    'developer' => 'required',
    'publisher' => 'required',
    'introduce' => 'required',
    'csrf_token' => 'edit_product_form_csrf_token'
  ];

  $error = FormValidation::validate(
    $_POST,
    $rules
  );

  // make sure there is no error
  if ( !$error ){
    // step 4: update post
    Product::update(
      $product['id'],
      $_POST['name'],
      $_POST['price'],
      $_POST['type'],
      $_POST['status'],
      $_POST['img_url'],
      $_POST['release_time'],
      $_POST['developer'],
      $_POST['publisher'],
      $_POST['introduce']
    );

    // step 5: remove CSRF token
    CSRF::removeToken('edit_product_form');

    // step 6: redirect back to manage posts page
    header("Location: /manage-products");
    exit;
  }
}

require dirname(__DIR__) . '/parts/header.php'; 

?>
    <div class="container mx-auto my-5" style="max-width: 700px;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1 text-white">Edit Post</h1>
      </div>
      <div class="card mb-2 p-4 text-white bg-dark">
        <?php require dirname( __DIR__ ) . '/parts/error_box.php'; ?>
        <form
          method="POST"
          action="<?php echo $_SERVER["REQUEST_URI"]; ?>"
          >
          <div class="mb-3">
            <div class="row">
                <div class="col">
                    <label for="product-name" class="form-label">Name</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="product-name" 
                        name="name"
                        value="<?php echo $product['name']; ?>"
                    />
                </div>
                <div class="col">
                    <label for="product-price" class="form-label">Price</label>
                    <input 
                        type="number" 
                        class="form-control" 
                        id="product-price"  
                        name="price"
                        value="<?php echo $product['price']; ?>"
                    />
                </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row">
                <div class="col">
                    <label for="product-type" class="form-label">Type</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="product-type" 
                        name="type"
                        value="<?php echo $product['type']; ?>"
                    />
                </div>
                <div class="col">
                    <label for="product-release_time" class="form-label">Release Time</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="product-release_time"  
                        name="release_time"
                        value="<?php echo $product['release_time']; ?>"
                    />
                </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="col">
                <label for="product-img_url" class="form-label">Image URL</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="product-img_url"  
                    name="img_url"
                    value="<?php echo $product['img_url']; ?>"
                />
            </div>
          </div>
          <div class="mb-3">
            <div class="row">
                <div class="col">
                    <label for="product-developer" class="form-label">Developer</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="product-developer" 
                        name="developer"
                        value="<?php echo $product['developer']; ?>"
                    />
                </div>
                <div class="col">
                    <label for="product-publisher" class="form-label">Publisher</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="product-publisher"  
                        name="publisher"
                        value="<?php echo $product['publisher']; ?>"
                    />
                </div>
            </div>
          </div>
          <div class="mb-3">
            <label for="product-introduce" class="form-label">Introduce</label>
            <textarea class="form-control" id="post-content" rows="10" name="introduce"><?php echo $product['introduce']; ?></textarea>
          </div>
          <div class="mb-3">
            <label for="post-content" class="form-label">Status</label>
            <select class="form-control" id="post-status" name="status">
              <option value="Pending Review" <?php echo ( $product['status'] == 'Pending Review' ? 'selected' : '' ); ?>>Pending for Review</option>
              <option value="Publish" <?php echo ( $product['status'] == 'Publish' ? 'selected' : '' ); ?>>Publish</option>
            </select>
          </div>
          <div class="text-end">
            <button type="submit" class="btn btn-secondary">Add</button>
          </div>
          <input
            type="hidden"
            name="csrf_token"
            value="<?php echo CSRF::getToken( 'edit_product_form' ); ?>"
          />
        </form>
      </div>
      <div class="text-center">
        <a href="/manage-products" class="btn btn-link btn-sm text-decoration-none text-white"
          ><i class="bi bi-arrow-left"></i> Back to Posts</a
        >
      </div>
    </div>

    <?php require dirname(__DIR__) . '/parts/footer.php'; ?>
