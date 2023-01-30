<?php

  // make sure only admin can access
  if ( !Authentication::whoCanAccess('admin') ) {
    header('Location: /dashboard');
    exit;
  }

  // Step 1: generate CSRF token
  CSRF::generateToken( 'delete_user_form' );

  // Step 2: make sure it's POST request
  if ( $_SERVER["REQUEST_METHOD"] === 'POST' ) {

    // step 3: do error check
    $error = FormValidation::validate(
      $_POST,
      [
        'user_id' => 'required',
        'csrf_token' => 'delete_user_form_csrf_token'
      ]
    );

    // make sure there is no error
    if ( !$error ) {
      // step 4: delete user
      User::delete( $_POST['user_id'] );

      // step 5: remove CSRF token
      CSRF::removeToken( 'delete_user_form' );

      // step 6: redirect back to the same page
      header("Location: /manage-users");
      exit;

    } // end - $error

  } // end - $_SERVER["REQUEST_METHOD"]

require dirname(__DIR__) . '/parts/header.php';

?>

    <div class="container mx-auto my-5" style="max-width: 700px">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1 text-white">Manage Users</h1>
        <div class="text-end">
          <a href="/manage-users-add" class="btn btn-secondary btn-sm"
            >Add New User</a
          >
        </div>
      </div>
      <div class="card mb-2 p-4 bg-dark">
        <?php require dirname( __DIR__ ) . '/parts/error_box.php'; ?>
        <table
          class="table table-hover table-bordered table-striped table-dark border-white"
        >
          <thead>
            <tr>
              <th scope="col">No.</th>
              <th scope="col">ID</th>
              <th scope="col">Name</th>
              <th scope="col">Email</th>
              <th scope="col">Role</th>
              <th scope="col" class="text-end">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach( User::getAllUsers() as $index => $user ) : ?>
            <tr>
              <td><?php echo $index+1; ?></td>
              <th scope="row"><?php echo $user['id']; ?></th>
              <td><?php echo $user['name']; ?></td>
              <td><?php echo $user['email']; ?></td>
              <td>
                <?php
                  switch( $user['role'] ) {
                    case 'admin':
                      echo '<span class="badge bg-dark">' . $user['role'] .'</span>';
                      break;
                    case 'editor':
                      echo '<span class="badge bg-secondary">' . $user['role'] .'</span>';
                      break;
                    case 'user':
                      echo '<span class="badge bg-light text-dark">' . $user['role'] .'</span>';
                      break;
                  }
                ?>
              </td>
              <td class="text-end">
                <div class="buttons">
                  <a
                    href="/manage-users-edit?id=<?php echo $user['id']; ?>"
                    class="btn btn-warning btn-sm me-2"
                    ><i class="bi bi-pencil"></i
                  ></a>
                  <?php if ( $_SESSION['user']['id'] !== $user['id'] ) : ?>
                    <!-- Delete button Start -->
                    <!-- Button trigger modal -->
                    <button 
                      type="button" 
                      class="btn btn-danger btn-sm" 
                      data-bs-toggle="modal" 
                      data-bs-target="#user-<?php echo $user['id']; ?>">
                      <i class="bi bi-trash"></i>
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="user-<?php echo $user['id']; ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content bg-dark">
                          <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Delete User</h1>
                            <button type="button" class="btn-close bg-light" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body text-start">
                            Are you sure you want to delete this user (<?php echo $user['name']; ?>)
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <form
                              method="POST"
                              action="<?php echo $_SERVER["REQUEST_URI"]; ?>"
                              >
                              <input 
                                type="hidden" 
                                name="user_id" 
                                value="<?php echo $user['id']; ?>" 
                                />
                              <input 
                                type="hidden" 
                                name="csrf_token" 
                                value="<?php echo CSRF::getToken( 'delete_user_form' ); ?>"
                                />
                              <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php endif; ?>
                </div>
              </td>
            </tr>
            <?php endforeach; ?>
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
