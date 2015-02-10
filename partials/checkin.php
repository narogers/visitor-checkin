<?php
$passthru = false;
if (!empty($_GET['nameOrID'])) {
    $passthru = $_GET['nameOrID'];
} else {
    header("Content-type: application/json");
    extract($_POST);
}
$ret = array("error"=>-1,"message"=>"Patron account not found.");

if ($passthru || ($format == 'CODABAR' && strlen($code))) {
/**
 * The mySQL connection parameters have been sanitized. Before this will run
 * the following line will need to configured to connect to the right
 * database. NEVER commit passwords and/or sensitive information like this
 * into version control even if it is private
 */
$db = new mysqli(<server>, <user>, <password>, <database>);
    $stmt = $db->prepare("insert into checkins (expiredAccount,patronName,patronType) values (?, ?, ?)");
    $stmt->bind_param("iss",$expired,$name,$type);

    // $passthru is the handler for simple user name or member ID checkins
    if (!$passthru) {
        // Remove the boundary chars from the scanned barcode
        $barcode = substr($code,1,-1);

        // Query the X-Server
        $xbase = "http://opac.clevelandart.org/X/?library=CMA50&base=STACKS";
        $op = "op=bor-info";
        $blob = file_get_contents($xbase."&".$op."&bor_id=".$barcode);
        $p = xml_parser_create();

        $xml = simplexml_load_string($blob);

        // If an ID is found, the user
        if ($xml->z303->{'z303-id'} && $xml->z305->{'z305-expiry-date'}) {
            $expiration = strtotime($xml->z305->{'z305-expiry-date'});
            if (stristr($xml->z303->{'z303-name'},",")) {
                list($lname,$fname) = explode(",",$xml->z303->{'z303-name'},2);
                $name = trim($fname." ".$lname);
            } else {
                $name = $xml->z303->{'z303-name'};
            }

            $type = (string)$xml->z305->{'z305-bor-type'};

            if ($expiration < time()) {
                $ret = array("error"=>-2,"message"=>"Patron account expired on ".date("F j, Y",$expiration).".");
                $expired = 1;
            } else {
                $ret = array("message"=>"Welcome, ".$name."!");
                $expired = 0;
            }
            $stmt->execute();
        }
    } else {
        $expired = 0;
        $name = $passthru;
        $type = $_GET['patronType'];
        $stmt->execute();
    }

    $stmt->close();
    $db->close();
}

if (!$passthru) {
    print json_encode($ret);
} else {
    print $passthru;
}
