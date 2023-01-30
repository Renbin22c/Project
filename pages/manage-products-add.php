<?php

  // make sure only admin can access
  if ( !Authentication::whoCanAccess('admin') ) {
    header('Location: /dashboard');
    exit;
  }

  // step 1: set CSRF token
  CSRF::generateToken( 'add_product_form' );

  // step 2: make sure post request
  if ( $_SERVER["REQUEST_METHOD"] === 'POST' ) {

    // step 3: do error check
     $rules = [
      'name' => 'required',
      'price' => 'required',
      'type' => 'required',
      'img_url' => 'required',
      'release_time' => 'required',
      'developer' => 'required',
      'publisher' => 'required',
      'introduce' => 'required',
      'csrf_token' => 'add_product_form_csrf_token'
    ];

    $error = FormValidation::validate(
      $_POST,
      $rules
    );

    // make sure there is no error
    if ( !$error ) {

      // step 4 = add new product
      Product::add(
        $_POST['name'],
        $_POST['price'],
        $_POST['type'],
        $_POST['img_url'],
        $_POST['release_time'],
        $_POST['developer'],
        $_POST['publisher'],
        $_POST['introduce']

      );

      // step 5: remove the CSRF token
      CSRF::removeToken( 'add_product_form' );

      // step 6: redirect to manage products page
      header("Location: /manage-products");
      exit;

    } // end - $error


  } // end - $_SERVER["REQUEST_METHOD"]

require dirname(__DIR__) . '/parts/header.php'; 

?>
    <div class="container mx-auto my-5" style="max-width: 700px;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1 text-white">Add New Product</h1>
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
                        placeholder="Insert game name..."
                    />
                </div>
                <div class="col">
                    <label for="product-price" class="form-label">Price</label>
                    <input 
                        type="number" 
                        class="form-control" 
                        id="product-price"  
                        name="price"
                        placeholder="Insert game price..."
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
                        placeholder="Insert game type..."
                    />
                </div>
                <div class="col">
                    <label for="product-release_time" class="form-label">Release Time</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="product-release_time"  
                        name="release_time"
                        placeholder="Insert game release time..."
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
                    placeholder="Insert image link..."
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
                        placeholder="Insert game developer..."
                    />
                </div>
                <div class="col">
                    <label for="product-publisher" class="form-label">Publisher</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        id="product-publisher"  
                        name="publisher"
                        placeholder="Insert game publisher..."
                    />
                </div>
            </div>
          </div>
          <div class="mb-3">
            <label for="product-introduce" class="form-label">Introduce</label>
            <textarea class="form-control" id="post-content" rows="10" name="introduce" placeholder="Insert game introduction..."></textarea>
          </div>
          <div class="text-end">
            <button type="submit" class="btn btn-secondary">Add</button>
          </div>
          <input
            type="hidden"
            name="csrf_token"
            value="<?php echo CSRF::getToken( 'add_product_form' ); ?>"
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