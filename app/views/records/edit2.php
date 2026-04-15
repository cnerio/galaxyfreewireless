<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/navbarAdmin.php'; ?>

<section class="py-5 mt-5">


				<div class="container py-5">
					<div class="row">
						<div class="col-md-12">
							<nav aria-label="breadcrumb">
								<ol class="breadcrumb">
									<li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/records">Back</a></li>
									<li class="breadcrumb-item active" aria-current="page">Edit</li>
								</ol>
							</nav>
							<h3 style="text-align:right">Customer ID: <?php echo $data['customer_id']; ?></h3>
							<hr>
						</div>
					</div>
					<div class="row mb-3">
						<div class="col-md-3">
							<div class="card">
								<div class="card-body" id="demographics-card">
									<h4 class="card-title pb-2">Demographics <a href="" id="editBtn" data-bs-toggle="modal" data-bs-target="#modalEditRecord"><i class="fa fa-pencil"></i></a></h4>
									<p><b>First Name: </b><?php echo $data['first_name']; ?></p>
									<p><b>Last Name:</b> <?php echo $data['second_name']; ?></p>
									<p><b>Email:</b> <?php echo $data['email']; ?></p>
									<p><b>DOB:</b> <?php echo $data['dob']; ?></p>
									<p><b>Contact Phone:</b> <?php echo $data['phone_number']; ?></p>
									<p><b>Last 4 SSN:</b> <?php echo $data['ssn']; ?></p>
									<p><b>Address:</b> <?php echo $data['address1'] . " " . $data['address2'] . "," . $data['city'] . "," . $data['state'] . "," . $data['zipcode']; ?></p>
									<p><b>Organization:</b> <?php echo $data['organization'];  ?></p>
									<hr>
									<p><b>Step:</b> <?php echo $data['order_step'];  ?></p>

								</div>
							</div>
							<div class="card">
								<div class="card-body">
									<h4 class="card-title pb-2">Lifeline Information</h4>
									<p id="pBefore"><b>Program Before: </b><span><?php echo $data['program_before']; ?></span><i style="cursor: pointer;" class="fa fa-pencil ml-1" onclick="editPBefore()"></i></p>
									<p id="pBenefit"><b>Program Benefit:</b> <span><?php echo $data['name']; ?></span><i style="cursor: pointer;" class="fa fa-pencil ml-1" onclick="editPBenefit()"></i></p>
									<p><b>Agreement:</b> <?php echo $data['fcc_agreement']; ?></p>
									<p><b>Transfer Consent:</b> <?php echo $data['transferconsent']; ?></p>
									<p><b>Agree Terms & Conditions:</b> <?php echo $data['agree_terms']; ?></p>
									<p><b>Agree received SMS:</b> <?php echo $data['agree_sms']; ?></p>
									<p><b>Order status:</b> <?php echo $data['order_status']; ?></p>
								
									<?php if ($data['order_id'] > 0) { ?>
										<p id="newOrderID"><b>Order ID:</b> <?php echo $data['order_id']; ?></p>
										<p><b>Account #:</b> <?php echo $data['account']; ?></p>
										<p id="newACPStatus"><b>NLAD Status:</b> <?php echo $data['acp_status']; ?></p>
										<p><b>Company Enrolled:</b> <?php echo $data['company']; ?></p>
									<?php } else { ?>
										<p id="newOrderID"><b>Order ID:</b> <?php echo $data['order_id']; ?></p>
										<p id="newACPStatus"><b>NLAD Status:</b> <?php echo $data['acp_status']; ?></p>
									<?php } ?>
								</div>
							</div>
							<!-- <div class="card">
								<div class="card-body">
									<h4 class="card-title pb-2">Merchant Information</h4>
									<p><b>Merchant's name: </b></p>
									<p><b>TID: </b><?php echo $data['surgepays_id']; ?></p>
								</div>
							</div> -->
						</div>
						<div class="col-md-9">
							<div class="row mb-3">
								<div class="col-md-4">
									<div class="card set-height">
										<div class="card-body">
											<h4 class="card-title pb-2">Documents </h4>
											<div>
											
												
											</div>
                                            <?php
                                                if(!empty($data['documents'])){
                                                    foreach($data['documents'] as $llfile){
                                                    //$folder = ($llfile['type_doc']=="ScreenshotImage")?"SignScreenshots":"Documents";

                                                    echo '<p><b>'.$llfile['type_doc'].':</b> <a href="' . $llfile['filepath'] . '" target="_blank" style="font-size:12px;">View File</a></p>';
                                                }
                                                }
                                            ?>
										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="card set-height">
										<div class="card-body">
											<h4 class="card-title pb-2">Actions</h4>
											<div id="order_area">
												<?php
												if ($data['order_id'] > 0 && $data['order_id'] != 999) { ?>
													Check NLAD Status
													<button id="checknlad" data-id_order="<?php echo $data['id']; ?>" type="button" class="btn btn-primary" onclick="runChecknlad()">Check</button>
													<div id="checkingOrderSpinner">
														<span class="loader"></span>
													</div>
													<br>
													<div class="form-check">
														<input class="form-check-input" type="checkbox" value="" id="defaultCheck1" onclick="selectDefaultCheck1()">
														<label class="form-check-label" for="defaultCheck1">
															re-submit order
														</label>
													</div>
													<br>
													<div class="alert alert-danger" role="alert" id="actions_error">Error checking NLAD Status</div>
													<p id="newOrderIdText">
													<h5></h5>
													</p>
												<?php
												} else if ($data['order_id'] == 999) { ?>
													<p>Unable to process</p>
												<?php } else {
												?>

													<button id="createOrder" data-id_order="<?php echo $data['customer_id']; ?>" type="button" class="btn btn-primary">Create Order</button>
													<br>
													<div id="resCreateOrder" class="pt-3 pb-3"></div>


													<button onclick="updateOrderId()" data-id_order="<?php echo $data['id']; ?>" type="button" class="btn btn-warning">Unable to process</button>
													<div id="creatingOrderSpinner">
														<br><br>
														<span class="loader"></span>
													</div>
													<br><br>
													<div class="alert alert-success" role="alert" id="actions_success">Success</div>
													<div class="alert alert-danger" role="alert" id="actions_error">Error creating order</div>
													<p id="newOrderIdText">
													<h5></h5>
													</p>
												<?php
												}
												?>
											</div>

											<!--<div id="consent_area" class="py-3">
							
							Send ACP Consent Link: <button id="c_sms" type="button" class="btn btn-primary">via SMS</button> <button id="c_email" type="button" class="btn btn-primary">via Email</button>
							</div>-->

										</div>
									</div>
								</div>
								<div class="col-md-4">
									<div class="card set-height">
										<div class="card-body">
											<ul class="list-unstyled list-inline">
												<li>
													<p><strong>Status: </strong> <span style="white-space: nowrap; word-break: keep-all;" id="refresh-status">
															<?php echo ($data['order_status'] == "") ? 'Not Status' : $data['order_status']; ?>
														</span> </p>
												</li>
											</ul>
											<form id="status-form" action="#" method="post" enctype="application/x-www-form-urlencoded">
												<div class="row">
													<div class="col-md-12">
														<select class="form-select auto-size mb-3" id="order_status" name="order_status">
															<option value="">Please select</option>
															<option value="Pending" <?php if ($data['order_status'] == 'Pending') {
																						echo 'selected="selected"';
																					} ?>>Pending</option>
															<option value="Complete" <?php if ($data['order_status'] == 'Complete') {
																							echo 'selected="selected"';
																						} ?>>Complete</option>
															<option value="Duplicate" <?php if ($data['order_status'] == 'Duplicate') {
																							echo 'selected="selected"';
																						} ?>>Duplicate</option>
															<option value="DNM" <?php if ($data['order_status'] == 'DNM') {
																					echo 'selected="selected"';
																				} ?>>DNM</option>
															<option value="Test" <?php if ($data['order_status'] == 'Test') {
																						echo 'selected="selected"';
																					} ?>>Test</option>
														</select>
														<input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id'] ?>" />
														<input type="hidden" name="username" id="username" value="<?php echo $_SESSION['email']; ?>">
														<input type="submit" id="submit-status" class="btn btn-secondary" value="Change Status">
													</div>
												</div>
											</form>
											<div id="status-result" class="mt-2"></div>
										</div>
									</div>
								</div>
							</div>
							<!-- <div class="row mb-3">
								<div class="col-md-12">
									<div class="card">
										<div class="card-body">
											<ul class="list-unstyled list-inline">
												<li>
													<p><strong>Agent Assigned: </strong> <span style="white-space: nowrap; word-break: keep-all;">
															<?php //echo ($data['tookstaff']!= "" && $_SESSION['rol_user'] != -1)?'Not assigned yet':$main_row['tookstaff'];
															?>
															<?php echo ($data['tookstaff'] == "") ? 'Not assigned yet' : $data['tookstaff']; ?>
														</span> </p>
												</li>
											</ul>
											<form id="staff-form" action="#" method="post" enctype="application/x-www-form-urlencoded">
												<?php //if($data['tookstaff'] != "" && $_SESSION['rol_user'] != -1){ 
												?>
												<?php if ($data['tookstaff'] != "") { ?>
													<fieldset disabled>
													<?php } else { ?>
														<fieldset>
														<?php } ?>
														<div class="row">
															<div class="col-md-12">
																<select class="form-control auto-size mb-3" id="staff-select" name="staff-agent">
																	<option value="">Please select</option>
																</select>
																<input type="hidden" name="contractProcess" value="staffTook" />
																<input type="hidden" id="customer_id" name="customer_id" value="<?php echo $data['customer_id'];; ?>" />
																<input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>" />
																<input type="hidden" name="user_email" id="user_email" value="<?php echo $_SESSION['email']; ?>" />
																<input type="hidden" name="username" id="username" value="<?php echo $_SESSION['name']; ?>">
																<input type="submit" id="assign-agent" class="btn btn-outline-secondary" value="Assign Agent">
															</div>
														</div>
														</fieldset>
											</form>
											<div id="staff-result"></div>
										</div>
									</div>
								</div>
							</div> -->
							<!--/**************INTERNAL NOTES*************/-->
							<div class="card">
								<div class="card-body">
									<div class="row">
										<div class="col-md-12">
											<nav>
												<div class="nav nav-tabs" id="nav-tab" role="tablist">
													<a class="nav-item nav-link active" id="nav-internal-tab" data-bs-toggle="tab" href="#internal-update" role="tab" aria-controls="internal_update" aria-selected="true">Internal Notes</a>
													<!-- <a class="nav-item nav-link" id="nav-contact-tab" data-bs-toggle="tab" href="#sms" role="tab" aria-controls="sms" aria-selected="false">SMS</a> -->
													<a class="nav-item nav-link" id="nav-profile-tab" data-bs-toggle="tab" href="#logs" role="tab" aria-controls="email" aria-selected="false">Showckwave Log</a>
												</div>
											</nav>
											<div class="tab-content" id="myTabContent">
												<div class="tab-pane fade show active" id="internal-update" role="tabpanel" aria-labelledby="internal_tab">
													<div class="row">
														<form id="internal-form" action="" enctype="application/x-www-form-urlencoded" style="width:100%;">
															<div class="col-md-12 mb-12 form-group">
																<div class="input-group">
																	<textarea class="form-control border-stile" aria-label="With textarea" cols="60" id="internal" name="internal" rows="5" required></textarea>
																</div>
															</div>
															<div class="col-md-12 mb-2">
																<div class="input-group-prepend"> <span class="input-group-text me-log">
																		<h6> Active User: <?php echo $_SESSION['name']; ?> </h6>
																	</span> </div>
															</div>
															<div class="col-lg-2 col-md-12 mb-2">
																<input type="hidden" id="customer_id" name="customer_id" value="<?php echo $data['customer_id']; ?>" />
																<input type="hidden" name="user_id" id="user_id" value="<?php echo $_SESSION['user_id']; ?>" />
																<input type="hidden" name="user_name" id="user_name" value="<?php echo $_SESSION['name']; ?>" />

																<input type="hidden" name="func" value="internalupdate" />
																<button value="Submit Note" id="send_internal"  class="btn btn-secondary" >Submit&nbsp;Note</button>
															</div>
														</form>
													</div>
													<div>
														<div class="row">
															<div class="col-12 col-md-12" id="msg-result"></div>
															<div class="col-12 col-md-12" id="wrapp-communication"></div>
															<div class="col-12 col-md-12" id="wrapp-notes">
															</div>
														</div>
													</div>
												</div>
												<div class="tab-pane fade" id="sms" role="tabpanel" aria-labelledby="sms-tab">
													<form id="sms-form" action="#" enctype="application/x-www-form-urlencoded">
														<div class="row">
															<div class="col-md-6 mb-6"></div>
															<div class="col-md-6 mb-6 form-group">
																<select class="form-control pull-left mt-3 mb-3" id="scriptsms" name="scriptsms" onchange="showScriptSelected('sms');">
																	<option value="">Select script</option>
																</select>
															</div>
														</div>
														<div class="row">
															<div class="col-md-12 mb-12">
																<textarea class="form-control mb-3" cols="60" id="sms-textarea" name="sms-textarea" rows="5"></textarea>
															</div>
															<div class="col-md-4 mb-4">
																<input type="hidden" id="id_order" name="id_order" value="<?php echo $data['id'];; ?>" />
																<input type="hidden" id="userid_active" name="userid_active" value=" <?php echo $_SESSION['user_id']; ?>" />
																<input type="hidden" name="user_active" id="user_active" value="<?php echo $_SESSION['name']; ?>" />
																<button type="button" id="send_sms" class="btn btn-outline-secondary" data-toggle="modal" data-target="#sendsmsModal">Submit SMS</button>
															</div>
														</div>
													</form>

												</div>
												<div class="tab-pane fade" id="logs" role="tabpanel" aria-labelledby="profile-tab">
													<div class="row">
														<div class="col">
																	<div class="row">
																		<div class="col-12 col-md-12 mt-2 mb-3">
																			<label for="ACP_Messages">APIS Response</label>
																		</div>
																		<div class="col-12 col-md-12">
																			<div id="accordion" role="tablist" aria-multiselectable="true">
																			</div>
																		</div>
																	</div>
														</div>
													</div>
													
												</div>

												<div id="communication-result"></div>
											</div>
											<div id="comm-confirmation" class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
												<div class="modal-dialog modal-sm">
													<div class="modal-content" id="confirm-result"> </div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!--/***********************************/-->
							<!--Internal Update Sent-->
							
							<!--/***********************************/-->
						</div>
					</div>
				</div>






	<div class="modal fade" id="modalEditRecord" tabindex="-1" role="dialog" aria-labelledby="modalEditRecordLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalEditRecordLabel">Edit Record</h5>
					<button type="button" class="close closeRecordEditModal" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form id="formRecordEdit">
					<div class="modal-body">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group"><label>First Name</label>
									<input name="firstname_edit" id="firstname_edit" type="text" class="form-control" value="<?php echo $data['first_name']; ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group"><label>Last Name</label>
									<input name="lastname_edit" id="lastname_edit" type="text" class="form-control" value="<?php echo $data['second_name']; ?>">
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group"><label>Email</label>
									<input name="email_edit" id="email_edit" type="text" class="form-control" value="<?php echo $data['email']; ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group"><label>DOB</label>
									<input name="dob_edit" id="dob_edit" type="text" class="form-control" value="<?php echo $data['dob']; ?>">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group"><label>Contact Phone</label>
									<input name="phone_edit" id="phone_edit" type="text" class="form-control" value="<?php echo $data['phone_number']; ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group"><label>SSN</label>
									<input name="ssn_edit" id="ssn_edit" type="text" class="form-control" value="<?php echo $data['ssn']; ?>">
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Street</label>
									<input name="address1_edit" id="address1_edit" type="text" class="form-control" value="<?php echo $data['address1']; ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Apt/Unit/Suite</label>
									<input name="address2_edit" id="address2_edit" type="text" class="form-control" value="<?php echo $data['address2']; ?>">
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="">City</label>
									<input name="city_edit" id="city_edit" type="text" class="form-control" value="<?php echo $data['city']; ?>">
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="">State</label>
									<select class="form-select" name="state_edit" id="state_edit" aria-required="true" required>
										<option value="">Select State</option>
										<option value="AL" <?php echo ($data['state'] == "AL") ? "selected" : "" ?>>Alabama</option>
										<option value="AK" <?php echo ($data['state'] == "AK") ? "selected" : "" ?>>Alaska</option>
										<option value="AZ" <?php echo ($data['state'] == "AZ") ? "selected" : "" ?>>Arizona</option>
										<option value="AR" <?php echo ($data['state'] == "AR") ? "selected" : "" ?>>Arkansas</option>
										<option value="CA" <?php echo ($data['state'] == "CA") ? "selected" : "" ?>>California</option>
										<option value="CO" <?php echo ($data['state'] == "CO") ? "selected" : "" ?>>Colorado</option>
										<option value="CT" <?php echo ($data['state'] == "CT") ? "selected" : "" ?>>Connecticut</option>
										<option value="DE" <?php echo ($data['state'] == "DE") ? "selected" : "" ?>>Delaware</option>
										<option value="DC" <?php echo ($data['state'] == "DC") ? "selected" : "" ?>>District of Columbia</option>
										<option value="FL" <?php echo ($data['state'] == "FL") ? "selected" : "" ?>>Florida</option>
										<option value="GA" <?php echo ($data['state'] == "GA") ? "selected" : "" ?>>Georgia</option>
										<option value="HI" <?php echo ($data['state'] == "HI") ? "selected" : "" ?>>Hawaii</option>
										<option value="ID" <?php echo ($data['state'] == "ID") ? "selected" : "" ?>>Idaho</option>
										<option value="IL" <?php echo ($data['state'] == "IL") ? "selected" : "" ?>>Illinois</option>
										<option value="IN" <?php echo ($data['state'] == "IN") ? "selected" : "" ?>>Indiana</option>
										<option value="IA" <?php echo ($data['state'] == "IA") ? "selected" : "" ?>>Iowa</option>
										<option value="KS" <?php echo ($data['state'] == "KS") ? "selected" : "" ?>>Kansas</option>
										<option value="KY" <?php echo ($data['state'] == "KY") ? "selected" : "" ?>>Kentucky</option>
										<option value="LA" <?php echo ($data['state'] == "LA") ? "selected" : "" ?>>Louisiana</option>
										<option value="ME" <?php echo ($data['state'] == "ME") ? "selected" : "" ?>>Maine</option>
										<option value="MD" <?php echo ($data['state'] == "MD") ? "selected" : "" ?>>Maryland</option>
										<option value="MA" <?php echo ($data['state'] == "MA") ? "selected" : "" ?>>Massachusetts</option>
										<option value="MI" <?php echo ($data['state'] == "MI") ? "selected" : "" ?>>Michigan</option>
										<option value="MN" <?php echo ($data['state'] == "MN") ? "selected" : "" ?>>Minnesota</option>
										<option value="MS" <?php echo ($data['state'] == "MS") ? "selected" : "" ?>>Mississippi</option>
										<option value="MO" <?php echo ($data['state'] == "MO") ? "selected" : "" ?>>Missouri</option>
										<option value="MT" <?php echo ($data['state'] == "MT") ? "selected" : "" ?>>Montana</option>
										<option value="NE" <?php echo ($data['state'] == "NE") ? "selected" : "" ?>>Nebraska</option>
										<option value="NV" <?php echo ($data['state'] == "NV") ? "selected" : "" ?>>Nevada</option>
										<option value="NH" <?php echo ($data['state'] == "NH") ? "selected" : "" ?>>New Hampshire</option>
										<option value="NJ" <?php echo ($data['state'] == "NJ") ? "selected" : "" ?>>New Jersey</option>
										<option value="NM" <?php echo ($data['state'] == "NM") ? "selected" : "" ?>>New Mexico</option>
										<option value="NY" <?php echo ($data['state'] == "NY") ? "selected" : "" ?>>New York</option>
										<option value="NC" <?php echo ($data['state'] == "NC") ? "selected" : "" ?>>North Carolina</option>
										<option value="ND" <?php echo ($data['state'] == "ND") ? "selected" : "" ?>>North Dakota </option>
										<option value="OH" <?php echo ($data['state'] == "OH") ? "selected" : "" ?>>Ohio</option>
										<option value="OK" <?php echo ($data['state'] == "OK") ? "selected" : "" ?>>Oklahoma</option>
										<option value="OR" <?php echo ($data['state'] == "OR") ? "selected" : "" ?>>Oregon</option>
										<option value="PA" <?php echo ($data['state'] == "PA") ? "selected" : "" ?>>Pennsylvania</option>
										<option value="PA" <?php echo ($data['state'] == "PR") ? "selected" : "" ?>>Puerto Rico</option>
										<option value="RI" <?php echo ($data['state'] == "RI") ? "selected" : "" ?>>Rhode Island</option>
										<option value="SC" <?php echo ($data['state'] == "SC") ? "selected" : "" ?>>South Carolina</option>
										<option value="SD" <?php echo ($data['state'] == "SD") ? "selected" : "" ?>>South Dakota</option>
										<option value="TN" <?php echo ($data['state'] == "TN") ? "selected" : "" ?>>Tennessee</option>
										<option value="TX" <?php echo ($data['state'] == "TX") ? "selected" : "" ?>>Texas</option>
										<option value="UT" <?php echo ($data['state'] == "UT") ? "selected" : "" ?>>Utah </option>
										<option value="VT" <?php echo ($data['state'] == "VT") ? "selected" : "" ?>>Vermont</option>
										<option value="VA" <?php echo ($data['state'] == "VA") ? "selected" : "" ?>>Virginia</option>
										<option value="WA" <?php echo ($data['state'] == "WA") ? "selected" : "" ?>>Washington</option>
										<option value="WV" <?php echo ($data['state'] == "WV") ? "selected" : "" ?>>West Virginia</option>
										<option value="WI" <?php echo ($data['state'] == "WI") ? "selected" : "" ?>>Wisconsin</option>
										<option value="WY" <?php echo ($data['state'] == "WY") ? "selected" : "" ?>>Wyoming</option>
										<option value="<?php echo $data['state']; ?>"><?php echo $data['state']; ?></option>
									</select>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label for="">Zipcode</label>
									<input name="zipcode_edit" id="zipcode_edit" type="text" class="form-control" value="<?php echo $data['zipcode']; ?>">
								</div>
							</div>
						</div>



					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary closeRecordEditModal" data-bs-dismiss="modal">Close</button>
						<input type="hidden" value="Addusernew" name="acttion">
						<input type="hidden" id="idlogui" name="idlogui" value='<?php echo $_SESSION['user_id'];  ?>'>
						<button type="submit" id="savenew" class="btn btn-primary">Save</button>
					</div>
					<div style="padding: 13px;" id="msjresusersAdd">
					</div>
					<input type="hidden" value="<?php echo $data['id']; ?>" id="leadId">
					<input type="hidden" value="<?php echo $data['company']; ?>" id="company">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<div id="update-record-result" class="mt-2"></div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- modal create note -->
	<div class="modal fade" id="modalAddNote" tabindex="-1" role="dialog" aria-labelledby="modalAddNoteLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="modalAddNoteLabel">Add internal note</h5>
					<button type="button" class="close closeAddNoteModal right" data-bs-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form id="formAddNote">
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group"><label>Note:</label>
									<textarea class="form-control" id="internalNote" rows="5"></textarea>
								</div>
							</div>
						</div>

					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary closeAddNoteModal" data-bs-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary">Submit</button>
					</div>
					<!-- <input type="hidden" value="<?php echo $data['customer_id']; ?>" id="leadId"> -->
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<div id="add-internal-note" class="mt-2"></div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- end modal create note -->
</section>

<?php require APPROOT . '/views/inc/footer.php'; ?>

<script>
		//const urlroot = "https://secure-order-forms.com/surgephone/LifelineProject/Blitzs";
        const urlroot = "<?php echo URLROOT; ?>/records/";
        function getApisResponse(customer_id) {

			$.ajax({

				url: urlroot + '/getResponses',

				type: "POST",

				data: {
					customer_id: customer_id
				},

				success: function(data) {
					var Obj = JSON.parse(data);
					var notes_html = '';
					var html_head = '';
					var imglink = '';
					var html_body = '';
					var i = 0
					if (data.length > 0) {
						$.each(Obj, function(key, val) {
							console.log(JSON.stringify(val.response));
							html_head = '<div class="card-header"><h5 class="card-title commu-title" style="display:inline-block">' + val.title + '</h5><small style="float:right;">' + val.created_at + ' | ' + val.id_log + '</small></div>';
							html_body = '<div id="collapse-' + i + '" class="collapse show" role="tabpanel" aria-labelledby="heading-' + i + '">';
							html_body += '<div class="card-block">';
							html_body += '<p class="card-text"><pre>' + JSON.stringify(val.response, null, '\t') + '</pre></p>';
							html_body += '</div>';
							html_body += '</div>';

							notes_html += '<div class="card">' + html_head + '<div class="card-body">' + html_body + '</div></div>';

							i = i++;

						});
						$('#accordion').html(notes_html);

					}
				}
			});
		}


		function getNotes(customer_id) {

			$.ajax({

				url: urlroot + '/getNotes',

				type: "POST",

				data: {
					customer_id: customer_id
				},

				success: function(data) {
					var Obj = JSON.parse(data);
					var notes_html = '';
					var html_head = '';
					var imglink = '';
					var html_body = '';
					if (data.length > 0) {
						$.each(Obj, function(key, val) {
							console.log(val.message_send);
							if (val.type_note == 'Email') {

								html_head = '<div class="card-header"><h5 class="card-title commu-title">EMAIL SENT</h5><small style="float:right;">' + val.date_note + ' | ' + val.user_name + '</small></div>';
								html_body = '<p class="card-text">' + val.message_send + '</p>'

							} else if (val.type_note == 'SMS') {

								html_head = '<div class="card-header" style="background-color: #fcf08a;"><h5 class="card-title commu-title">SMS SENT</h5><small style="float:right;">' + val.date_note + ' | ' + val.user_name + '</small></div>';

								html_body = '<p class="card-text">' + val.message_send + '</p>'

							} else if (val.type_note == 'SMS RESPONSE') {

								html_head = '<div class="card-header"><h5 class="card-title commu-title">SMS RESPONSE</h5><small style="float:right;">' + val.date_note + ' | ' + val.user_name + '</small></div>';
								html_body = '<p class="card-text">' + val.message_receive + '</p>'

							} else if (val.type_note == 'MMS RESPONSE') {

								html_head = '<div class="card-header"><h5 class="card-title commu-title">MEDIA RESPONSE</h5><small style="float:right;">' + val.date_note + ' | ' + val.user_name + '</small></div>';

								imglink = '<a href="https://secure-order-forms.com/surgepays/blitzSMS_files/' + val.message_receive + '" target="_blank">view file</a>';
								html_body = '<p class="card-text">' + imglink + '</p>';

							} else {

								html_head = '<div class="card-header"><h6 class="card-title commu-title">NOTE <small style="float:right;">' + val.date_note + ' | ' + val.user_name + '</small></h6></div>';
								html_body = '<p class="card-text">' + val.message_send + '</p>'
							}

							notes_html += '<div class="card mb-2">' + html_head + '<div class="card-body">' + html_body + '</div></div>';

						});
						$('#wrapp-notes').html(notes_html);

					}
				}
			});
		}

		function showScriptSelected(input) {
			var script_body = $("#script" + input + " option:selected").attr("data-script");
			if (script_body != "") {
				$("#" + input + "-textarea").val(script_body);
			}
		}

		function getScripts(source = '') {
			//console.log(source);
			$.ajax({
				data: {
					source: source
				},
				url: urlroot + '/getScripts',
				type: "POST",
				success: function(response) {
					//console.log(response)
					var Obj = JSON.parse(response);
					//console.log(Obj);
					$.each(Obj, function(key, value) {
						//console.log(value.type_id);
						if (response.length > 0) {
							$("#scriptsms").append(`<option value="${value.id_script}" data-script="${value.script_body}">${value.script_name}</option>`);
						} else {
							$("#scriptsms").append(`<option value="0">No scripts available</option>`);
						}
					});
				}
			});
		}

		function getStaffList() {
			$.ajax({
				url: urlroot + '/getStaffs',
				type: "POST",
				success: function(response) {
					//console.log(response)
					var Obj = JSON.parse(response);
					//console.log(Obj);
					$.each(Obj, function(key, value) {
						//console.log(value.type_id);
						if (response.length > 0) {
							$("#staff-select").append(`<option value="${value.name}" user-id="${value.user_id}" user-email="${value.email}">${value.name}</option>`);
						} else {
							$("#staff-select").append(`<option value="0">No User Available</option>`);
						}
					});
				}
			});
		}


		$(document).ready(function() {

			var success_response = '<div class="alert alert-success" role="alert">Success</div>';
			var fail_response = '<div class="alert alert-danger" role="alert">Error, something is missing</div>';
			
			var customer_id = '<?php echo  $data['customer_id']; ?>';
			//getScripts(source);
			getStaffList();
			getNotes(customer_id);
			getApisResponse(customer_id);

			$("#creatingOrderSpinner").hide();
			$("#checkingOrderSpinner").hide();
			$("#actions_error").hide();
			$("#actions_success").hide();
			$("#newOrderIdText").hide();

			$("#createOrder").click(function() {
				var customer_id = $(this).data("id_order");
				$("#creatingOrderSpinner").show();
				$.ajax({
					url: urlroot+'shockwaveProcess/'+customer_id,
					type: "GET",
					dataType: "json",
					success: function(data) {
						$("#creatingOrderSpinner").hide();

						console.log(data)

						if (data.status) {
							$("#createOrder").prop("disabled", true);
							$("#resCreateOrder").html('<div class="alert alert-success" role="alert">'+ data.msg + '<br> ' + data.status_text+'</div>');
							//$("#resCreateOrder").html('<div class="alert alert-success" role="alert">'+ data.msg + '<br> ' + data.msgDetail+'</div>');
							$("#newOrderID").html(`<b>Order ID:</b> ${data.order_id}`)

							// $("#order_area").html(`Check NLAD Status <button id="checknlad" data-id_order="${data.order_id}" type="button" class="btn btn-primary" onclick="runChecknlad()">Check</button>
							// <div id="checkingOrderSpinner">
							// 	<span class="loader"></span>
							// </div>
							// <br>
							// <div class="form-check">
							// 	<input class="form-check-input" type="checkbox" value="" id="defaultCheck1" onclick="selectDefaultCheck1()">
							// 	<label class="form-check-label" for="defaultCheck1">
							// 		re-submit order
							// 	</label>
							// </div>`);
							// $("#newOrderIdText").html(`<h5>New Order ID: ${data.order_id} </h5>`);
							// $("#newACPStatus").html(`<b>Nlad Status:</b> ${myObj.Status}`);

						} else {
							$("#resCreateOrder").html('<div class="alert alert-danger" role="alert">'+ data.msg + '</div>');
							//$("#resCreateOrder").html('<div class="alert alert-danger" role="alert">'+ data.msg + '<br> ' + data.msgDetail+'</div>');
						}

					}
				});
			})

			$("#ssn-btn").click(function() {
				var ssn = $("#lastssn").val();
				var idrecord = $("#id_order").val();
				$.ajax({
					url: urlroot + '/savessn',
					type: "POST",
					data: {
						idrecord: idrecord,
						ssn: ssn
					},
					success: function(data) {

					}
				})
			})


			$("#send_sms").click(function() {
				event.preventDefault();

				var id_order = $("#id_order").val();
				var id_user = $("#userid_active").val();
				var user_name = $("#user_active").val();
				var id_script = $("#scriptsms option:selected").val();
				var scriptsms = $("#sms-textarea").val();
				var parameter = {
					"id_order": id_order,
					"id_user": id_user,
					"user_name": user_name,
					"id_script": id_script,
					"sms_textarea": scriptsms,
				}
				console.log(parameter);
				$.ajax({
					url: urlroot + '/sendSMS',

					type: "POST",

					data: parameter,

					success: function(data) {
						console.log('Data received ' + data);
						var myObj = JSON.parse(data);
						if (myObj.response == 'OK') {
							$("#communication-result").html(success_response)
							getNotes(customer_id);
						} else {
							$("#communication-result").html(fail_response)
						}
						setTimeout(function() {
							$("#communication-result").html('')
						}, 5000);
						$("#sms-textarea").val('');
					}
				});
			})

			$("#send_internal").click(function() {
				event.preventDefa