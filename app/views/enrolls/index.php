
<?php 
//print_r($data);
$queryString = $_SERVER['QUERY_STRING']; 
require APPROOT . '/views/inc/header.php'; 
?>
<?php 
$apply=false;
$powered=$data['powered'] ?? '';
require APPROOT . '/views/inc/navbar.php'; 

if(isset($data)){
$full_url = $data['url'];
}else{
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$full_url = $protocol . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
}


$utm_source = isset($_GET['utm_source']) ? $_GET['utm_source'] : null;
$utm_medium = isset($_GET['utm_medium']) ? $_GET['utm_medium'] : null;
$utm_campaign = isset($_GET['utm_campaign']) ? $_GET['utm_campaign'] : null;
$utm_content = isset($_GET['utm_content']) ? $_GET['utm_content'] : null;
$match_type = isset($_GET['match_type']) ? $_GET['match_type'] : null;
$utm_adgroup = isset($_GET['utm_adgroup']) ? $_GET['utm_adgroup'] : null;
$gclid = isset($_GET['gclid']) ? $_GET['gclid'] : null;
$fbclid = isset($_GET['fbclid']) ? $_GET['fbclid'] : null
?>
<section class="py-5 mt-5">
    <div class="container py-5">
        <div class="row">
            <div class="col mx-auto">
                <form id="enrollForm" action="#">
                        <h3>Personal Info</h3>

                        <section>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="firstname">First Name <span class="requiredmark">*</span></label>
                                        <input type="text" id="firstname" name="firstname" class="form-control" <?php if(isset($data)){echo ($data['first_name'])?"value='".$data['first_name']."' readonly":"";} ?>>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="lastname">Last Name <span class="requiredmark">*</span></label>
                                        <input type="text" id="lastname" name="lastname" class="form-control" <?php if(isset($data)){echo ($data['second_name'])?"value='".$data['second_name']."' readonly":"";} ?>>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">Birth Date</label>
                                        <input type="date" id="dob" name="dob" class="form-select">
                                    </div>
                                </div>
                            </div> -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="ssn">Social Security Number <span class="requiredmark">*</span></label>
                                        <!-- <input type="text" id="ssn" name="ssn" class="form-control" maxlength="11" pattern="[0-9]*" placeholder="0000"> -->
                                        <input type="text" id="ssn" name="ssn" class="form-control" placeholder="000-00-0000">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="dob">Birth Date <span class="requiredmark">*</span></label>
                                        <div class="input-group">
                                            <!-- <input type="text" id="dobM" name="dobM" class="form-control dob-group" maxlength="2" pattern="[0-9]*" placeholder="MM"> -->
                                             <select name="dobM" id="dobM" class="form-select dob-group">
                                                <option value="">Month</option>
                                                <option value="01">January</option>
                                                <option value="02">February</option>
                                                <option value="03">March</option>
                                                <option value="04">April</option>
                                                <option value="05">May</option>
                                                <option value="06">June</option>
                                                <option value="07">July</option>
                                                <option value="08">August</option>
                                                <option value="09">September</option>
                                                <option value="10">October</option>
                                                <option value="11">November</option>
                                                <option value="12">December</option>
                                             </select>
                                            <input type="text" id="dobD" name="dobD" class="form-control dob-group" maxlength="2" pattern="[0-9]*" placeholder="Day">
                                            <input type="text" id="dobY" name="dobY" class="form-control dob-group" maxlength="4" pattern="[0-9]*" placeholder="Year">
                                        </div>
                                        <div class="dob-errors">
                                            <label id="dobM-error" class="error" for="dobM"></label>
                                            <label id="dobD-error" class="error" for="dobD"></label>
                                            <label id="dobY-error" class="error" for="dobY"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email <span class="requiredmark">*</span></label>
                                        <input type="text" id="email" name="email" class="form-control" <?php if(isset($data)){echo ($data['email'])?"value='".$data['email']."' readonly":"";} ?>>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Phone <span class="requiredmark">*</span></label>
                                        <input type="text" id="phone" name="phone" class="form-control phoneUs">
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row pt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address1">Street Address <span class="requiredmark">*</span></label>
                                        <input type="text" id="address1" name="address1" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address2">Apartment or Unit Number</label>
                                        <input type="text" id="address2" name="addess2" class="form-control" >
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="city">City <span class="requiredmark">*</span></label>
                                        <input type="text" id="city" name="city" class="form-control" >
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="state">State <span class="requiredmark">*</span></label>
                                        <select name="state" id="state" class="form-select" onchange="stateChanged()" >
                                            <option value="">Select a state</option>
                                            <option value="AL" <?php if(isset($data)){echo($data['state']=="AL")?"selected":"";}?>>Alabama</option>
                                            <option value="AK" <?php if(isset($data)){echo($data['state']=="AK")?"selected":"";}?>>Alaska</option>
                                            <option value="AZ" <?php if(isset($data)){echo($data['state']=="AZ")?"selected":"";}?>>Arizona</option>
                                            <option value="AR" <?php if(isset($data)){echo($data['state']=="AR")?"selected":"";}?>>Arkansas</option>
                                            <option value="CA" <?php if(isset($data)){echo($data['state']=="CA")?"selected":"";}?>>California</option>
                                            <option value="CO" <?php if(isset($data)){echo($data['state']=="CO")?"selected":"";}?>>Colorado</option>
                                            <option value="CT" <?php if(isset($data)){echo($data['state']=="CT")?"selected":"";}?>>Connecticut</option>
                                            <option value="DE" <?php if(isset($data)){echo($data['state']=="DE")?"selected":"";}?>>Delaware</option>
                                            <option value="FL" <?php if(isset($data)){echo($data['state']=="FL")?"selected":"";}?>>Florida</option>
                                            <option value="GA" <?php if(isset($data)){echo($data['state']=="GA")?"selected":"";}?>>Georgia</option>
                                            <option value="HI" <?php if(isset($data)){echo($data['state']=="HI")?"selected":"";}?>>Hawaii</option>
                                            <option value="ID" <?php if(isset($data)){echo($data['state']=="ID")?"selected":"";}?>>Idaho</option>
                                            <option value="IL" <?php if(isset($data)){echo($data['state']=="IL")?"selected":"";}?>>Illinois</option>
                                            <option value="IN" <?php if(isset($data)){echo($data['state']=="IN")?"selected":"";}?>>Indiana</option>
                                            <option value="IA" <?php if(isset($data)){echo($data['state']=="IA")?"selected":"";}?>>Iowa</option>
                                            <option value="KS" <?php if(isset($data)){echo($data['state']=="KS")?"selected":"";}?>>Kansas</option>
                                            <option value="KY" <?php if(isset($data)){echo($data['state']=="KY")?"selected":"";}?>>Kentucky</option>
                                            <option value="LA" <?php if(isset($data)){echo($data['state']=="LA")?"selected":"";}?>>Louisiana</option>
                                            <option value="ME" <?php if(isset($data)){echo($data['state']=="ME")?"selected":"";}?>>Maine</option>
                                            <option value="MD" <?php if(isset($data)){echo($data['state']=="MD")?"selected":"";}?>>Maryland</option>
                                            <option value="MA" <?php if(isset($data)){echo($data['state']=="MA")?"selected":"";}?>>Massachusetts</option>
                                            <option value="MI" <?php if(isset($data)){echo($data['state']=="MI")?"selected":"";}?>>Michigan</option>
                                            <option value="MN" <?php if(isset($data)){echo($data['state']=="MN")?"selected":"";}?>>Minnesota</option>
                                            <option value="MS" <?php if(isset($data)){echo($data['state']=="MS")?"selected":"";}?>>Mississippi</option>
                                            <option value="MO" <?php if(isset($data)){echo($data['state']=="MO")?"selected":"";}?>>Missouri</option>
                                            <option value="MT" <?php if(isset($data)){echo($data['state']=="MT")?"selected":"";}?>>Montana</option>
                                            <option value="NE" <?php if(isset($data)){echo($data['state']=="NE")?"selected":"";}?>>Nebraska</option>
                                            <option value="NV" <?php if(isset($data)){echo($data['state']=="NV")?"selected":"";}?>>Nevada</option>
                                            <option value="NH" <?php if(isset($data)){echo($data['state']=="NH")?"selected":"";}?>>New Hampshire</option>
                                            <option value="NJ" <?php if(isset($data)){echo($data['state']=="NJ")?"selected":"";}?>>New Jersey</option>
                                            <option value="NM" <?php if(isset($data)){echo($data['state']=="NM")?"selected":"";}?>>New Mexico</option>
                                            <option value="NY" <?php if(isset($data)){echo($data['state']=="NY")?"selected":"";}?>>New York</option>
                                            <option value="NC" <?php if(isset($data)){echo($data['state']=="NC")?"selected":"";}?>>North Carolina</option>
                                            <option value="ND" <?php if(isset($data)){echo($data['state']=="ND")?"selected":"";}?>>North Dakota</option>
                                            <option value="OH" <?php if(isset($data)){echo($data['state']=="OH")?"selected":"";}?>>Ohio</option>
                                            <option value="OK" <?php if(isset($data)){echo($data['state']=="OK")?"selected":"";}?>>Oklahoma</option>
                                            <option value="OR" <?php if(isset($data)){echo($data['state']=="OR")?"selected":"";}?>>Oregon</option>
                                            <option value="PA" <?php if(isset($data)){echo($data['state']=="PA")?"selected":"";}?>>Pennsylvania</option>
                                            <option value="PR" <?php if(isset($data)){echo($data['state']=="PR")?"selected":"";}?>>Puerto Rico</option>
                                            <option value="RI" <?php if(isset($data)){echo($data['state']=="RI")?"selected":"";}?>>Rhode Island</option>
                                            <option value="SC" <?php if(isset($data)){echo($data['state']=="SC")?"selected":"";}?>>South Carolina</option>
                                            <option value="SD" <?php if(isset($data)){echo($data['state']=="SD")?"selected":"";}?>>South Dakota</option>
                                            <option value="TN" <?php if(isset($data)){echo($data['state']=="TN")?"selected":"";}?>>Tennessee</option>
                                            <option value="TX" <?php if(isset($data)){echo($data['state']=="TX")?"selected":"";}?>>Texas</option>
                                            <option value="UT" <?php if(isset($data)){echo($data['state']=="UT")?"selected":"";}?>>Utah</option>
                                            <option value="VT" <?php if(isset($data)){echo($data['state']=="VT")?"selected":"";}?>>Vermont</option>
                                            <option value="VA" <?php if(isset($data)){echo($data['state']=="VA")?"selected":"";}?>>Virginia</option>
                                            <option value="WA" <?php if(isset($data)){echo($data['state']=="WA")?"selected":"";}?>>Washington</option>
                                            <option value="WV" <?php if(isset($data)){echo($data['state']=="WV")?"selected":"";}?>>West Virginia</option>
                                            <option value="WI" <?php if(isset($data)){echo($data['state']=="WI")?"selected":"";}?>>Wisconsin</option>
                                            <option value="WY" <?php if(isset($data)){echo($data['state']=="WY")?"selected":"";}?>>Wyoming</option>
                                            <option value="DC" <?php if(isset($data)){echo($data['state']=="DC")?"selected":"";}?>>District of Columbia</option>
                                            
                                        </select>

                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="zipcode">Zipcode <span class="requiredmark">*</span></label>
                                        <input type="text" id="zipcode" name="zipcode" class="form-control zipcode" maxlength="5" pattern="^[0-9]{5}$" placeholder="00000" <?php if(isset($data)){echo ($data['zipcode'])?"value='".$data['zipcode']."' readonly":"";} ?>>
                                    </div>
                                </div>
                                <div class="col-md-12 pt-2">
                                    <h6>is this address permanent or temporary?</h6>
                                    <div class="form-check">
                                        <label class="form-chack-label" for="typeAddress1">
                                        <input type="radio" name="typeAddress" id="typeAddres1" class="form-check-input" value="Permanent">
                                        Permanent</label>
                                    </div>
                                    <div class="form-check">
                                        <label class="form-chack-label" for="typeAddress2">
                                        <input type="radio" name="typeAddress" id="typeAddres2" class="form-check-input" value="Temporary">
                                        Temporary</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 pt-2" id="showShip" style="cursor:pointer">
                                    <h6>Ship to a different address? <i class="fa fa-chevron-down"></i></h6>
                                </div>
                            </div>
                            <div id="shipArea" style="display:none;">
                                <div class="row pt-2">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="shipaddress1">Street Address</label>
                                            <input type="text" id="shipaddress1" name="shipaddress1" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="shipaddress2">Apartment or Unit Number</label>
                                            <input type="text" id="shipaddress2" name="shipaddess2" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="shipcity">City</label>
                                            <input type="text" id="shipcity" name="shipcity" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="shipstate">State</label>
                                            <select name="shipstate" id="shipstate" class="form-select">
                                                <option value="">Select a state</option>
                                                <option value="AL">Alabama</option>
                                                <option value="AK">Alaska</option>
                                                <option value="AZ">Arizona</option>
                                                <option value="AR">Arkansas</option>
                                                <option value="CA">California</option>
                                                <option value="CO">Colorado</option>
                                                <option value="CT">Connecticut</option>
                                                <option value="DE">Delaware</option>
                                                <option value="FL">Florida</option>
                                                <option value="GA">Georgia</option>
                                                <option value="HI">Hawaii</option>
                                                <option value="ID">Idaho</option>
                                                <option value="IL">Illinois</option>
                                                <option value="IN">Indiana</option>
                                                <option value="IA">Iowa</option>
                                                <option value="KS">Kansas</option>
                                                <option value="KY">Kentucky</option>
                                                <option value="LA">Louisiana</option>
                                                <option value="ME">Maine</option>
                                                <option value="MD">Maryland</option>
                                                <option value="MA">Massachusetts</option>
                                                <option value="MI">Michigan</option>
                                                <option value="MN">Minnesota</option>
                                                <option value="MS">Mississippi</option>
                                                <option value="MO">Missouri</option>
                                                <option value="MT">Montana</option>
                                                <option value="NE">Nebraska</option>
                                                <option value="NV">Nevada</option>
                                                <option value="NH">New Hampshire</option>
                                                <option value="NJ">New Jersey</option>
                                                <option value="NM">New Mexico</option>
                                                <option value="NY">New York</option>
                                                <option value="NC">North Carolina</option>
                                                <option value="ND">North Dakota</option>
                                                <option value="OH">Ohio</option>
                                                <option value="OK">Oklahoma</option>
                                                <option value="OR">Oregon</option>
                                                <option value="PA">Pennsylvania</option>
                                                <option value="RI">Rhode Island</option>
                                                <option value="SC">South Carolina</option>
                                                <option value="SD">South Dakota</option>
                                                <option value="TN">Tennessee</option>
                                                <option value="TX">Texas</option>
                                                <option value="UT">Utah</option>
                                                <option value="VT">Vermont</option>
                                                <option value="VA">Virginia</option>
                                                <option value="WA">Washington</option>
                                                <option value="WV">West Virginia</option>
                                                <option value="WI">Wisconsin</option>
                                                <option value="WY">Wyoming</option>
                                                <option value="DC">District of Columbia</option>
                                                <option value="PR">Puerto Rico</option>
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="shipzipcode">Zipcode</label>
                                            <input type="text" id="shipzipcode" name="shipzipcode" class="form-control zipcode" maxlength="5" pattern="^[0-9]{5}$" placeholder="00000">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                    <div class="col-md-12" style="text-align: right;">
                                        <div id="recaptcha" class="g-recaptcha" style="display: inline-block" data-sitekey="6LeVbyYsAAAAAOhmjaZxzq7vA6v25kD6FwIywZ6c"></div>
                                    </div>
                                    <div class="col-md-12" style="text-align: right;"> 
                                        <span class="msg-error error"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div id='checkmessage'>

                                        </div>
                                    </div>
                                </div>
                            <input type="hidden" id="url" name="url" value="<?php echo $full_url; ?>">
                            <input type="hidden" id="company" name="company" value="American Assist">
                            <input type="hidden" id="utm_source" name="utm_source" value="<?php echo $utm_source; ?>">
                            <input type="hidden" id="utm_medium" name="utm_medium" value="<?php echo $utm_medium; ?>">
                            <input type="hidden" id="utm_campaign" name="utm_campaign" value="<?php echo $utm_campaign; ?>">
                            <input type="hidden" id="utm_content" name="utm_content" value="<?php echo $utm_content; ?>">
                            <input type="hidden" id="match_type" name="match_type" value="<?php echo $match_type; ?>">
                            <input type="hidden" id="utm_adgroup" name="utm_adgroup" value="<?php echo $utm_adgroup; ?>">
                            <input type="hidden" id="gclid" name="gclid" value="<?php echo $gclid; ?>">
                            <input type="hidden" id="fbclid" name="fbclid" value="<?php echo $fbclid; ?>">
                            <input type="hidden" id="powered" name="powered" value="<?php echo $data['powered'] ?? ''; ?>">
                            <input type="hidden" id="enrollment_id" name="enrollment_id" value="<?php echo $data['enrollment_id'] ?? ''; ?>">
                            <input type="hidden" id="tribal" name="tribal" value="<?php echo $data['is_tribal'] ?? ''; ?>">
                        </section>

                        <h3>Eligibility</h3>

                        <section>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="eligibility_program">Select Government Program <span class="requiredmark">*</span></label>
                                        <select name="eligibility_program" id="eligibility_program" class="form-control form-control-lg">

                                            <!-- <option value="">Select..</option>

                                            <option value="100001">Supplemental Nutrition Assistance Program (Food Stamps or SNAP)</option>

                                            <option value="100004">Medicaid</option>

                                            <option value="100002">Household Income</option>

                                            <option value="100006">Supplemental Security Income (SSI)</option>

                                            <option value="100000">Federal Public Housing Assistance (Section 8)</option>

                                            <option value="100014">Veteran&#39;s Pension or Survivors Benefit Programs</option>

                                            <option value="100011">Bureau of Indian Affairs General Assistance</option>

                                            <option value="100008">Tribally-Administered Temporary Assistance for Needy Families (TTANF)</option>

                                            <option value="100010">Food Distribution Program on Indian Reservations (FDPIR)</option>

                                            <option value="100009">Head Start (if income eligibility criteria are met)</option> -->

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div id="allStatesArea">
                                        <div class="form-group">
                                            <h6>Are you a Current Recipient of Lifeline Benefits?</h6>
                                            <div class="form-check form-check-inline">
                                                <input class=" form-check-input" value="Yes" type="radio" name="current_benefits" id="current_benefits1">

                                                <label class="form-check-label" for="current_benefits1">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">

                                                <input class=" form-check-input" value="No" type="radio" name="current_benefits" id="current_benefits2">

                                                <label class="form-check-label" for="current_benefits2">No</label>
                                            </div>
                                        </div>

                                    </div>
                                    <div id="adultquestion" style="display:none">
                                            <div class="form-group">
                                            <h6>Do you live with another adult?</h6>
                                            <div class="form-check form-check-inline">
                                                <input class=" form-check-input" value="Yes" type="radio" name="anotheradult" id="anotheradult1" onchange="questionsArea(this,'otherbenefits','shareincome')">
                                                <label class="form-check-label" for="anotheradult1">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class=" form-check-input" value="No" type="radio" name="anotheradult" id="anotheradult2" onchange="questionsArea(this,'otherbenefits','shareincome')">
                                                <label class="form-check-label" for="anotheradult2">No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="otherbenefits" style="display:none">
                                            <div class="form-group">
                                            <h6>Does the adult who lives with you receive a California Lifeline discount?</h6>
                                            <div class="form-check form-check-inline">
                                                <input class=" form-check-input" value="Yes" type="radio" name="otherbenefits" id="otherbenefits1" onchange="questionsArea(this,'shareincome')">
                                                <label class="form-check-label" for="otherbenefits1">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class=" form-check-input" value="No" type="radio" name="otherbenefits" id="otherbenefits2" onchange="questionsArea(this,'shareincome')">
                                                <label class="form-check-label" for="otherbenefits2">No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="shareincome" style="display:none">
                                            <div class="form-group">
                                            <h6>Do you share income and living expenses with the adult who lives with you?</h6>
                                            <div class="form-check form-check-inline">
                                                <input class=" form-check-input" value="Yes" type="radio" name="shareincome" id="shareincome1" onchange="questionsArea(this,'NoQualifyForCalifornia')">
                                                <label class="form-check-label" for="shareincome1">Yes</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class=" form-check-input" value="No" type="radio" name="shareincome" id="shareincome2" onchange="questionsArea(this,'NoQualifyForCalifornia')">
                                                <label class="form-check-label" for="shareincome2">No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="NoQualifyForCalifornia" style="display:none">
                                        <div class="alert alert-warning" role="alert">
                                            You do <b>not</b> qualify for California Lifeline because someone in your household already receives a Lifeline benefit. Only one Lifeline benefit per household is allowed under California Lifeline rules.<b><br> As a result, I understand that I will be removed from the California Lifeline Program.</b>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nv_application_id">National Verifier Application ID</label>
                                        <input type="text" id="nv_application_id" name="nv_application_id" class="form-control">
                                        <span><b>Not Required</b></span>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <h6>What type of phone do you currently use?</h6>
                                        <div class="form-check form-check-inline">
                                            <input class=" form-check-input" value="iOS" type="radio" name="type_phone" id="apple_phone">

                                            <label class="form-check-label" for="apple_phone"> <i class="fa fa-apple" style="color:darkgrey"></i> iOS</label>
                                        </div>
                                        <div class="form-check form-check-inline">

                                            <input class=" form-check-input" value="Android" type="radio" name="type_phone" id="android_phone">

                                            <label class="form-check-label" for="android_phone"> <i class="fa fa-android" style="color:green"></i> Android</label>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <span class="btn btn-lg btn-primary" id="uploadBtn">Click to upload your government ID</span>
                                    <br>
                                    <span><b>Optional</b></span>
                                    <br>
                                    <label id="fileInputerror" style="display:none;" class="error" for="fileInput"></label>
                                    <input type="file" name="fileInput" id="fileInput" accept="image/*,application/pdf,.doc,.docx" capture="camera" style="display: none;" />
                                    <div id="preview"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <span class="btn btn-lg btn-primary" id="uploadBtnpob">Click to upload your Proof of Benefit</span>
                                    <br>
                                    <span><b>Optional</b></span>
                                    <br><label id="fileInputerror2" style="display:none;" class="error" for="fileInput2"></label>
                                    <input type="file" name="fileInput2" id="fileInput2" accept="image/*,application/pdf,.doc,.docx" capture="camera" style="display: none;" />
                                    <div id="preview2"></div>
                                </div>
                            </div>
                            
                        </section>

                        <h3>Agreement</h3>

                        <section>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <p style="margin-bottom: 0;">I agree, under penalty of perjury,to the following statements:</p>
                                        <p><small>(You must initial next to each statement.)</small></p>
                                        <div id="agreeItems">

                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <p><b>Typing your name in the box below will be considered your signature</b></p>
                                    <label for="signaturename">TYPE NAME</label>
                                    <input type="text" class="form-control mb-2" id="signaturename" name="signaturename">
                                    <span id="datetimespan" style="font-family: sans-serif;"></span>
                                    <input type="hidden" id="datetimeconsent" name="datetimeconsent">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-check">
                                        <input type="checkbox" name="terms" id="terms" class="form-check-input" value="Yes" onchange='radioValueCheck("terms")' checked>
                                        <label class="form-chack-label" for="terms">I agree to the <a href="#">terms of service</a> and <a href="#">privacy policy.</a></label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="sms" id="sms" class="form-check-input" value="Yes" onchange='radioValueCheck("sms")' checked>
                                        <label class="form-chack-label" for="sms">Verify benefits and delivery through SMS</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="checkbox" name="know" id="know" class="form-check-input" value="Yes" onchange='radioValueCheck("know")' checked>
                                        <label class="form-chack-label" for="know">I acknowledge that my PII will be transferred to NLAD to complete my Lifeline enrollment.</label>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="customer_id" name="customer_id" value="<?php echo $data['customer_id'] ?? NULL; ?>">
                            
                        </section>
                </form>
            </div>
        </div>
    </div>
</section>
<div class="loader" style="display: none;">
    <div class="spinner"></div>
</div>
<style>
    span.error {
    color: #8a1f11;
    display: inline-block;
    /* margin-left: 1.5em; */
    font-size: 12px;
}
    .loader{
        position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.6);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
    }
    .spinner {
  width: 50px;
  --b: 8px;
  aspect-ratio: 1;
  border-radius: 50%;
  background: #514b82;
  -webkit-mask:
    repeating-conic-gradient(#0000 0deg,#000 1deg 70deg,#0000 71deg 90deg),
    radial-gradient(farthest-side,#0000 calc(100% - var(--b) - 1px),#000 calc(100% - var(--b)));
  -webkit-mask-composite: destination-in;
          mask-composite: intersect;
  animation: l5 1s infinite;
}
@keyframes l5 {to{transform: rotate(.5turn)}}
</style>
<?php require APPROOT . '/views/inc/footer.php'; ?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
    let columnId;
  
    $(document).ready(function() {
        $(".phoneUs").mask('(000) 000-0000');
        $(".zipcode").mask('00000');
        $("#ssn").mask('000-00-0000');
        const today = new Date();
        const eighteenYearsAgo = new Date(today);
        eighteenYearsAgo.setFullYear(today.getFullYear() - 18);   
        document.getElementById("dob").setAttribute("max", eighteenYearsAgo.toISOString().split("T")); 
        
        
        
    });
    var form = $("#enrollForm");
    
    $.validator.addMethod("social", function(value, element) {
        return this.optional(element) || /^(?!000|666|9\d{2})\d{3}-(?!00)\d{2}-(?!0000)\d{4}$/.test(value);
    }, "Please enter a valid SSN (XXX-XX-XXXX)");

    $("#enrollForm").validate({
        errorPlacement: function errorPlacement(error, element) {
            element.after(error);
        },
        rules: {
            firstname:{
                required:true
            },
            lastname:{
                required:true
            },
            ssn:{
                required:true,
                social:true
            },
            dobD: {
                required: true,
                digits: true,
                range: [1, 31]
            },
            dobM: {
                required: true,
                digits: true,
                range: [1, 12]
            },
            dobY: {
                required: true,
                digits: true,
                range: [1900, 2099]
            },
            zipcode: {
                required: true,
                zipcodeUS: true
            },
            email: {
                required: true,
                email: true
            },
            phone:{
                required:true
            },
            address1:{
                required:true
            },
            city:{
                required:true
                
            },
            state:{
                required:true
            },
            zipcode:{
                required:true,
                zipcodeMatch: true
            },
            eligibility_program:{
                required:true
            },
            signaturename:{
                required:true
            },
            fileInput: {
                required: true
            },
            typeAddress:{
                required:true
            },
            anotheradult:{
                required: true
                },
            otherbenefits:{
                required: {
                    depends: function() {
                    return $('input[name="anotheradult"]:checked').val() === 'Yes';
                    }
                }
            },
            shareincome:{
                required: {
                    depends: function() {
                    return $('input[name="otherbenefits"]:checked').val() === 'Yes';
                    }
                }
            }
        },
        messages:{
            dobD: {
                required: "Please enter a day",
                digits: "Please enter a valid day",
                range: "Please enter a valid day"
            },
            dobM: {
                required: "Please enter a month",
                digits: "Please enter a valid month",
                range: "Please enter a valid month"
            },
            dobY: {
                required: "Please enter a year",
                digits: "Please enter a valid year",
                range: "Please enter a valid year"
            }
        }
    });
    var stepParam = "<?php echo $data['step']; ?>"
    var stepFromUrl = stepParam || 0;

    form.steps({
        headerTag: "h3",
        bodyTag: "section",
        transitionEffect: "slideLeft",
        startIndex: parseInt(stepFromUrl),
        onInit: function (event,currentIndex){
            if(currentIndex===1){
                let powered = $("#powered").val();
                                    let zipcode = $("#zipcode").val();
                                    let tribal = $("#tribal").val();
                                    getlifelineprograms(powered,zipcode,tribal);
                }else if (currentIndex===2){
                    //console.log(currentIndex)
                    let firstname = $("#firstname").val();
                                    let lastname = $("#lastname").val();
                                    let initials = firstname.charAt(0).toUpperCase() + lastname.charAt(0).toUpperCase();
                                    let state = $("#state").val();
                                    getAgreementsItems(state,initials);
                                    getDatetime();
                }
        },
        onStepChanging: function(event, currentIndex, newIndex) {
            //form.validate().settings.ignore = ":disabled,:hidden";
            form.validate().settings.ignore=":hidden";
            console.log(currentIndex)
            let canProceed = false;
            let customer_id="";
            let benefitProgram;
            if(newIndex<currentIndex){
                canProceed=true;
            }else{
                if (form.valid() === true) {

                var	captcha = $( '#recaptcha' );
				var response = grecaptcha.getResponse();
                
                if (currentIndex === 0) {
                    
                    console.log(currentIndex)
                    if (response.length === 0) {
						$( '.msg-error').text( "The captcha is required, please verify." );
				
						captcha.addClass( "error" );
						return false;
				
			 	 	} else {
						$( '.msg-error' ).text('');
						captcha.removeClass( "error" );
						// form submit return true
				  		//console.log('valid'); 
                        customer_id=$("#customer_id").val();
                        let step1Data = $("#enrollForm-p-0 :input").serialize();
                        //let dob = $("#dobY").val()+"-"+$("#dobM").val()+"-"+$("#dobD").val();
                        //step1Data.push({gresponse: response});
                        if(customer_id.length>0){
                            step1Data += "&customer_id="+customer_id;
                        }
                        //step1Data += "&g-recaptcha-response="+response;
                        $.ajax({
                            url: "<?php echo URLROOT; ?>/enrolls/savestep1",
                            method: "POST",
                            data: step1Data,
                            async: false, // block navigation until response
                            success: function(response) {
                                $("#load").hide();
                                console.log("Step " + currentIndex + " saved.");
                                console.log(response);
                                canProceed=false;
                                var myObj = JSON.parse(response)
                                if (myObj.status == "fail") {
                                    canProceed = false;
                                    $("#checkmessage").html("<p style='color:red'>"+myObj.msg+"</p>")
                                } else {
                                    customer_id=myObj.customer_id
                                    $("#customer_id").val(customer_id);
                                    grecaptcha.reset();
                                    canProceed = true;
                                    let powered = $("#powered").val();
                                    let zipcode = $("#zipcode").val();
                                    let tribal = $("#tribal").val();
                                    getlifelineprograms(powered,zipcode,tribal);
                                    //$("#enrollForm").steps("next");
                                }


                            },
                            error: function() {
                                alert("Error saving step " + currentIndex);
                            }
                        });
                    }

                } else if (currentIndex === 1) {
                    $("#fileInputerror").hide();
                    $("#fileInputerror").html("");
                    $("#fileInputerror2").hide();
                    $("#fileInputerror2").html("");
                    $.ajax({
                            url: "<?php echo URLROOT; ?>/enrolls/savestep2",
                            method: "POST",
                            data: {
                                eligibility_program:$("#eligibility_program").val(),
                                nv_application_id:$("#nv_application_id").val(),
                                customer_id:$("#customer_id").val(),
                                current_benefits:radioCheck('current_benefits'),
                                type_phone:($('input[name="type_phone"]').is(':checked'))?$('input[name="type_phone"]:checked').val():'',
                                govId:(base64String)?base64String:null,
                                pob:(pobbase64String)?pobbase64String:null
                            },
                            async: false, // block navigation until response
                            success: function(response) {
                                console.log("Step " + currentIndex + " saved.");
                                console.log(response);
                                var myOBj = JSON.parse(response);
                                if(myOBj.statusFile){
                                    canProceed = true;
                                    let firstname = $("#firstname").val();
                                    let lastname = $("#lastname").val();
                                    let initials = firstname.charAt(0).toUpperCase() + lastname.charAt(0).toUpperCase();
                                    let state = $("#state").val();
                                    getAgreementsItems(state,initials);
                                    getDatetime();
                                }else{
                                    canProceed = false;
                                    if(myOBj.message){
                                        $("#fileInputerror2").show();
                                        $("#fileInputerror2").html(myOBj.message);
                                    }
                                }

                            },
                            error: function() {
                                alert("Error saving step " + currentIndex);
                            }
                        });

                    
                }
            }
            
            }
            return canProceed;
            //return form.valid();
        },
        onFinishing: function(event, currentIndex) {
            form.validate().settings.ignore = ":disabled,:hidden";
            //console.log(currentIndex)
            let canProceed = false;
            //let screenshot = takescreenshot();
            
            if (form.valid() === true) {
                var $finishButton = $("a[href='#finish']"); // Select the default "Finish" button
        
                // Disable the button to prevent multiple clicks
                $finishButton.prop('disabled', true);
                 
                let step3Data = $("#enrollForm-p-2 :input").serialize();
                // Append your custom data
                //step3Data.push({ name: 'base64screen', value: screenshot  });
                //step3Data += '&base64screen='+screenshot;
               
                $.ajax({
                        url: "<?php echo URLROOT; ?>/enrolls/savestep3",
                        method: "POST",
                        data: step3Data,
                        //async: false, // block navigation until response
                        beforeSend:function(){
                            $('.loader').show();
                        },
                        success: function(response) {
                            console.log("Step " + currentIndex + " saved.");
                            console.log(response);
                            var myObject = JSON.parse(response)
                            if(myObject.status=="success"){
                                canProceed=true;
                                //$("#enrollForm").steps("next")
                            }else{
                                canProceed=false;
                                $finishButton.prop('disabled', false);
                            }

                        },
                        error: function() {
                            alert("Error saving step " + currentIndex);
                        }
                    });
                    canProceed=true;
            }
            
            return canProceed;
        },
        onFinished: function(event, currentIndex) {
            
            takeScreenshot().then(function(base64image) {
                    //console.log("Base64 Screenshot:", base64image);
                    $.ajax({
                        url: "<?php echo URLROOT; ?>/enrolls/savescreen",
                        method: "POST",
                        data: {base64screen:base64image,customer_id:$("#customer_id").val()},
                        //async: false, // block navigation until response
                        success: function(response) {
                            $('.loader').hide();
                            console.log("Step " + currentIndex + " saved.");
                            console.log(response);
                            var myObject = JSON.parse(response)
                            if(myObject.statusScreen){
                                // submit hidden form to thankyou with customer_id from myObject
                                var redirectForm = $("<form>", {
                                    action: "<?php echo URLROOT;?>/enrolls/thankyou",
                                    method: "POST"
                                });
                                redirectForm.append($("<input>", {
                                    type: "hidden",
                                    name: "customer_id",
                                    value: myObject.customer_id || $("#customer_id").val()
                                }));
                                $("body").append(redirectForm);
                                redirectForm.submit();
                            } else {
                                alert("Error saving final step. Please try again.");
                            }
                        }
                    });
                });

              
        }
    });

    $('#showShip').on('click', function() {
        $('#shipArea').toggle(); // Or use slideToggle() for animation

        const isVisible = $('#shipArea').is(':visible');
        $(this).html(isVisible ?
            '<h6>Ship to a different address? <i class="fa fa-chevron-up"></i></h6>' :
            '<h6>Ship to a different address? <i class="fa fa-chevron-down"></i></h6>');
    });

    function getlifelineprograms(etc,zipcode='',tribal=''){
        $.ajax({
            url: "<?php echo URLROOT; ?>/enrolls/getprograms/"+etc+"/"+zipcode+"/"+tribal,
            method: "GET",
            async: false, // block navigation until response
            success: function(response) {
                
                let options = JSON.parse(response)
                //console.log(options)
                var sel = $('#eligibility_program'); // Reference to the select element
                sel.empty();
                $('<option>').val('').text('Select..').appendTo(sel);
                $.each(options, function(key, val) {
                    $('<option>').val(val.id_program).text(val.name).appendTo(sel);
                });
                },
                error: function() {
                    alert("Error saving step " + currentIndex);
                }
                    });
    }

    function getAgreementsItems(states,initials){

        const template = params => `<div class="row mb-2">
                                            <div class="col-3 col-md-2">
                                                <input class="form-control" name="${params.inputname}" type="text" maxlength="2" id="${params.inputname}">
                                                <small>Initial</small>
                                            </div>
                                            <div class="col-9 col-md-10">
                                                <p style="font-size:.875em;">${params.description}</p>
                                            </div>
                                        </div>`
        $.ajax({
            url: "<?php echo URLROOT; ?>/enrolls/getagreementitems/"+states,
            method: "GET",
            async: false, // block navigation until response
            success: function(response) {
                    
                    let items = JSON.parse(response);
                    //console.log(items)
                    var agree = $('#agreeItems');
                    agree.empty();
                    $.each(items,function(key,val){
                        //console.log(val)
                        agree.append(template({inputname:val.inputname,description:val.description}));
                        $("#"+val.inputname).val(initials)
                        let newInput = $("#"+val.inputname)
                        newInput.rules('add', {
                            required: true,
                            messages: {
                                required: "This field is required"
                            }
                            });
                    });

                },
                error: function() {
                    alert("Error saving step " + currentIndex);
                }
                    });
    }

    function getDatetime(){
        var date = moment();
        let now = date.format("MMMM D, yyyy, h:mm:ss A");
        let currentdatetime = date.format("YYYY-MM-DD HH:mm:ss");
        //var formattedDateTime = date.format("YYYY-MM-DD HH:mm:ss");
        $("#datetimeconsent").val(currentdatetime);
        // Saturday, June 9th, 2007, 5:46:21 PM
        $('#datetimespan').append(now);
    }

  let base64String = "";
  let uploadedFileName = "";
  let pobbase64String = "";
  let pobuploadedFileName = "";

  $('#uploadBtn').on('click', function () {
    $('#fileInput').click();
    //$("#fileInputerror").html('')
  });

   $('#uploadBtnpob').on('click', function () {
    $('#fileInput2').click();
    //$("#fileInputerror").html('')
  });

  $('#fileInput').on('change', function () {
    const file = this.files[0];
    if (!file) return;

    $("#fileInputerror").html('');
    $("#fileInputerror").hide();
    uploadedFileName = file.name;

    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function () {
            const img = new Image();
            img.src = reader.result;

            img.onload = function () {
                const canvas = document.createElement('canvas');
                const MAX_WIDTH = 800;
                const scale = MAX_WIDTH / img.width;

                canvas.width = MAX_WIDTH;
                canvas.height = img.height * scale;

                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                // Compress image (JPEG, 70% quality)
                const compressedBase64 = canvas.toDataURL('image/jpeg', 0.7);
                base64String = compressedBase64;

                // Update preview
                let previewHtml = `<img src="${compressedBase64}" style="max-width:200px; display:block; margin-bottom:10px;">`;
                previewHtml += `<button class="btn btn-danger btn-sm" id="removeBtn">Remove</button>`;

                $('#preview').html(previewHtml);
                $('#uploadBtn').hide();
            };
        };
        reader.readAsDataURL(file);
    } else {
        // For PDF or other files
        const reader = new FileReader();
        reader.onload = function () {
            base64String = reader.result;

            let previewHtml = `<p>📄 ${file.name}</p>`;
            previewHtml += `<button class="btn btn-danger btn-sm" id="removeBtn">Remove</button>`;

            $('#preview').html(previewHtml);
            $('#uploadBtn').hide();
        };
        reader.readAsDataURL(file);
    }
});

$('#fileInput2').on('change', function () {
    const file = this.files[0];
    if (!file) return;

    $("#fileInputerror2").html('');
    $("#fileInputerror2").hide();
    pobuploadedFileName = file.name;

    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function () {
            const img = new Image();
            img.src = reader.result;

            img.onload = function () {
                const canvas = document.createElement('canvas');
                const MAX_WIDTH = 800;
                const scale = MAX_WIDTH / img.width;

                canvas.width = MAX_WIDTH;
                canvas.height = img.height * scale;

                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);

                // Compress image (JPEG, 70% quality)
                const compressedBase64 = canvas.toDataURL('image/jpeg', 0.7);
                pobbase64String = compressedBase64;

                // Update preview
                let previewHtml = `<img src="${compressedBase64}" style="max-width:200px; display:block; margin-bottom:10px;">`;
                previewHtml += `<button class="btn btn-danger btn-sm" id="removeBtn2">Remove</button>`;

                $('#preview2').html(previewHtml);
                $('#uploadBtnpob').hide();
            };
        };
        reader.readAsDataURL(file);
    } else {
        // For PDF or other files
        const reader = new FileReader();
        reader.onload = function () {
            pobbase64String = reader.result;

            let previewHtml = `<p>📄 ${file.name}</p>`;
            previewHtml += `<button class="btn btn-danger btn-sm" id="removeBtn2">Remove</button>`;

            $('#preview2').html(previewHtml);
            $('#uploadBtnpob').hide();
        };
        reader.readAsDataURL(file);
    }
});


//   $('#fileInput').on('change', function () {
//     const file = this.files[0];
//     if (!file) return;
//     $("#fileInputerror").html('')
//     const reader = new FileReader();

//     reader.onload = function () {
//       base64String = reader.result;
//       uploadedFileName = file.name;

//       // Hide upload button
//       $('#uploadBtn').hide();

//       // Show preview
//       let previewHtml = '';

//       if (file.type.startsWith('image/')) {
//         previewHtml = `<img src="${base64String}" style="max-width:200px; display:block; margin-bottom:10px;">`;
//       } else {
//         previewHtml = `<p>📄 ${file.name}</p>`;
//       }

//       // Add remove button
//       previewHtml += `<button class="btn btn-danger btn-sm" id="removeBtn">Remove</button>`;

//       $('#preview').html(previewHtml);
//     };

//     reader.readAsDataURL(file);
//   });

//    $('#fileInput2').on('change', function () {
//     const file = this.files[0];
//     if (!file) return;
//     $("#fileInputerror").html('')
//     const reader = new FileReader();

//     reader.onload = function () {
//       pobbase64String = reader.result;
//       pobuploadedFileName = file.name;

//       // Hide upload button
//       $('#uploadBtnpob').hide();

//       // Show preview
//       let previewHtml = '';

//       if (file.type.startsWith('image/')) {
//         previewHtml = `<img src="${pobbase64String}" style="max-width:200px; display:block; margin-bottom:10px;">`;
//       } else {
//         previewHtml = `<p>📄 ${file.name}</p>`;
//       }

//       // Add remove button
//       previewHtml += `<button class="btn btn-danger btn-sm" id="removeBtn2">Remove</button>`;

//       $('#preview2').html(previewHtml);
//     };

//     reader.readAsDataURL(file);
//   });


  // Remove handler
  $(document).on('click', '#removeBtn', function () {
    base64String = "";
    uploadedFileName = "";
    $('#preview').empty();
    $('#uploadBtn').show();
    $('#fileInput').val(""); // Clear file input
  });

   // Remove handler
  $(document).on('click', '#removeBtn2', function () {
    pobbase64String = "";
    pobuploadedFileName = "";
    $('#preview2').empty();
    $('#uploadBtnpob').show();
    $('#fileInput2').val(""); // Clear file input
  });

  function radioCheck(name) {
		var check;
		//$('input[name=option]').on('change', function() {
		if ($('input[name='+name+']').is(':checked')) {
			check = 'Yes';
		} else {
			check = 'No';
		}
		//});
		return check;
	}

    function radioValueCheck(id) {
		//$('input[name=option]').on('change', function() {
		if ($('#'.id).is(':checked')) {
			$('#'.id).val('Yes');
		} else {
			$('#'.id).val('No');
		}
		//});
		return check;
	}

    // Async screenshot function
function takeScreenshot() {
  return html2canvas(document.querySelector("#enrollForm")).then(function(canvas) {
    return canvas.toDataURL("image/png");
  });
}

function stateChanged(){
    let state = $("#state").val();
    if(state==="CA"){
        $("#allStatesArea").hide();
        $("#adultquestion").show();
    }else{
        $("#allStatesArea").show();
        $("#adultquestion").hide();
    }
}

function questionsArea(element,question1,extra=""){
    let answer = element.value
    
    if(answer=="Yes"){
        // $("#"+question1).hide();
        $("#"+question1).show();
        if(question1=="NoQualifyForCalifornia"){
            $('#enrollForm :input').prop('disabled', true);
            $("input[name=shareincome").prop('disabled',false)
        }
        
    }else{
        //$("#"+question1).show();
        $("#"+question1).hide();
        if(question1=="NoQualifyForCalifornia"){
            $('#enrollForm :input').prop('disabled', false);
            
        }
        $('input[name="'+question1+'"]').prop('checked', false);
        if(extra){
            $("#"+extra).hide();
            $('input[name="'+extra+'"]').prop('checked', false);
        }
    }
}

$(document).ready(function () {
    

     $.validator.addMethod("zipcodeMatch", function (value, element, params) {
    let zipcode = $("#zipcode").val();
    let city = $("#city").val().toLowerCase();
    let state = $("#state").val();
    let valid = false;

    // Usamos la validación asincrónica de jQuery Validation
    let done = this.optional(element);

    $.ajax({
      url: "https://api.zippopotam.us/us/" + zipcode,
      dataType: "json",
      async: false, // Necesario para trabajar con jQuery Validate directamente
      success: function (data) {
        let place = data.places[0];
        let apiCity = place["place name"].toLowerCase();
        let apiState = place["state abbreviation"];
        //console.log(state+apiState)
        if (city == apiCity || state == apiState) {
          valid = true;
        }
      },
      error: function () {
        valid = false;
      }
    });

    return done || valid;
  }, "City or State does not match to your Zip Code.");
})

</script>