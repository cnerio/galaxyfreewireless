<?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/navbarAdmin.php'; ?>
<style>

		.follow-wrapper {
			width: 160px;
			text-align: center;
			float: inherit;
			margin-bottom: 20px;
			background: #305586;
			border-radius: 25px;
			padding: 15px 15px 15px 20px;
			color: #fff;
		}

		.custom-size {
			max-width: 20.5% !important;
		}

		.searchInput {
			border-radius: 5px;
			float: left;
			padding: 6px;
			color: #fff;
			font-size: 13px !important;
			transition: .4s;
			line-height: 40px;
			margin-top: 0;
			margin-right: 5px;
			width: 200px !important;
			height: 24px;
			border-color: #495057;
		}

		@media (max-width: 768px) {
			.custom-size {
				max-width: 30% !important;
			}
		}

		.table-bordered {
			font: normal 12px arial, tahoma, helvetica, sans-serif;
		}

		.table-bordered td {
			padding: 8px 10px;
		}
</style>
<section class="py-5 mt-5">
    <div class="container-fluid py-5">
        <div class="row">
							<div class="col">
								<div class="card">
									<div class="card-header row">
										<div class="col-md-6">
											<h4>Lifeline Leads</h4>
										</div>

										<div class="col-md-6" style="text-align:right">
											<!-- <a class="btn btn-info text-white" id="optionStatefilter">Export States</a> <span id="msjStatefilter"></span>  -->
											<a class="btn btn-success text-white" id="optionfilter">Export Data</a> <span id="msjFilter"></span> 
										</div>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-md-6">
												<?php //echo "total emails delivered: ".$count['total']."/".$discount['total'];
												?>
												<!--<div class="input-group"><input class="form-control" type="text"><button class="btn btn-outline-primary" type="button"><i class="fa fa-search"></i></button></div>-->
											</div>
											<div class="col-md-6 offset-md-6">
												<div class="pull-right">
													<!--	<button class="btn btn-success modalCreate" id="myInput">
											<i class="fa fa-paper-plane"></i>&nbsp;Send Emails</button>-->
													<!--<button id="refresh" class="btn btn-primary" role="button" style="margin: 0 8px;"><i class="fa fa-refresh"></i>&nbsp;Refresh</button>
										<a class="btn btn-dark" role="button"><i class="fa fa-download"></i>&nbsp;Export</a>-->
												</div>
											</div>
										</div>
										<div class="grid-wrapper">
											<div class="table-responsive" id="records_content">
												<table class="table table-hover table-bordered" style="font-size:12px">
													<thead>
														<tr>
															<th width="30">#</th>
															<th width="100">Customer ID</th>
															<th>First Name</th>
															<th>Last Name</th>
															<th width="90">Phone</th>
															<th>Email</th>
															<th width="90">DOB</th>
															<th>City</th>
															<th width="50">State</th>
															<th width="68">Zipcode</th>
															<th>Order ID</th>
															<th>Order Status</th>
															<th>Program Benefit</th>
															<th>Created At</th>
															<th>Actions</th>
														</tr>
														<tr>
															<td></td>
															<td><input id="customer_id" type="text" class="form-control grid-filter"></td>
															<td><input id="first_name" type="text" class="form-control grid-filter"></td>
															<td><input id="second_name" type="text" class="form-control grid-filter"></td>
															<td><input id="phone_number" type="text" class="form-control grid-filter"></td>
															<td><input id="email" type="text" class="form-control grid-filter"></td>
															<td><input id="dob" type="text" class="form-control grid-filter"></td>
															<td><input id="city" type="text" class="form-control grid-filter"></td>
															<td><input id="state" type="text" class="form-control grid-filter"></td>
															<td><input id="zipcode" type="text" class="form-control grid-filter"></td>
															<td><input id="order_id" type="text" class="form-control grid-filter"></td>
															<td>
																<select id="order_status" class="form-select grid-filter">
																	<option value="">Please select</option>
																	<option value="New" >New</option>
																	<option value="Complete">Complete</option>
																	<option value="Duplicate">Duplicate</option>
																	<option value="Rejected TG5">Rejected TG5</option>
																	<option value="Do Not Serviceable">Do Not Serviceable</option>
																	<option value="Address Issue">Address Issue</option>
																	<option value="Docs Received">Docs Received</option>
																	<option value="Waiting for Docs">Waiting for Docs</option>
																	<option value="SOLIX PENDING">SOLIX PENDING</option>
																	<option value="TPIV">TPIV</option>
																	<option value="DEAD">DEAD</option>
																	<option value="Test">Test</option>
																</select>
															</td>
															<td><select id="program_benefit" class="form-select grid-filter">
																	<option value="">Select...</option>
																</select>
															</td>
															<td><input id="date_create" type="text" class="form-control grid-filter"></td>
															<!--<td><select id="source" type="text" class="form-control grid-filter">
																	<option value="">select an option</option>
																
																	<option value="acpsurge">acpsurge</option>
													<option value="affiliate">affiliate</option>
													<option value="galaxy">galaxy</option>
													<option value="logics">logics</option>
													<option value="merchant">merchant</option>
													<option value="usaphone">usaphone</option>
													<option value="terminal">terminal</option>
													<option value="pct">pct</option>
																</select></td>
															<td><input id="agent" type="text" class="form-control grid-filter"></td>-->
															<td>
																<div class="pull-right">
																	<button class="btn btn-outline-danger btn-sm" type="button" style="margin-right: 10px;" id="clean"><i class="fa fa-filter"></i>&nbsp;Clean Filter</button>
																</div>
															</td>
														</tr>
													</thead>
													<?php //if ($_SESSION['user_source'] == 'merchant' || $_SESSION['user_source'] == 'galaxy') { 
													?>
													<!--<tr>
											<td colspan="15">Data not available for this user</td>
										</tr>-->
													<?php //} else { 
													?>
													<tbody id="gridBody">
													</tbody>
													<?php //} 
													?>
												</table>
												</table>
												<div class="row">
													<div class="col-lg-6" id="toShow"></div>
													<div class="col-lg-6">
														<nav class="pull-right" id="pagination"> </nav>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
    </div>
</section>

<?php require APPROOT . '/views/inc/footer.php'; ?>

<script>
    const urlroot = "<?php echo URLROOT; ?>/records";

		function load(page, where = '', example_length, camposAscDesc, firstload = '') {

			var search = "";

			var parametros = {
				action: "ajax",
				page: page,
				search: where,
				example_length: example_length,
				camposAscDesc: camposAscDesc,
				firstload: firstload
			};
			//console.log(parametros)

			$("#loader").fadeIn('slow');


			$.ajax({

				url: urlroot + '/read',

				type: 'POST',

				data: {
					action: "ajax",
					page: page,
					search: where,
					length: example_length,
					camposAscDesc: camposAscDesc,
					firstload: firstload
				},


				beforeSend: function(objeto) {
					$("#gridBody").empty();
					$("#gridBody").html('<tr id="loading"><td colspan="16" align="center"><img src="https://secure-order-forms.com/surgepays/SMSReports/img/Iphone-spinner-2.gif" class="img-fluid m-3" alt=""></td></tr>');
				},

				success: function(data) {

					//console.log(data);
					$("#gridBody").empty();
					const result = document.getElementById('gridBody');

					var resultObj = JSON.parse(data);

					if (!resultObj.fields || resultObj.fields.length < 1) {
						result.innerHTML = "NO RECORDS FOUND";
					} else {


						console.log(resultObj);
						var row;
						var cell, cell1, cell2, cell3, cell4, cell5, cell6, cell7, cell8, cell9, cell10, cell11, cell12, cell13, cell14, cell15,cell16;
						var f, cnum;
						var i = 0;
						var c = 1;
						$.each(resultObj.fields, function(k, v) {

							//console.log(v);
							//f = JSON.parse(v)
							cnum = resultObj.offset + c;
							row = result.insertRow(i);
							cell = row.insertCell(0);
							cell1 = row.insertCell(1);
							cell2 = row.insertCell(2);
							cell3 = row.insertCell(3);
							cell4 = row.insertCell(4);
							cell5 = row.insertCell(5);
							cell6 = row.insertCell(6);
							cell7 = row.insertCell(7);
							cell8 = row.insertCell(8);
							cell9 = row.insertCell(9);
							cell10 = row.insertCell(10);
							cell11 = row.insertCell(11);
							cell12 = row.insertCell(12);
							cell13 = row.insertCell(13);
							//cell14 = row.insertCell(14);
							cell15 = row.insertCell(14);

							cell.innerHTML = cnum;
							cell1.innerHTML = v.customer_id;
							cell2.innerHTML = v.first_name;
							cell3.innerHTML = v.second_name;
							cell4.innerHTML = v.phone_number;
							cell5.innerHTML = v.email;
							cell6.innerHTML = v.dob;
							cell7.innerHTML = v.city;
							cell8.innerHTML = v.state;
							cell9.innerHTML = v.zipcode;
							cell10.innerHTML = v.order_id;
							cell11.innerHTML = v.order_status
							cell12.innerHTML = getProgramName(v.program_benefit);
							cell13.innerHTML = v.created_at;
							//cell14.innerHTML = v.tookstaff;
							cell15.innerHTML = '<div class="pull-right"><a href="'+urlroot+'/edit/' + v.customer_id + '" class="btn btn-outline-dark btn-sm" type="button"><i class="fa fa-pencil"></i>&nbsp;Edit</a></div>';
							/*cell14.innerHTML = '<div class="pull-right"><button class="btn btn-outline-primary btn-sm modalView" type="button" style="margin-right: 10px;" data-idorder="'+v.id+'"><i class="fa fa-eye"></i>&nbsp;View</button><a href="https://secure-order-forms.com/surgephone/acp_landings/dashboard/records/edit/'+v.id+'" class="btn btn-outline-dark btn-sm" type="button"><i class="fa fa-pencil"></i>&nbsp;Edit</a></div>';
							 */

							i++;
							c++;
						})

						$("#toShow").html('<p>Showing ' + resultObj.offsetToShow + ' to ' + cnum + ' of ' + resultObj.numrows + '</p>');

						$("#pagination").html(resultObj.pagination);
					}

					if (where != "") {

						document.getElementById("customer_id").value = where[0];

						document.getElementById("first_name").value = where[1];

						document.getElementById("second_name").value = where[2];

						document.getElementById("phone_number").value = where[3];

						document.getElementById("email").value = where[4];

						document.getElementById("dob").value = where[5];

						document.getElementById("city").value = where[6];

						document.getElementById("state").value = where[7];
						document.getElementById("zipcode").value = where[8];
						document.getElementById("order_id").value = where[9];
						document.getElementById("order_status").value = where[10];
						document.getElementById("program_benefit").value = where[11];
						document.getElementById("date_create").value = where[12];
						//document.getElementById("agent").value = where[13];
					}

				}

			})

		}

        		function camposValue() {
			var ArrayCampos = [];
			var customer_id = $("#customer_id").val().trim();
			var first_name = $("#first_name").val().trim();
			var second_name = $("#second_name").val().trim();
			var phone_number = $("#phone_number").val().trim();
			var email = $("#email").val().trim();
			var dob = $("#dob").val().trim();
			var city = $("#city").val().trim();
			var state = $("#state").val().trim();
			var zipcode = $("#zipcode").val().trim();
			var order_id = $("#order_id").val().trim();
			var order_status = $("#order_status").val().trim();
			var program_benefit = $("#program_benefit option:selected").val();
			var created_at = $("#date_create").val().trim();
			//var source = $("#source").val().trim();
			//var agent = $("#agent").val().trim();
			//var createdat = $( "#createdat" ).val();
			//console.log("AQUIIIIIIIII"+BuscarPorTodo);

			var ArrayCampos = [
				customer_id,
				first_name,
				second_name,
				phone_number,
				email,
				dob,
				city,
				state,
				zipcode,
				order_id,
				order_status,
				program_benefit,
				created_at,
				//source,
				//agent
			];

			return ArrayCampos;
		}

        	$(".grid-filter").change(function() {
			var myArray = camposValue();
			console.log(myArray);
			var camposAscDesc = '';
			var example_length = 10;
			load(1, myArray, example_length, camposAscDesc, '');

		});

	$(document).ready(function() {

			getLifelinePrograms();
			var nowhere = "";

			var example_length = 10;

			var camposAscDesc = "";
			var firstload = 'YES';

			load(1, nowhere, example_length, camposAscDesc, firstload);

    });

    function getLifelinePrograms(program = '') {
			$.ajax({
				/*data:  param,*/
				url: urlroot + '/getPrograms',
				type: 'post',
				success: function(response) {
					console.log(response)
					var Obj = JSON.parse(response);
					//console.log(Obj);
					$.each(Obj, function(key, value) {
						//console.log(value.type_id);
						if (program) {
							if (value.type_id == program) {
								$("#program_benefit").append(`<option value="${value.code}" selected>${value.name}</option>`);
							} else {
								$("#program_benefit").append(`<option value="${value.code}">${value.name}</option>`);
							}

						} else {
							$("#program_benefit").append(`<option value="${value.code}">${value.name}</option>`);
						}
					});
				}
			});
		}

    

    function getProgramName($program) {
		$prog = "";
			switch ($program) {
				case 'FPH':
					$prog = "Federal public housing";
					break;
				case '100000':
					$prog = "Federal public housing";
					break;
				// case '100001':
				// 	$prog = "Food stamps";
				// 	break;
				case '100002':
					$prog = "Household income";
					break;
				case 'MEDIC':
					$prog = "Medical assistance (medicaid)";
					break;
				case '100004':
					$prog = "Medical assistance (medicaid)";
					break;
				case 'SSI':
					$prog = "Supplemental security income (SSI)";
					break;
				case '100006':
					$prog = "Supplemental security income (SSI)";
					break;
				case '100008':
					$prog = "Tribally-Administered Temporary Assistance for Needy Families (TTANF)";
					break;
				case '100009':
					$prog = "Tribal - Head start (Income qualifying Only)";
					break;
				case '100010':
					$prog = "Tribal - Food Distribution Program on Indian Reservations (FDPIR)";
					break;
				case '100011':
					$prog = "Tribal - Bureau of Indian Affairs General Assistance";
					break;
				case '100013':
					$prog = "Program Eligibility Approved by State Administrator";
					break;
				case 'VPSBP':
					$prog = "Veteran's Pension or Survivors Benefit Programs";
					break;
				case '100014':
					$prog = "Veteran's Pension or Survivors Benefit Programs";
					break;
				case '110000':
					$prog = "School Lunch/Breakfast Program*";
					break;
				case '110001':
					$prog = "Federal Pell Grant*";
					break;
				case '110002':
					$prog = "Substantial Loss of Income*";
					break;
				case '110003':
					$prog = "Existing low income program/COVID19 program*";
					break;
				case '110004':
					$prog = "Special Supplemental Nutrition Program for Women Infants, and Children (WIC)";
					break;
				case 'SNAP':
					$prog = "Supplemental Nutrition Assistance Program (Food Stamps or SNAP)";
					break;
				case '100001':
					$prog = "Supplemental Nutrition Assistance Program (Food Stamps or SNAP)";
					break;


			}
			return $prog;
		}

        async function getData(program) {
            url = "<?php echo URLROOT; ?>/records/getProgramNames/"+program;
  const response = await fetch(url);
  if (!response.ok) {
    throw new Error('Network response was not ok');
  }
  const data = await response.json(); // or response.text(), etc.
  return data['name'];
}

$('#optionfilter').click(function() {
				var tokenqury = $("#tokenfillter").val();
				$("#msjFilter").hide();
				$("#msjFilter").append("<iframe src='"+urlroot+"/getDataReport' title='Reports'></iframe>");
				//console.log("<iframe src='"+urlroot+"/getDataReport' title='Reports'></iframe>")
			});

// $('#optionStatefilter').click(function() {
// 				var tokenqury = $("#tokenfillter").val();
// 				$("#msjStatefilter").hide();
// 				$("#msjStatefilter").append("<iframe src='"+urlroot+"'getStatesReport' title='Reports'></iframe>");
// 			});
$("#clean").on('click', function() {
			$(".grid-filter").val('');
			myArray = camposValue();
			var camposAscDesc = '';
			var example_length = 10;
			//$(".grid-filter").change();
			load(1, myArray, example_length, camposAscDesc);
		});
</script>