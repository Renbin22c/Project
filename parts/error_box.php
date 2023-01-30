<?php 
if ( isset( $error ) && !empty( $error ) ) : ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $error; ?>
    </div>
<?php endif;