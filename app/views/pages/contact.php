<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/navbar.php'; ?>
<section>
  <div class="container">
    <div class="row">
      <div class="col">
          <h1><?php echo $data['title']; ?></h1>
          <p><?php echo $data['description']; ?></p>
          <p>Version: <strong><?php echo APPVERSION; ?></strong></p>
          <hr>
          <p class="lead"><b><?php echo $data['info']; ?></b></p>
            <p class="lead"><?php echo $data['name']; ?></p>
            <p class="lead"><?php echo $data['location']; ?></p>
            <p class="lead"><?php echo $data['contact']; ?></p>
            <p class="lead"><?php echo $data['mail']; ?></p>
      </div>
    </div>
  </div>
</section>
<?php require APPROOT . '/views/inc/footer.php'; ?>