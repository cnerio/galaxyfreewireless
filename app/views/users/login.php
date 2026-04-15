<?php require APPROOT . '/views/inc/header.php'; ?>
<section class="py-5 mt-5">
    <div class="container py-5">
        <div class="row">
    <div class="col-md-4 mx-auto">
        <div class="card bg-light mt-5">
            <!-- <div class="card-header card-text">
                
                <h2 class="card-text">User login</h2>
            <p class="card-text"><small>Please enter your username and password</small></p>
            </div> -->
        
            <div class="card-body pt-4">
                <form method="post" action="<?php echo URLROOT ;?>/users/login">
                    <div class="form-group">
                        <label for="email">Email<sub>*</sub></label>
                        <input type="email" name="email" class="form-control form-control-lg <?php echo (!empty($data['email_err'])) ? 'is-invalid' : '' ;?>" value="<?php echo $data['email'] ;?>">
                        <span class="invalid-feedback"><?php echo $data['email_err'] ;?> </span>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Password<sub>*</sub></label>
                        <input type="password" name="password" class="form-control form-control-lg <?php echo (!empty($data['password_err'])) ? 'is-invalid' : '' ;?>" value="<?php echo $data['password'] ;?>">
                        <span class="invalid-feedback"><?php echo $data['password_err'] ;?> </span>
                    </div>

                    <div class="form-group">
                        <div class="row">
                            <div class="col">
                                <input type="submit" class="btn btn-primary shadow pull-right" value="Login">
                                <?php flash('register_success'); ?>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    </div>
</section>
<?php //require APPROOT . '/views/inc/footer.php'; ?>