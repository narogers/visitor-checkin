<?php
/**
 * The mySQL connection parameters have been sanitized. Before this will run
 * the following line will need to configured to connect to the right
 * database. NEVER commit passwords and/or sensitive information like this
 * into version control even if it is private
 */
$db = new mysqli(<server>, <user>, <password>, <database>);
if ($db->connect_errno) {
       die(json_encode(array("error"=>$db->connect_error)));
}

if ($_POST['patronType']) {

    $stmt = $db->prepare("insert into patron_registrations (regJSON) values (?)");

    $stmt->bind_param("s",$json);
    $json = json_encode($_POST);
    $stmt->execute();

    $err = $stmt->errno;
    $stmt->close();
    $db->close();
    if ($err) {
        die(json_encode(array("error"=>$err)));
    } else {
        die(json_encode("success"));
    }
} else {
    $db->close();
    die(json_encode(array("error"=>"Invalid form data")));
}

?>
