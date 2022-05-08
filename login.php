<?
    include('_coresetting.php');
    include('include/_conn.php');
    include('_corefunction.php');    

    if (isset($_POST['username']) && $_POST['password']){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $tmp = array();
        $collection = $db->tbl_user;
        $record = $collection->findOne(array('username'=> $username, 'password'=> $password, 'isvalid'=>1));
        
        if ($record){
            $redirect_url = "";
            $userArr = iterator_to_array($record);

            $_SESSION["login_tbl_user_pkey"] = $userArr['userID'];
            $_SESSION['login_username'] = $userArr['displayName'];
            $_SESSION['user_type'] = $userArr['user_info']['type'];

            if (isset($userArr['user_info']['ID']) && $userArr['user_info']['ID'].'' != ''){
                $_SESSION['user_type_id'] = $userArr['user_info']['ID'];

                $redirect_url = $userArr['user_info']['default_page']."?studentID=".$userArr['user_info']['ID'];
            }else{
                $redirect_url = $userArr['user_info']['default_page'];
            }
            
            header('Location: '.$redirect_url);
        }else{
            $loginres = true;
        }
        
    }

    ?>

    <!DOCTYPE html>
    <html dir="ltr">
    <? include('_block/_head.php'); ?>
    <head>
        <!-- toastr -->
        <link href="assetss/libs/toastr/build/toastr.min.css" rel="stylesheet">
        <script src="dist/js/corejs.js"></script>
    </head>
    <body>
        <div class="main-wrapper">
            <!-- ============================================================== -->
            <!-- Preloader - style you can find in spinners.css -->
            <!-- ============================================================== -->
        <? if (!$_DEBUG){ ?>
            <div class="preloader">
                <div class="lds-ripple">
                    <div class="lds-pos"></div>
                    <div class="lds-pos"></div>
                </div>
            </div>
        <? } ?>
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Login box.scss -->
        <!-- ============================================================== -->
        <div class="auth-wrapper d-flex no-_block justify-content-center align-items-center bg-dark">
            <div class="auth-box bg-dark border-top border-secondary">
                <div id="loginform">
                    <div class="text-center p-t-10 p-b-10">
                        <span class="db"><img width="350px" src="assets/images/logo_large.png" alt="logo" /></span>
                    </div>
                    <!-- Form -->
                    <form class="form-horizontal m-t-10" id="loginform" action="login.php" method="post">
                        <div class="row p-b-30">
                            <div class="col-12">
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-success text-white" id="basic-addon1"><i class="ti-user"></i></span>
                                    </div>
                                    <input id="username" type="text" name="username" class="form-control form-control-lg" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" required="" value="<? if($_DEBUG) echo "ringo"?>">
                                </div>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text bg-warning text-white" id="basic-addon2"><i class="ti-pencil"></i></span>
                                    </div>
                                    <input id="password" type="password" name="password" class="form-control form-control-lg" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1" required="" value="<? if($_DEBUG) echo "123123"?>">
                                </div>
                                <? if (isset($loginres) && $loginres){ ?>
                                    <div class="alert alert-danger" role="alert">
                                        Sorry, Username or Password incorrect. 
                                    </div>
                                <? } ?>
                            </div>
                        </div>
                        <div class="row border-top border-secondary">
                            <div class="col-12">
                                <div class="form-group">
                                    <div class="p-t-20">
                                        <button class="btn btn-success float-right" type="submit">Login</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>                
            </div>
        </div>
    </div>
    <script src="assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- toastr -->
    <script src="assets/libs/toastr/build/toastr.min.js"></script>
    <!-- ============================================================== -->
    <!-- This page plugin js -->
    <!-- ============================================================== -->
    <script>
        $(".preloader").fadeOut();
        $("#username").focus();

    </script>
    <!-- Show Notification -->
    <? getNotication(); ?>
</body>

</html> 