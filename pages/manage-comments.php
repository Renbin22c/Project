<?php

  // Step 1: generate CSRF token
  CSRF::generateToken( 'delete_comment_form' );

  // Step 2: make sure it's POST request
  if ( $_SERVER["REQUEST_METHOD"] === 'POST' ) {

    // step 3: do error check
    $error = FormValidation::validate(
      $_POST,
      [
        'comment_id' => 'required',
        'csrf_token' => 'delete_comment_form_csrf_token'
      ]
    );

    // make sure there is no error
    if ( !$error ) {
      // step 4: delete user
      Comment::delete( $_POST['comment_id'] );

      // step 5: remove CSRF token
      CSRF::removeToken( 'delete_comment_form' );

      // step 6: redirect back to the same page
      header("Location: /manage-comments");
      exit;

    } // end - $error

  } // end - $_SERVER["REQUEST_METHOD"]

require dirname(__DIR__) . '/parts/header.php';

?>

    <div class="container mx-auto my-5" style="max-width: 1050px">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1 text-white">Manage Comment</h1>
      </div>
      <div class="card mb-2 p-4 bg-dark">
        <table
          class="table table-hover table-bordered table-striped table-dark border-white"
        >
          <thead>
            <tr>
              <th scope="col">ID</th>
              <th scope="col">Comments</th>
              <th scope="col">User_Name & ID</th>
              <th scope="col">Product_Name & ID</th>
              <th scope="col">Recommended</th>
              <th scope="col" class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach( Comment::getAllComments($_SESSION['user']['id']) as $comment ) : ?>
            <tr>
              <th scope="row"><?php echo $comment['id']; ?></th>
              <td><?php echo substr( $comment['comment'], 0, 30 ); ?></td>
              <td><?php echo User::getUserNameById($comment['user_id'])['name'] . ' ('. $comment['user_id'] . ')'; ?></td>
              <td><?php echo Product::getProductNameById($comment['product_id'])['name']. ' ('. $comment['product_id'] . ')'; ?></td>
              <td>
              <?php 
                switch($comment['recommend']){
                  case '1':
                    echo 'Recommend';
                    break;
                  case '-1':
                    echo 'Not Recommend';
                    break;
                }
                ?>
              </td>
              <td class="text-end">
                <div class="buttons">
                  <a href="/product?id=<?php echo $comment['product_id']; ?>" 
                    target="_blank" class="btn btn-primary btn-sm me-2">
                    <i class="bi bi-eye"></i>
                  </a>
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
                          Are you sure you want to delete this comment (<?php echo $comment['id']; ?>)
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
                              value="<?php echo CSRF::getToken( 'delete_comment_form' ); ?>"
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
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
      <div class="text-center">
        <a
          href="/dashboard"
          class="btn btn-link btn-sm text-white text-decoration-none"
          ><i class="bi bi-arrow-left"></i> Back to Dashboard</a
        >
      </div>
    </div>

<?php require dirname(__DIR__) . '/parts/footer.php'; ?>
