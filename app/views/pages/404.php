<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/navbar.php'; ?>

<div class="container d-flex align-items-center justify-content-center" style="min-height: 70vh;margin:auto;margin-top:5rem;">
    <div class="text-center p-5 shadow-lg rounded" style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); max-width: 550px; width: 100%;margin:auto">
        <div class="display-1 text-danger font-weight-bold mb-4" style="font-size: 6rem; letter-spacing: -2px;">404</div>
        <h1 class="h3 font-weight-bold text-dark mb-3"><?php echo $data['title'] ?? 'Page Not Found'; ?></h1>
        <p class="text-muted mb-4"><?php echo $data['description'] ?? 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.'; ?></p>
        <a href="<?php echo URLROOT; ?>" class="btn btn-primary btn-lg px-4 shadow-sm" style="border-radius: 30px; font-weight: 600; letter-spacing: 0.5px; transition: transform 0.2s ease;">
            <i class="fa fa-home mr-2"></i> Go Back Home
        </a>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>
