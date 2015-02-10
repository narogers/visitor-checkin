<?php
/**
 * Created by PhpStorm.
 * User: whannah
 * Date: 3/5/14
 * Time: 3:02 PM
 */
/**
 * The mySQL connection parameters have been sanitized. Before this will run
 * the following line will need to configured to connect to the right
 * database. NEVER commit passwords and/or sensitive information like this
 * into version control even if it is private
 */
$db = new mysqli(<server>, <user>, <password>, <database>);
$stmt = $db->prepare("select regJSON, dateAdded from patron_registrations where regID = ?");
$stmt->bind_param("i",$id);
$id = (int)$_GET['id'];

$stmt->execute();
$stmt->bind_result($json, $dateAdded);
?>
<!doctype html>
<html>
<head>
    <title>Ingalls Library Patron Check-in Kiosk</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" />
    <link rel="stylesheet" href="includes/style.css" />
    <!--[if lt IE 9]><script type="text/javascript" src="includes/flashcanvas.js"></script><![endif]-->
    <script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
    <script src="includes/helpers.js?v=38"></script>
    <style type="text/css">.ui-li-desc{ padding-top: 5px; }</style>
</head>
<body>
<div data-role="page" id="home" data-theme="d">
    <div data-role="header" data-theme="b" data-position="fixed">
        <!--<a data-icon="check" href="#checkIn" data-theme="b">Check-In</a>-->
        <h1>Ingalls Library Patron Check-in Kiosk</h1>
        <div class="ui-grid-d">
            <div class="ui-block-a"><a data-role="button" href="#registrations">NEW REGISTRATIONS</a></div>
            <div class="ui-block-b"><a data-role="button" href="#checkinsToday">CHECK-INS TODAY</a></div>
            <div class="ui-block-c"><a data-role="button" href="#checkinsPastMonth">Check-ins Last Full Calendar Month</a></div>
            <div class="ui-block-d"><a data-role="button" href="#checkinsPastWeek">Check-ins Last 7 Days</a></div>
            <div class="ui-block-e"><a data-role="button" href="#checkinsTotal">All Check-ins</a></div>
        </div>
    </div>
    <div role="main" class="ui-content"><pre><?php
            while($row = $stmt->fetch()) {
                $json = json_decode($json, JSON_PRETTY_PRINT);
                foreach ($json as $key => $value) {
                    if ($key != 'image/png;base64') {
                        print "<strong>".str_pad($key,20," ",STR_PAD_RIGHT);
                        print "</strong>: ".$value."<br />";
                    } else {
                        $sig = '<img src="data:image/png;base64,'.$value.'"/>';
                    }
                }
                print $sig;
            }
        ?>
    </pre></div>
    <?php include("partials/footer.html"); ?>
</div>
    </body>
    </html>
<?php
    $stmt->close();
$db->close();
?>
