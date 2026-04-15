<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/navbar.php'; ?>
<div class="container">
    <h1><?php echo $data['title']; ?></h1>
  <p><?php echo $data['description']; ?></p>
  <p>Version: <strong><?php echo APPVERSION; ?></strong></p>
</div>
<?php require APPROOT . '/views/inc/footer.php'; ?>