<?php

function baseurl() {
    return sprintf( "%s://%s", 
            isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http', $_SERVER['SERVER_NAME'] );
}

function path(){
    return sprintf( "%s", $_SERVER['REQUEST_URI'] );    
}

?>


<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>StudentCircle Lettings</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/bootstrap-theme.min.css">
        <link rel="stylesheet" href="css/jquery.tagsinput.css" />
        <link rel="stylesheet" href="css/main.css">
        <script src="js/vendor/jquery-1.10.1.min.js"></script> 
        <script src="js/vendor/jquery.mobile-1.4.2.min.js"></script>
        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>

    </head>
    <body>
        <!--Header Section Wrapper Start-->

        <div class="container">
            <div class="headerSection GradientOrange">
                <div class="logo"><img src="img/logo.png" alt="" class="logo"/></div>
            </div>
        </div>

        <!--Header Section Wrapper End--> 

        <!--Middle Content Wrapper Start-->

        <div class="container" id="display">

        </div>
        <!--Middle Content Wrapper End-->  

        <script src="js/vendor/bootstrap.min.js"></script> 
        <script src="js/jquery.flexslider-min.js"></script> 
        <script src="js/jquery.tagsinput.js"></script>
        <script src="js/plugins.js"></script> 
        <script src="js/main.js"></script>
        <script>
         var BASE_URL="<?php echo baseurl(); ?>";   
         var PATH="<?php echo path(); ?>";
        </script>
        <script src="js/application.js"></script>
    </body>
</html>