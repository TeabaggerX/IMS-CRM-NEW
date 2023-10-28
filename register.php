<div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                <div class="col-lg-7">
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                        </div>
                        <form class="user" id="regester_form">
                            <div class="form-group">
                                <input type="email" class="form-control form-control-user" id="username" placeholder="User Name" name="username">
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="text" class="form-control form-control-user" id="exampleFirstName" placeholder="First Name" name="first_name">
                                </div>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control form-control-user" id="exampleLastName" placeholder="Last Name" name="last_name">
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control form-control-user" id="exampleInputEmail" placeholder="Email Address" name="email">
                            </div>
                            <div class="form-group row">
                                <div class="col-sm-6 mb-3 mb-sm-0">
                                    <input type="password" class="form-control form-control-user" id="password" placeholder="Password" name="password">
                                </div>
                                <div class="col-sm-6">
                                    <input type="password" class="form-control form-control-user" id="repassword" placeholder="Repeat Password" name="repassword">
                                </div>
                            </div>
                            <div class="btn btn-primary btn-user btn-block" id="btn-user">
                                Register Account
                            </div>
                        </form>
                        <hr>
                        <div class="text-center">
                            <a class="small" href="index.php?module=&page=login">Already have an account? Login!</a>
                        </div>
                    </div>
                    <div id="msg">
                        <div class="card bg-danger text-white shadow">
                            <div class="card-body">
                                Danger
                                <div class="text-white-50 small">#e74a3b</div>
                            </div>
                        </div>
                        <div class="card bg-success text-white shadow">
                            <div class="card-body">
                                Danger
                                <div class="text-white-50 small">#e74a3b</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    jQuery('.bg-danger').hide();
    jQuery('.bg-success').hide();
    jQuery("#btn-user").click(function(){
        jQuery('.bg-danger').hide();
        var pw = jQuery('#password').val();
        var repw = jQuery('#repassword').val();
        if(pw != repw){
            jQuery('.bg-danger .card-body').html("Passwords do not match.");
            jQuery('.bg-danger').show();
        } else {
            jQuery.post('/ajax.php',{'action':'register','form':jQuery('#regester_form').serialize()}).done(function (res) {
                    jQuery('.bg-success .card-body').html(res);
                    jQuery('.bg-success').show();
            });
        }

        });
</script>