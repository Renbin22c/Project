<?php

  // make sure only admin can access
  if ( !Authentication::whoCanAccess('admin') ) {
    header('Location: /dashboard');
    exit;
  }

  // load user data
  $user = User::getUserByID( $_GET['id'] );

  // step 1: set CSRF token
  CSRF::generateToken( 'edit_user_form' );


  // step 2: make sure post request
  if ( $_SERVER["REQUEST_METHOD"] === 'POST' ) {

    // step 3: do error check

      // Do a check to find out should we do password update or not
      $is_password_changed = ( 
        ( isset( $_POST['password'] ) && !empty( $_POST['password'] ) ) || 
        ( isset( $_POST['confirm_password'] ) && !empty( $_POST['confirm_password'] ) ) 
        ? true : false
      );

      // if both password & confirm_password fields are empty, skip error checking for both fields.
      // skip error checking for both field
      $rules = [
        'name' => 'required',
        'email' => 'email_check',
        'role' => 'required',
        'csrf_token' => 'edit_user_form_csrf_token'
      ];

      // if password is updated
      if ( $is_password_changed ) {
        $rules['password'] = 'password_check'; //  make sure the length >= 8
        $rules['confirm_password'] = 'is_password_match'; // make sure both fields are match
      }

      // if either password & confirm_password fields are empty, skip error checking for both fields.
      $error = FormValidation::validate(
        $_POST,
        $rules
      );

      // if email changed, make sure it cannot belongs to another user
      // we check for email changes
      if ( $user['email'] !== $_POST['email'] ) {
          // do database check to make sure new email wasn't already in use
          $error .= FormValidation::checkEmailUniqueness($_POST['email']);
      }

      // make sure there is no error
      if ( !$error ) {
        // step 4: update user
        User::update(
          $user['id'], // id
          $_POST['name'], // name
          $_POST['email'], // email
          $_POST['role'],// role
          ( $is_password_changed ? $_POST['password'] : null )// password update if availbale
        );

        // step 5: remove the CSRF token
        CSRF::removeToken( 'edit_user_form' );

        // step 6: redirect back to manage users page
        header("Location: /manage-users");
        exit;
      }
  }

  require dirname(__DIR__) . '/parts/header.php';
?>
<div class="container mx-auto my-5" style="max-width: 700px;">
      <div class="d-flex justify-content-between align-items-center mb-2">
        <h1 class="h1 text-white">Edit User</h1>
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
                <label for="name" class="form-label d-flex">
                  <i class="bi bi-person-circle"> Name</i>
                </label>
                <input 
                  type="text" 
                  class="form-control" 
                  id="name"
                  name="name"
                  value="<?php echo $user['name']; ?>"
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
                  value="<?php echo $user['email']; ?>"
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
                <label for="confirm_password" class="form-label">
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
            <label for="role" class="form-label">Role</label>
            <select class="form-control" id="role" name="role">
              <option value="">Select an option</option>
              <option value="user" <?php echo ( $user['role'] == 'user' ? 'selected' : '' ); ?>>User</option>
              <option value="editor" <?php echo ( $user['role'] == 'editor' ? 'selected' : '' ); ?>>Editor</option>
              <option value="admin" <?php echo ( $user['role'] == 'admin' ? 'selected' : '' ); ?>>Admin</option>
            </select>
          </div>
          <div class="d-grid">
            <button type="submit" class="btn btn-secondary">Update</button>
          </div>
          <input
            type="hidden"
            name="csrf_token"
            value="<?php echo CSRF::getToken('edit_user_form'); ?>"
            />
        </form>
      </div>
      <div class="text-center">
        <a href="/manage-users" class="btn btn-link btn-sm text-white text-decoration-none"
          ><i class="bi bi-arrow-left"></i> Back to Users</a
        >
      </div>
    </div>
<?php require dirname(__DIR__) . '/parts/footer.php'; ?>