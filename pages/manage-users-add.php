<?php

  // make sure only admin can access
  if ( !Authentication::whoCanAccess('admin') ) {
    header('Location: /dashboard');
    exit;
  }

  // step 1: set CSRF token
  CSRF::generateToken( 'add_user_form' );

  // step 2: make sure post request
  if ( $_SERVER["REQUEST_METHOD"] === 'POST' ) {

    // step 3: do error check
     $rules = [
      'name' => 'required',
      'email' => 'email_check',
      'role' => 'required',
      'csrf_token' => 'add_user_form_csrf_token',
      'password' => 'password_check',
      'confirm_password' => 'is_password_match'
    ];

    $error = FormValidation::validate(
      $_POST,
      $rules
    );

    // check for email uniqueness
    if ( FormValidation::checkEmailUniqueness( $_POST['email'] ) ) {
      $error .= FormValidation::checkEmailUniqueness( $_POST['email'] );
    }

    // make sure there is no error
    if ( !$error ) {

      // step 4 = add new user
      User::add(
        $_POST['name'],
        $_POST['email'],
        $_POST['role'],
        $_POST['password']
      );

      // step 5: remove the CSRF token
      CSRF::removeToken( 'add_user_form' );

      // step 6: redirect to manage users page
      header("Location: /manage-users");
      exit;

    } // end - $error


  } // end - $_SERVER["REQUEST_METHOD"]

require dirname(__DIR__) . '/parts/header.php';

?>
    <div class="container mx-auto my-5" style="max-width: 700px">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1 text-white">Add New User</h1>
      </div>
      <div class="card mb-2 p-4 bg-dark text-white">
        <?php require dirname( __DIR__ ) . '/parts/error_box.php'; ?>
        <form
          method="POST"
          action="<?php echo $_SERVER["REQUEST_URI"]; ?>"
          >
          <div class="mb-3">
            <div class="row">
              <div class="col">
                <label for="name" class="form-label">
                  <i class="bi bi-person-circle"> Name</i>
                </label>
                <input 
                  type="text" 
                  class="form-control" 
                  id="name" 
                  name="name"
                />
              </div>
              <div class="col">
                <label for="email" class="form-label">
                  <i class="bi bi-envelope-fill"> Email Address</i>
                </label>
                <input 
                  type="email" 
                  class="form-control" 
                  id="email" 
                  name="email"
                />
              </div>
            </div>
          </div>
          <div class="mb-3">
            <div class="row">
              <div class="col">
                <label for="password" class="form-label">
                  <i class="bi bi-shield-lock-fill"> Password</i>
                </label>
                <input 
                  type="password" 
                  class="form-control" 
                  id="password" 
                  name="password"
                />
              </div>
              <div class="col">
                <label for="confirm-password" class="form-label">
                  <i class="bi bi-fingerprint"> Confirm Password</i>
                </label>
                <input
                  type="password"
                  class="form-control"
                  id="confirm-password"
                  name="confirm_password"
                />
              </div>
            </div>
          </div>
          <div class="mb-3">
            <label for="role" class="form-label">
              <i class="bi bi-person-badge"> Role</i>
            </label>
            <select class="form-control" id="role" name="role">
              <option value="">Select an option</option>
              <option value="user">User</option>
              <option value="editor">Editor</option>
              <option value="admin">Admin</option>
            </select>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-secondary">Add</button>
          </div>
          <input
            type="hidden"
            name="csrf_token"
            value="<?php echo CSRF::getToken( 'add_user_form' ); ?>"
          />
        </form>
      </div>
      <div class="text-center">
        <a
          href="/manage-users"
          class="btn btn-link btn-sm text-white text-decoration-none"
          ><i class="bi bi-arrow-left"></i> Back to Users</a
        >
      </div>
    </div>

<?php require dirname(__DIR__) . '/parts/footer.php'; ?>
