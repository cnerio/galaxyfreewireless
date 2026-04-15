<header>
    <nav id="mainNav" class="navbar navbar-expand-md navbar-shrink  fixed-top py-2">
    <div class="container">
        <!-- <a class="navbar-brand d-flex align-items-center" href="/"> -->
        <a class="navbar-brand">
        <figure class="figure mb-0">
            <img class="img-fluid" style="width:auto;height:60px;" src="<?php echo URLROOT; ?>/public/img/logo.png" alt="">
            <figcaption>
                <?php if($powered=="AMBT"){ echo "Powered by American Broadband + Telecommunication"; }else if($powered=="NAL"){echo "Powered by National Relief Telecom";} ?></figcaption>
        </figure>
            <!-- <h2 class="fw-hold text-bold mb-0">Tr</h2>
            <p style="font-size:9px;"><small>Powered by American Broadband + Telecommunication</small></p> -->
        </a>
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navcol-1">
            <span class="visually-hidden">Toggle navigation</span><span class="navbar-toggler-icon"></span>
        </button>
        <div id="navcol-1" class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"></li> 
                <li class="nav-item" style="text-align: right;">
                    <a class="nav-link active pb-0" target="_blank" href="mailto:info@gotruewireless.com">info@GoTrueWireless.com <i class="fa fa-envelope"></i></a>
                    <a class="nav-link active pt-0" target="_blank" href="tel:+18337338524" style="font-family: sans-serif;">(833) 733-8524 <i class="fa fa-phone-square"></i></a>
                </li> 
                <!-- <li class="nav-item"><a class="nav-link" href="index.html">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="features.html">Features</a></li>
                <li class="nav-item"><a class="nav-link" href="integrations.html">Integrations</a></li>
                <li class="nav-item"><a class="nav-link" href="pricing.html">Pricing</a></li>
                <li class="nav-item"><a class="nav-link active" href="contacts.html">Contacts</a></li> 
                <a class="btn btn-primary shadow" role="button" href="<?php //echo URLROOT; ?>/enrolls?<?php //echo $queryString; ?>">Apply Now!</a>-->
            </ul>
            <?php if($apply){ ?><button class="btn btn-primary fs-5 py-2 px-4" data-bs-toggle="modal" data-bs-target="#exampleModal">Apply Now</button> <?php } ?>
        </div>
    </div>
</nav>
</header>
<style>
       figure {
        font-size: 9px;
        margin: 0;
    } 
</style>

