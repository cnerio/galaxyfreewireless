<?php 
$queryString = $_SERVER['QUERY_STRING']; // e.g., "utm_source=google&utm_medium=cpc"

//echo $queryString;

require APPROOT . '/views/inc/header.php'; ?>
<?php 
$apply=true;
require APPROOT . '/views/inc/navbar.php'; 
?>

    <header class="pt-5 mb-5">
        <div class="container pt-4 pt-xl-5">
            <div class="row pt-5">
                <div class="col-md-6 text-center text-md-start mx-auto">
                    <div class="text-center">
                        <h1 class="display-4 fw-bold">Get your <span class="underline">FREE</span> Government Wireless Service&nbsp;now!.</h1>
                        <p class="fs-5 text-muted mb-2">High-Speed Data, Unlimited Talk & Text.</p>
                        <div class="my-2"><a class="btn btn-primary fs-5 py-2 px-4" role="button" href="<?php echo URLROOT; ?>/enrolls?<?php echo $queryString; ?>">Apply Now!</a></div>
                    </div>
                </div>
                <div class="col-md-6 mx-auto">
                    <div class="text-center position-relative"><img class="img-fluid" src="<?php echo URLROOT; ?>/public/img/illustrations/meeting.svg" style="width: 800px;"></div>
                </div>
            </div>
        </div>
    </header>
    <section>
        <div class="container py-4 py-xl-5">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="display-6 fw-bold pb-md-4">How to <span class="underline">Qualify</span></h3>
                    <p>You can qualify if you participate in government assistance programs such as:</p>
                </div>
                <div class="col-md-6">
                    <ul>
                    <li>Supplemental Nutrition Assistance Program (Food Stamps or&nbsp;SNAP)</li>
                    <li>Medicaid</li>
                    <li>Supplemental Security Income&nbsp;(SSI)</li>
                    <li>Federal Public Housing Assistance (Section&nbsp;8)</li>
                    <li>Veterans Pension or Survivors Benefit&nbsp;Programs</li>
                    <li>Bureau of Indian Affairs General&nbsp;Assistance</li>
                    <li>Tribally-Administered Temporary Assistance for Needy Families&nbsp;(TTANF)</li>
                    <li>Food Distribution Program on Indian Reservations&nbsp;(FDPIR)</li>
                    <li>Head Start (if income eligibility criteria are&nbsp;met)</li>
                </ul>
                </div>
            </div>
        </div>
    </section>
    <section class="py-4 py-xl-5">
        <div class="container">
            <div class="text-white bg-primary border rounded border-0 border-primary d-flex flex-column justify-content-between flex-lg-row p-4 p-md-5">
                <div class="pb-2 pb-lg-1">
                    <h2 class="fw-bold text-secondary mb-2"> Do you receive government benefits?</h2>
                    <p class="mb-0">Just fill out this enrollment form.</p>
                </div>
                <div class="my-2"><a class="btn btn-light fs-5 py-2 px-4" role="button" href="<?php echo URLROOT; ?>/enrolls?<?php echo $queryString; ?>">Apply Now !</a></div>
            </div>
        </div>
    </section>

  
<?php require APPROOT . '/views/inc/footer.php'; ?>
