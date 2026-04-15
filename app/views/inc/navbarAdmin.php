<header>
    <nav id="mainNav" class="navbar navbar-expand-md navbar-shrink  fixed-top py-3">
    <div class="container">
        <!-- <a class="navbar-brand d-flex align-items-center" href="<?php //echo URLROOT; ?>/records/">
            <span>Go Knows Lifeline</span></a> -->
            <a class="navbar-brand">
        <figure class="figure mb-0">
            <img class="img-fluid" style="width:auto;height:60px;" src="<?php echo URLROOT; ?>/public/img/logo.png" alt="">
            <figcaption>
                </figcaption>
        </figure>
            <!-- <h2 class="fw-hold text-bold mb-0">Tr</h2>
            <p style="font-size:9px;"><small>Powered by American Broadband + Telecommunication</small></p> -->
        </a>
            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navcol-1"><span class="visually-hidden">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
        <div id="navcol-1" class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <!-- 
                <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="features.html">Features</a></li>
                <li class="nav-item"><a class="nav-link" href="integrations.html">Integrations</a></li>
                <li class="nav-item"><a class="nav-link" href="pricing.html">Pricing</a></li>-->
                <li class="nav-item"><a class="nav-link" href="<?php echo URLROOT; ?>/records/">Home</a></li>
                <?php if($_SESSION['rol']==1){ ?> <li class="nav-item"><a class="nav-link" href="<?php echo URLROOT; ?>/users/admin">Users</a></li> <?php }; ?>
           </ul><a class="btn btn-primary btn-shadow" href="<?php echo URLROOT; ?>/users/logout">Logout</a> <!-- <a class="btn btn-primary shadow" role="button" href="apply.html">apply</a> -->
        </div>
    </div>
</nav>
</header>