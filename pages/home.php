<?php

require dirname(__DIR__) . '/parts/header.php';

?>
    <div class="container mt-5 mb-2 mx-auto" style="max-width: 900px">
      <div class="min-vh-100">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h1 class="h1 text-white">Game Store</h1>
          <div class="d-flex align-items-center justify-content-end gap-3">
            <a href="/cart" class="btn btn-light" style="font-size: 20px">
              <i class="bi bi-cart4"></i>
            </a>
          </div>
        </div>

        <div class="row row-cols-1 row-cols-md-3 g-4">
          <?php foreach ( Product::getPublishProducts() as $product ): ?>
          <div class="col">
            <div class="card h-100">
              <img
                src= "<?php echo $product['img_url']; ?>"
                class="card-img-top"
              />
              <div class="card-body caption text-white">
                <div class="card-title">
                  <b><?php echo $product['name']; ?></b>
                  <div class="more">
                    <a href="/product?id=<?php echo $product['id']; ?>" class="text-white text-decoration-none">Learn More ></a>
                  </div>
                </div>
              </div>
              <div class="add text-white">
                <form 
                  method="POST"
                  action="/cart"
                >
                  <input 
                    type="hidden" 
                    name="product_id" 
                    value="<?php echo $product['id']; ?>"
                  >
                  <button class="btn btn-transparent">
                    <i class="bi bi-plus-circle-fill fs-5 text-white"></i>
                  </button>
                </form>
                </form>
              </div>
            </div>
          </div>
          <?php endforeach;?>
        </div>
      </div>

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
