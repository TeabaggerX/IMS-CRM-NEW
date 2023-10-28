<?php 
$_SESSION["loggedin"] = ''; 
$catNumber = rand(1,5);
?>
<div class="container">
    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-10 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block"><img src="img/cat0<?=$catNumber?>.jpg"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                </div>
                                <form class="user" action="/index.php?module=newsletter&page=app" method="post">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user" id="userName" name="userName" aria-describedby="emailHelp" placeholder="Enter User Name">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user" name="password" id="exampleInputPassword" placeholder="Password">
                                    </div>
                                    <?php if($showMsg != ''){ ?>
                                        <div class="form-group">
                                            <a href="#" class="btn btn-warning btn-icon-split">
                                            <span class="icon text-white-50">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </span>
                                            <span class="text"><?=$showMsg?></span>
                                        </a>
                                        </div>
                                    <?php } ?>
                                    <input type="submit" name="Login" value="Login" class="btn btn-primary btn-user btn-block">
                                    <input type="hidden" name="login" value="yes">
                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="index.php?module=&page=register">Do not have an account? Register for one!</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>