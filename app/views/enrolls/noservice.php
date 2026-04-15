<?php require APPROOT . '/views/inc/header.php'; ?>
<?php 
$apply=false;
$powered = "NONE";
require APPROOT . '/views/inc/navbar.php'; 
?>



<div class="container mt-5">
    <div class="row pt-5">
        <div class="col-md-8 text-center text-md-start mx-auto mt-5">
            <div class="text-center">
                <h1 class="display-4 fw-bold mb-5"><span class="underline">Thank You!</span>.</h1>
                <p class="fs-5 text-muted mb-5">Unfortunately, we are not approved to offer lifeline in your service area; however, we do have great prepaid plan options available.</p>
                 <div class="mb-3"><a href="<?php echo URLROOT; ?>" class="btn btn-primary" type="submit">Back to Home </a></div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>