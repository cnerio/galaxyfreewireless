<?php
require APPROOT . '/views/inc/header.php';
require APPROOT . '/views/inc/navbarAdmin.php';
$id_user = $_SESSION['user_id'];
?>
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
                        <input type="hidden" name="acttion" value="deleteuserUp">
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
