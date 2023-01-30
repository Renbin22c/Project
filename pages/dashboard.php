<?php

if (!Authentication::whoCanAccess('user')){
  header('Location: /login');
  exit;
}

require dirname(__DIR__) . '/parts/header.php';

?>
    <div class="container mx-auto my-5" style="max-width: 800px">
      <h1 class="h1 mb-4 text-center text-white">Dashboard</h1>
      <div class="row d-flex flex-column">
        <?php if (Authentication::whoCanAccess('editor')): ?>
        <div class="d-flex">
          <div class="col-3"></div>
          <div class="col-6">
            <div class="card mb-2 bg-dark">
              <div class="card-body">
                <h5 class="card-title text-center text-white">
                  <div class="mb-1">
                    <i class="bi bi-pencil-square" style="font-size: 3rem"></i>
                  </div>
                  Manage Products
                </h5>
                <div class="text-center mt-3">
                  <a href="/manage-products" class="btn btn-secondary btn-sm"
                    >Access</a
                  >
                </div>
              </div>
            </div>
          </div>
          <div class="col-3"></div>
        </div>
        <?php endif; ?>
        <?php if (Authentication::whoCanAccess('admin')): ?>
          <div class="d-flex">
            <div class="col-3"></div>
            <div class="col-6">
              <div class="card mb-2 bg-dark">
                <div class="card-body">
                  <h5 class="card-title text-center text-white">
                    <div class="mb-1">
                      <i class="bi bi-people" style="font-size: 3rem"></i>
                    </div>
                    Manage Users
                  </h5>
                  <div class="text-center mt-3">
                    <a href="/manage-users" class="btn btn-secondary btn-sm"
                      >Access</a
                    >
                  </div>
                </div>
              </div>
          </div>
        <div class="col-3"></div>
        </div>
        <?php endif; ?>
        <div class="d-flex">
          <div class="col-3"></div>
          <div class="col-6">
            <div class="card mb-2 bg-dark">
              <div class="card-body">
                <h5 class="card-title text-center text-white">
                  <div class="mb-1">
                    <i class="bi bi-chat-dots-fill" style="font-size: 3rem"></i>
                  </div>
                  Manage Comments
                </h5>
                <div class="text-center mt-3">
                  <a href="/manage-comments" class="btn btn-secondary btn-sm"
                    >Access</a
                  >
                </div>
              </div>
            </div>
          <div class="col-3"></div>
        </div>
      </div>
      <div class="mt-4 text-center">
        <a
          href="/home"
          class="btn btn-link btn-sm text-white text-decoration-none"
          ><i class="bi bi-arrow-left"></i> Back</a
        >
      </div>
    </div>

<?php require dirname(__DIR__) . '/parts/footer.php'; ?>