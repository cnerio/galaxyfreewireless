<?php
require APPROOT . '/views/inc/header.php';
require APPROOT . '/views/inc/navbarAdmin.php';
$id_user = $_SESSION['user_id'];
?>
<style>
    input.error,select.error {
    background: rgb(251, 227, 228);
    border: 1px solid #fbc2c4;
    color: #8a1f11;
}
label.error{
    color: #8a1f11;
    display: inline-block;
    /* margin-left: 1.5em; */
    font-size: 12px;
}
</style>
<section class="py-5 mt-5">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <!-- <div class="row">
                <div class="col-12 pb-5">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-target="#ModalAddUsers">
                        Add New User
                    </button>
                </div>
            </div> -->
                <!-- <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <div id="gettable">

                        </div>
                    </div>
                </div>
            </div> -->
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Users</h4>
                            </div>

                            <div class="col-md-6" style="text-align:right">
                                <!-- <a class="btn btn-info text-white" id="optionStatefilter">Export States</a> <span id="msjStatefilter"></span>  -->
                                <a class="btn btn-primary text-white" id="adduser" data-bs-toggle="modal" data-bs-target="#ModalAddUsers">Add User</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="grid-wrapper">
                            <div class="table-responsive" id="records_content">
                                <table class="table table-hover table-bordered" style="font-size:12px">
                                    <thead>
                                        <tr>
                                            <th width="30">#</th>
                                            <th>User Name</th>
                                            <th>Email</th>
                                            <th>Rol</th>
                                            <th>Actions</th>
                                        </tr>
                                        <tr>
                                            <td><input type="hidden" id="user_id" value="<?php echo $_SESSION['user_id'];?>"></td>
                                            <td><input id="name" type="text" class="form-control grid-filter"></td>
                                            <td><input id="email" type="text" class="form-control grid-filter"></td>
                                            <td><select id="rol" type="text" class="form-select grid-filter">
                                                    <option value="">Select...</option>
                                                    <option value="00">Regular User</option>
                                                    <option value="1">Admin User</option>
                                                </select>
                                            </td>
                                            <td width="30"><select id="active" type="text" class="form-select grid-filter">
                                                    <option value="">Select...</option>
                                                    <option value="00">Inactive</option>
                                                    <option value="1">Active</option>
                                                </select>
                                            </td>
                                            <td>
                                                <div class="pull-right">
                                                    <button class="btn btn-outline-danger btn-sm" type="button" style="margin-right: 10px;" id="clean"><i class="fa fa-filter"></i>&nbsp;Clean</button>
                                                </div>
                                            </td>
                                        </tr>
                                    </thead>
                                    <tbody id="gridBody"></tbody>
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

    <!-- Modal Editar -->
    <div class="modal fade" id="EditUserModal" tabindex="-1" role="dialog" aria-labelledby="EditUserModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="EditUserModalLabel">Edit User</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" id="formEdit_users">
                    <div class="modal-body">

                    <div class="form-group">
                        <label for="editname">Name<sub>*</sub></label>
                        <input type="text" name="editname" id="editname" class="form-control form-control-lg">
                    </div>

                    <div class="form-group">
                        <label for="editemail">Email<sub>*</sub></label>
                        <input type="text" id="editemail" name="editemail" class="form-control form-control-lg">
                    </div>

                    
                    <div class="form-group">
                        <label for="editrol">Rol<sub>*</sub></label>
                        <select name="editrol" id="editrol" class="form-select form-control-lg">
                            <option value="">select..</option>
                            <option value="0">Regular User</option>
                            <option value="1">Admin User</option>
                        </select>
                    </div>
                        
                        <div class="form-group"><label>Reset Password</label><br>
                                <input type="radio" name="resetPass" value="N" class="" checked="" id="radio"> No
                                <input type="radio" name="resetPass" value="Y" class="" id="radio2"> Yes
                        </div>
                        <div id="passArea" style="display: none;">
                            <div class="form-group">
                            <label>New Password <span class="required">*</span></label>
                                <input type="password" name="editpass" id="editpass" class="form-control">
                        </div>
                        <div class="form-group">
                        <label for="editconfirmpass">Confirm Password<sub>*</sub></label>
                        <input type="password" id="editconfirmpass" name="editconfirmpass" class="form-control form-control-lg">
                    </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="hidden" id="id_user" name="id_user">
                        <input type="hidden" id="acttion" name="acttion" value="editusers">
                        <button type="button" id="updateUser" class="btn btn-primary">Update</button>
                    </div>
                    <div style="padding: 13px;" id="updateResult"></div>

                </form>
            </div>
        </div>
    </div>

    <!-- Modal Add -->
    <div class="modal fade" id="ModalAddUsers" tabindex="-1" role="dialog" aria-labelledby="ModalAddUsersLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalAddUsersLabel">Add New User</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                
                <form id="formUserAdd">
                    <div class="modal-body">
                            <div class="form-group">
                        <label for="addname">Name<sub>*</sub></label>
                        <input type="text" name="addname" id="addname" class="form-control form-control-lg">
                    </div>

                    <div class="form-group">
                        <label for="addemail">Email<sub>*</sub></label>
                        <input type="addemail" name="addemail" class="form-control form-control-lg">
                    </div>
                    
                    <div class="form-group">
                        <label for="addpassword">Password<sub>*</sub></label>
                        <input type="password"  id="addpassword" name="addpassword" class="form-control form-control-lg ">
                    </div>

                    <div class="form-group">
                        <label for="addconfirm_password">Confirm Password<sub>*</sub></label>
                        <input type="password" id="addconfirm_password" name="addconfirm_password" class="form-control form-control-lg">
                    </div>
                    <div class="form-group">
                        <label for="addrol">Rol<sub>*</sub></label>
                        <select name="addrol" id="addrol" class="form-select form-control-lg">
                            <option value="">select..</option>
                            <option value="0">Regular User</option>
                            <option value="1">Admin User</option>
                        </select>
                    </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <input type="hidden" value="ADD" name="action">
                        <button type="button" id="savenew" class="btn btn-primary">Save</button>
                    </div>
                    <div style="padding: 13px;" id="msjresusersAdd">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Delete -->

    <div class="modal fade" id="DeleteUsersModal" tabindex="-1" role="dialog" aria-labelledby="DeleteUsersModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="DeleteUsersModalLabel">Delete User</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="deleteUsersUp">
                    <div class="modal-body">
                        Do you want to delete the user <b id="nameDeleteUser"></b>?
                        <input type="hidden" name="idusersDelete" id="idusersDelete">
                        <input type="hidden" name="action" value="deleteuserUp">
                        <input type="hidden" id="idlog" name="idlog" value='<?php echo $id_user;  ?>'>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" id="deleteok" class="btn btn-danger">Delete</button>
                    </div>
                    <div style="padding: 13px;" id="msjresusersDelete"></div>
                </form>
            </div>
        </div>
    </div>
</section>
<?php require APPROOT . '/views/inc/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<link rel="stylesheet" href="https:////cdn.datatables.net/2.3.1/css/dataTables.dataTables.min.css">
<script src="https://cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>
<script>
    const urlroot = "<?php echo URLROOT; ?>/users";
    const iduser = "<?php echo $_SESSION['user_id'];?>"

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
                if (resultObj.fields.lentgh < 1) {
                    result.innerHTML = "NO RECORDS FOUND";
                } else {
                    console.log(resultObj);
                    var row;
                    var cell, cell1, cell2, cell3, cell4, cell5, cell6, cell7, cell8, cell9, cell10, cell11, cell12, cell13, cell14, cell15;
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
                        cell.innerHTML = cnum;
                        //cell1.innerHTML = v.id;
                        cell1.innerHTML = v.name;
                        cell2.innerHTML = v.email;
                        cell3.innerHTML = (v.rol==1)?"Admin User":"Regular User";
                        cell4.innerHTML = (v.active==1)?'<span class="badge text-bg-success">Active</span>':'<span class="badge text-bg-danger">Inactive</span>';
                        //cell13.innerHTML = v.source;
                        //cell14.innerHTML = v.tookstaff;
                        cell5.innerHTML = '<div class="pull-right"><button class="btn btn-outline-dark btn-sm editUser" data-user="'+v.id+'" data-bs-toggle="modal" data-bs-target="#EditUserModal"><i class="fa fa-pencil"></i>&nbsp;Edit</button><button data-user="'+v.id+'" data-bs-toggle="modal" data-bs-target="#DeleteUsersModal" class="btn btn-outline-danger btn-sm deleteUser" type="button"><i class="fa fa-close"></i>&nbsp;Delete</a></div>';
                        /*cell14.innerHTML = '<div class="pull-right"><button class="btn btn-outline-primary btn-sm modalView" type="button" style="margin-right: 10px;" data-idorder="'+v.id+'"><i class="fa fa-eye"></i>&nbsp;View</button><a href="https://secure-order-forms.com/surgephone/acp_landings/dashboard/records/edit/'+v.id+'" class="btn btn-outline-dark btn-sm" type="button"><i class="fa fa-pencil"></i>&nbsp;Edit</a></div>';
                         */
                        i++;
                        c++;
                    })
                    $("#toShow").html('<p>Showing ' + resultObj.offsetToShow + ' to ' + cnum + ' of ' + resultObj.numrows + '</p>');
                    $("#pagination").html(resultObj.pagination);
                }
                if (where != "") {
                    //document.getElementById("user_id").value = where[0];
                    document.getElementById("name").value = where[0];
                    document.getElementById("email").value = where[1];
                    document.getElementById("rol").value = where[2];
                    document.getElementById("active").value = where[3];
                }
            }
        })
    }
    function camposValue() {
			var ArrayCampos = [];
			//var user_id = $("#user_id").val();
			var username = $("#name").val();
			var email = $("#email").val();
			var rol = $("#rol option:selected").val();
            var active = $("#active option:selected").val();

			var ArrayCampos = [
                username,
                email,
                rol,
                active
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

			load(1, "", 10, "", "YES");
            
        });



    $('#gridBody').on('click', '.editUser', function() {
        //console.log("click")
    //var value = $(this).data('user'); // or $(this).attr('data-val')
    var userId = $(this).attr('data-user');
    //console.log("Clicked value:", value);
    // Example use
    $.post(urlroot+'/getUser', { id: userId }, function(response) {
        //console.log("User data:", response);
        var resObj = JSON.parse(response);
        $("#editname").val(resObj.name);
        $("#editemail").val(resObj.email);
        $("#editrol").val(resObj.rol)
        $("#id_user").val(resObj.id)
    });
});

$("#gridBody").on('click','.deleteUser',function(){
    var userId = $(this).attr('data-user');
    //console.log("Clicked value:", value);
    // Example use
    $.post(urlroot+'/getUser', { id: userId }, function(response) {
        //console.log("User data:", response);
        var resObj = JSON.parse(response);
        $("#idusersDelete").val(resObj.id)
        $("#nameDeleteUser").html(resObj.name);
    });
})

    $("#savenew").on('click',function(e){
        console.log("savenew")
        e.preventDefault();
        
        if($('#formUserAdd').valid()){
            $.ajax({
                url: urlroot+"/adduser",
                type: 'post',
                data: $("#formUserAdd").serialize(),
                success: function(response) {
                    var myObj = JSON.parse(response)
                    if(myObj.status=="success"){
                        $("#msjresusersAdd").html('<div class="alert alert-success" role="alert">'+myObj.msg+'</div>');
                        load(1, "", 10, "", "YES");
                        //form.reset();
                    }else{
                         $("#msjresusersAdd").html('<div class="alert alert-danger" role="alert">'+myObj.msg+'</div>');
                    }
                }
            });
        }
    })

    $("#updateUser").on('click',function(e){
        console.log("updateUser")
        $("#updateResult").html('');
        e.preventDefault();
        
        if($('#formEdit_users').valid()){
            $.ajax({
                url: urlroot+"/updateuser",
                type: 'post',
                data: $("#formEdit_users").serialize(),
                success: function(response) {
                    var myObj = JSON.parse(response)
                    if(myObj.status=="success"){
                        $("#updateResult").html('<div class="alert alert-success" role="alert">'+myObj.msg+'</div>');
                        load(1, "", 10, "", "YES");
                        //form.reset();
                    }else{
                         $("#updateResult").html('<div class="alert alert-danger" role="alert">'+myObj.msg+'</div>');
                    }
                }
            });
        }
    })

    $("#deleteok").on('click',function(){
        $("#msjresusersDelete").html('');
        var userId = $("#idusersDelete").val();
        $.post(urlroot+'/removeUser', { id: userId }, function(response) {
        var myObj = JSON.parse(response)
                    if(myObj.status=="success"){
                        $("#msjresusersDelete").html('<div class="alert alert-success" role="alert">'+myObj.msg+'</div>');
                        load(1, "", 10, "", "YES");
                        //form.reset();
                    }else{
                         $("#msjresusersDelete").html('<div class="alert alert-danger" role="alert">'+myObj.msg+'</div>');
                    }
    });
    })

    $('#formUserAdd').validate({
            errorPlacement: function errorPlacement(error, element) {
            element.after(error);
        },
        rules: {
            addname: "required",
            addemail:{
                required:true,
                email:true
            },
            addpassword: {
                required: true,
                minlength: 6
            },
            addconfirm_password: {
                required: true,
                minlength: 6,
                equalTo: "#addpassword"
            },
            addrol:"required"
        }
    });



     $('#formEdit_users').validate({
            errorPlacement: function errorPlacement(error, element) {
            element.after(error);
        },
        rules: {
            editname: "required",
            editemail:{
                required:true,
                email:true
            },
            editpass: {
                required: true,
                minlength: 6
            },
            editconfirmpass: {
                required: true,
                minlength: 6,
                equalTo: "#editpass"
            },
            editrol:"required"
        }
    });


    $('input[name="resetPass"]').on('change', function() {
    $('#passArea').toggle($(this).val() === 'Y');
});



    $('#ModalAddUsers').on('hidden.bs.modal', function () {
    $('#formUserAdd')[0].reset();
    var validator = $("#formUserAdd").validate();
validator.resetForm();
    $("#msjresusersAdd").html('')
})

$('#EditUserModal').on('hidden.bs.modal', function () {
    $('#formEdit_users')[0].reset();
    var validator = $("#formEdit_users").validate();
validator.resetForm();
    $("#updateResult").html('')
})

$("#DeleteUserModel").on('hidden.bs.modal',function(){
    $("#idusersDelete").val('')
        $("#nameDeleteUser").html('');
})
$("#clean").on('click', function() {
			$(".grid-filter").val('');
			myArray = camposValue();
			var camposAscDesc = '';
			var example_length = 10;
			//$(".grid-filter").change();
			load(1, myArray, example_length, camposAscDesc);
		});
</script>
