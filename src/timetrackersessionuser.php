<?php
/**
 * Load the user from the timetracker session and only make his data available.
 *
 * require this file in your config.php
 */

require_once __DIR__ . '/../src/db.php';

if (!isset($_COOKIE['PHPSESSID'])) {
    //no session id cookie
    die('Not logged into timetacker (no session id)');
}
session_start();
if(!isset($_SESSION['_sf2_attributes']['_security_main'])) {
    die('No timetracker session');
}

$pattern = '/username";s:[0-9]+:"(.*?)"/';
if (!preg_match($pattern, $_SESSION['_sf2_attributes']['_security_main'], $matches)) {
    die('No user in timetracker session');
}
;
$username = $matches[1] ?? null;

if($username===null) {
    die('No username in timetracker session');
}

$teamMembers = $db->query(
    'SELECT DISTINCT members.username
    FROM users, teams, teams_users, users AS members
    WHERE users.id = teams.lead_user_id
     AND users.username = ' . $db->quote($username) . '
     AND users.type = "PL"
     AND teams.id = teams_users.team_id
     AND teams_users.user_id = members.id
    ORDER BY members.username'
)->fetchAll();

$GLOBALS['cfg']['arAllowedUsers'] = [];
if (!empty($teamMembers)) {
    $GLOBALS['cfg']['arAllowedUsers'] = array_column($teamMembers, "username");
}
$GLOBALS['cfg']['arAllowedUsers'][] = $username;
?>
