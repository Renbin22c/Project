<?php 

  // Step 1: generate CSRF token
  CSRF::generateToken( 'delete_product_form' );

  // Step 2: make sure it's POST request
  if ( $_SERVER["REQUEST_METHOD"] === 'POST' ) {

    // step 3: do error check
    $error = FormValidation::validate(
      $_POST,
      [
        'product_id' => 'required',
        'csrf_token' => 'delete_product_form_csrf_token'
      ]
    );

    // make sure there is no error
    if ( !$error ) {
      // step 4: delete user
      Product::delete( $_POST['product_id'] );

      // step 5: remove CSRF token
      CSRF::removeToken( 'delete_product_form' );

      // step 6: redirect back to the same page
      header("Location: /manage-products");
      exit;

    } // end - $error

  } // end - $_SERVER["REQUEST_METHOD"]

require dirname(__DIR__) . '/parts/header.php'; 

?>
    <div class="container mx-auto my-5" style="max-width: 900px;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1 text-white">Manage Products</h1>
        <div class="text-end">
          <?php if(Authentication::whoCanAccess('admin')): ?>
          <a href="/manage-products-add" class="btn btn-secondary btn-sm"
            >Add New Product</a
          >
          <?php endif; ?>
        </div>
      </div>
      <div class="card mb-2 p-4">
        <?php require dirname( __DIR__ ) . '/parts/error_box.php'; ?>
        <table class="table table-hover table-bordered table-striped table-dark border-white">
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col" style="width: 30%;">Title</th>
              <th scope="col">Price</th>
              <th scope="col">Release Time</th>
              <th scope="col">Status</th>
              <th scope="col" class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach( Product::getAllProducts() as $product ) : ?>
            <tr>
              <th scope="row"><?php echo $product['id']; ?></th>
              <td><?php echo $product['name']; ?></td>
              <td><?php echo '$' . $product['price']; ?></td>
              <td><?php echo $product['release_time']; ?></td>
              <td>
                <?php
                  switch( $product['status'] ) {
                    case 'Pending Review':
                      echo '<span class="badge bg-warning">' . $product['status'] .'</span>';
                      break;
                    case 'Publish':
                      echo '<span class="badge bg-success">' . $product['status'] .'</span>';
                      break;
                  }
                ?>
              </td>
              <td class="text-end">
                <div class="buttons">
                  <a href="/product?id=<?php echo $product['id']; ?>" 
                    target="_blank" class="btn btn-primary btn-sm me-2 <?php echo ( $product['status'] === 'Pending Review' ? 'disabled' : '' ); ?>">
                    <i class="bi bi-eye"></i>
                  </a>
                  <a
                    href="/manage-products-edit?id=<?php echo $product['id']; ?>"
                    class="btn btn-warning btn-sm me-2"
                    ><i class="bi bi-pencil"></i
                  ></a>
                  <button 
                    type="button" 
                    class="btn btn-danger btn-sm" 
                    data-bs-toggle="modal" 
                    data-bs-target="#post-<?php echo $product['id']; ?>">
                    <i class="bi bi-trash"></i>
                  </button>

                  <!-- Modal -->
                  <div class="modal fade" id="post-<?php echo $product['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content bg-dark">
                        <div class="modal-header">
                          <h1 class="modal-title fs-5" id="exampleModalLabel">Delete User</h1>
                          <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-start">
                          Are you sure you want to delete this product (<?php echo $product['name']; ?>)
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          <form
                            method="POST"
                            action="<?php echo $_SERVER["REQUEST_URI"]; ?>"
                          >
                            <input 
                              type="hidden" 
                              name="product_id" 
                              value="<?php echo $product['id']; ?>" 
                            />
                            <input 
                              type="hidden" 
                              name="csrf_token" 
                              value="<?php echo CSRF::getToken( 'delete_product_form' ); ?>"
                            />
                            <button type="submit" class="btn btn-danger">Delete</button>
                          </form>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
            
          </tbody>
        </table>
      </div>
      <div class="text-center ">
        <a href="/dashboard" class="btn btn-link btn-sm text-decoration-none text-white"
          ><i class="bi bi-arrow-left"></i> Back to Dashboard</a
        >
      </div>
    </div>

<?php require dirname(__DIR__) . '/parts/footer.php'; ?>
