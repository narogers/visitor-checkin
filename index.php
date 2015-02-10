<?php
/**
 * Created by PhpStorm.
 * User: whannah
 * Date: 1/27/14
 * Time: 11:47 AM
 */
  
?>
<!doctype html>
<html>
<head>
    <title>Ingalls Library Patron Check-In Kiosk</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" />
    <link rel="stylesheet" href="includes/style.css?v=4" />
    <!--[if lt IE 9]><script type="text/javascript" src="includes/flashcanvas.js"></script><![endif]-->
    <script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
    <script src="includes/jSignature.min.js"></script>
    <!-- <script src="includes/helpers.js?v=<?php echo date('YmdHis'); ?>"></script> --> <!-- uncomment to uncache -->
	<script src="includes/helpers.js?v=42"></script>
    <script src="includes/jquery.plugin.min.js"></script>
    <script src="includes/jquery.countdown.min.js"></script>
    <?php
    // Process name and ID based checkins
    if (!empty($_POST['nameOrID'])) {
        $result = file_get_contents("http://library.clevelandart.org/checkin/partials/checkin.php?nameOrID=".urlencode($_POST['nameOrID'])."&patronType=".urlencode($_POST['patronType']));
    }
    ?>
</head>
<body>
    <div data-role="page" id="home" data-theme="d">
        <div>&nbsp;</div>
        <?php include("partials/home.php"); ?>
        <div>&nbsp;</div>
        <div>&nbsp;</div>
        <div class="ui-grid-b">
            <div class="ui-block-a" style="width:12.5%">&nbsp;</div>
            <div class="ui-block-b" style="width:75%"><div style="text-align: center;" id="confMsg">&nbsp;</div></div>
            <div class="ui-block-c" style="width:12.5%">&nbsp;</div>
        </div>
    </div>
    <div data-role="page" id="register" data-theme="d">
        <?php include("partials/register.php"); ?>
    </div>
    <div data-role="page" id="checkinChoice" data-theme="d">
        <?php include("partials/checkinChoice.php"); ?>
    </div>
    <?php foreach (array("reg2","reg3") as $v) { ?>
    <div data-role="page" id="<?php echo $v ?>" data-theme="d">
        <?php include("partials/".$v.".php"); ?>
    </div>
    <?php } ?>
</body>
</html>