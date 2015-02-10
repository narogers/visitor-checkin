<?php
/**
 * Created by PhpStorm.
 * User: whannah
 * Date: 3/4/14
 * Time: 11:31 AM
 */
/**
 * The mySQL connection parameters have been sanitized. Before this will run
 * the following line will need to configured to connect to the right
 * database. NEVER commit passwords and/or sensitive information like this
 * into version control even if it is private
 */
$db = new mysqli(<server>, <user>, <password>, <database>);
$stmt = $db->prepare("select c.patronName, c.patronType, c.dateLog, c2.total from checkins c
inner join (select patronName, count(checkinID) as total from checkins where dateLog >= ? and dateLog <= ?
group by patronName) c2 on c.patronName = c2.patronName
where c.dateLog >= ? and c.dateLog <= ? order by c.dateLog desc");

$dateFrom = $dateFrom2 = date("Y-m",strtotime("last month"))."-01";
$dateTo = $dateTo2 = date("Y-m-d 23:59:59",(strtotime(date("Y-m-01"))-86400));

$stmt->bind_param("ssss",$dateFrom,$dateTo,$dateFrom2,$dateTo2);

$stmt->execute();
$stmt->bind_result($name,$type,$date,$checkins);
$total = 0;
?>
<!doctype html>
<html>
<head>
    <title>Ingalls Library Patron Check-In</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.css" />
    <link rel="stylesheet" href="includes/style.css" />
    <!--[if lt IE 9]><script type="text/javascript" src="includes/flashcanvas.js"></script><![endif]-->
    <script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
    <script src="includes/helpers.js?v=38"></script>
    <style type="text/css">.ui-li-desc{ padding-top: 5px; }</style>
    <script type="text/javascript">
        pageTimeout = setTimeout( function() {
            window.location.href = "http://library.clevelandart.org/checkin/adminReport.php";
        }, 300000); // 5 minutes
    </script>
</head>
<body>
<div data-role="page" id="checkinsPastMonth" data-theme="d">
    <div data-role="header" data-theme="b" data-position="fixed">
        <!--<a data-icon="check" href="#checkIn" data-theme="b">Check-In</a>-->
        <h1>Ingalls Library Patron Check-in</h1>
        <div class="ui-grid-d">
            <div class="ui-block-a"><a data-role="button" href="#registrations">NEW REGISTRATIONS</a></div>
            <div class="ui-block-b"><a data-role="button" href="#checkinsToday">CHECK-INS TODAY</a></div>
            <div class="ui-block-c"><a data-role="button" href="#checkinsPastMonth">Check-ins Last Full Calendar Month</a></div>
            <div class="ui-block-d"><a data-role="button" href="#checkinsPastWeek">Check-ins Last 7 Days</a></div>
            <div class="ui-block-e"><a data-role="button" href="#checkinsTotal">All Check-ins</a></div>
        </div>
    </div>
    <div role="main" class="ui-content">
    <?php
    while($row = $stmt->fetch()) {
        $total++;
        $rows[$type][] = array($name,$type,$date,$checkins);
    }
    print "<h3>".$total." check-ins from: ".date("F j, Y",strtotime($dateFrom))." - ";
    print date("F j, Y",strtotime($dateTo))."</h3>";
    ?>
        <ul data-role="listview" data-filter="true">
        <?php foreach($rows as $group => $row){
            print '<li data-role="list-divider">'.$group.'</li>';
            foreach ($row as $r) {
                print "<li>";
                print $r[0]." (".$r[1].", ".$r[3]." total checkins)<p>".date("F j, Y h:i a",strtotime($r[2]))."</p>";
                print "</li>";
            }
        } ?>
        </ul>
        </div>
    <?php include("partials/footer.html"); ?>
</div>
<div data-role="page" id="checkinsPastWeek" data-theme="d">
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
    <div role="main" class="ui-content">

        <?php
        $total = 0;
        $dateFrom = $dateFrom2 = date("Y-m-d",strtotime("-7 days"));
        $dateTo = $dateTo2 = date("Y-m-d 23:59:59",strtotime("yesterday"));
        $stmt->execute();
        unset($rows);
        while($row = $stmt->fetch()) {
            $total++;
            $rows[$type][] = array($name,$type,$date,$checkins);
        }
        print "<h3>".$total." check-ins from: ".date("F j, Y",strtotime($dateFrom))." - ";
        print date("F j, Y",strtotime($dateTo))."</h3>";
        ?>
        <ul data-role="listview" data-filter="true">
            <?php foreach($rows as $group => $row){
                print '<li data-role="list-divider">'.$group.'</li>';
                foreach ($row as $r) {
                    print "<li>";
                    print $r[0]." (".$r[1].", ".$r[3]." total checkins)<p>".date("F j, Y h:i a",strtotime($r[2]))."</p>";
                    print "</li>";
                }
            } ?>
        </ul>
    </div>
    <?php include("partials/footer.html"); ?>
</div>
<div data-role="page" id="checkinsToday" data-theme="d">
    <div data-role="header" data-theme="b" data-position="fixed">
        <h1>Ingalls Library Patron Check-in Kiosk</h1>
        <div class="ui-grid-d">
            <div class="ui-block-a"><a data-role="button" href="#registrations">NEW REGISTRATIONS</a></div>
            <div class="ui-block-b"><a data-role="button" href="#checkinsToday">CHECK-INS TODAY</a></div>
            <div class="ui-block-c"><a data-role="button" href="#checkinsPastMonth">Check-ins Last Full Calendar Month</a></div>
            <div class="ui-block-d"><a data-role="button" href="#checkinsPastWeek">Check-ins Last 7 Days</a></div>
            <div class="ui-block-e"><a data-role="button" href="#checkinsTotal">All Check-ins</a></div>
        </div>
    </div>
    <div role="main" class="ui-content">

        <?php
        $total = 0;
        $dateFrom = $dateFrom2 = date("Y-m-d 00:00:00");
        $dateTo = $dateTo2 = date("Y-m-d 23:59:59");
        $stmt->execute();
        unset($rows);
        while($row = $stmt->fetch()) {
            $total++;
            $rows[$type][] = array($name,$type,$date,$checkins);
        }
        print "<h3>".$total." check-ins today</h3>";
        ?>
        <ul data-role="listview" data-filter="true">
            <?php foreach($rows as $group => $row){
                print '<li data-role="list-divider">'.$group.'</li>';
                foreach ($row as $r) {
                    print "<li>";
                    print $r[0]." (".$r[1].", ".$r[3]." total checkins)<p>".date("F j, Y h:i a",strtotime($r[2]))."</p>";
                    print "</li>";
                }
            } ?>
        </ul>
    </div>
    <?php include("partials/footer.html"); ?>
</div>
<div data-role="page" id="checkinsTotal" data-theme="d">
    <div data-role="header" data-theme="b" data-position="fixed">
        <h1>Ingalls Library Patron Check-in Kiosk</h1>
        <div class="ui-grid-d">
            <div class="ui-block-a"><a data-role="button" href="#registrations">NEW REGISTRATIONS</a></div>
            <div class="ui-block-b"><a data-role="button" href="#checkinsToday">CHECK-INS TODAY</a></div>
            <div class="ui-block-c"><a data-role="button" href="#checkinsPastMonth">Check-ins Last Full Calendar Month</a></div>
            <div class="ui-block-d"><a data-role="button" href="#checkinsPastWeek">Check-ins Last 7 Days</a></div>
            <div class="ui-block-e"><a data-role="button" href="#checkinsTotal">All Check-ins</a></div>
        </div>
    </div>
    <div role="main" class="ui-content">

        <?php
        $total = 0;
        $dateFrom = $dateFrom2 = date("1970-01-01 00:00:00");
        $dateTo = $dateTo2 = date("Y-m-d 23:59:59");
        $stmt->execute();
        unset($rows);
        while($row = $stmt->fetch()) {
            $total++;
            $rows[$type][] = array($name,$type,$date,$checkins);
        }
        print "<h3>".$total." check-ins (total)</h3>";
        ?>
        <ul data-role="listview" data-filter="true">
            <?php foreach($rows as $group => $row){
                print '<li data-role="list-divider">'.$group.'</li>';
                foreach ($row as $r) {
                    print "<li>";
                    print $r[0]." (".$r[1].", ".$r[3]." total checkins)<p>".date("F j, Y h:i a",strtotime($r[2]))."</p>";
                    print "</li>";
                }
            } ?>
        </ul>
    </div>
    <?php include("partials/footer.html"); ?>
</div>
<div data-role="page" id="registrations" data-theme="d">
    <div data-role="header" data-theme="b" data-position="fixed">
        <h1>Ingalls Library Patron Check-in Kiosk</h1>
        <div class="ui-grid-d">
            <div class="ui-block-a"><a data-role="button" href="#registrations">NEW REGISTRATIONS</a></div>
            <div class="ui-block-b"><a data-role="button" href="#checkinsToday">CHECK-INS TODAY</a></div>
            <div class="ui-block-c"><a data-role="button" href="#checkinsPastMonth">Check-ins Last Full Calendar Month</a></div>
            <div class="ui-block-d"><a data-role="button" href="#checkinsPastWeek">Check-ins Last 7 Days</a></div>
            <div class="ui-block-e"><a data-role="button" href="#checkinsTotal">All Check-ins</a></div>
        </div>
    </div>
    <div role="main" class="ui-content">

        <?php
        $total = 0;
        $stmt->close();
        $stmt = $db->prepare("select regID, regJSON, dateAdded from patron_registrations order by dateAdded desc");
        $stmt->execute();
        $stmt->bind_result($regID,$json,$dateAdded);
        unset($rows);

        while($row = $stmt->fetch()) {
            $total++;
            $json = json_decode($json);
            $rows[$json->patronType][] = array($json->lname.", ".$json->fname, $json->patronType, $dateAdded,$regID);
        }
        print "<h3>Pending New User Registrations</h3>";
        ?>
        <ul data-role="listview" data-filter="true">
            <?php foreach($rows as $group => $row){
                print '<li data-role="list-divider">'.$group.'</li>';
                foreach ($row as $r){
                    print "<li><a href=/checkin/newReg.php?id=".$r[3].">";
                    print $r[0]."<p>".date("F j, Y h:i a",strtotime($r[2]))."</p>";
                    print "</a></li>";
                }
            } ?>
        </ul>
    </div>
    <?php include("partials/footer.html"); ?>
</div>
</body>
</html>
<?php
$stmt->close();
$db->close();
?>
