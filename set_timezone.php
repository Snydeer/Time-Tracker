<?php
$timezoneOffsetMinutes = isset($_GET['timezone_offset']) ? intval($_GET['timezone_offset']) : 0;

$timezoneOffsetHours = $timezoneOffsetMinutes / 60;

$timezoneIdentifier = timezone_name_from_abbr('', ($timezoneOffsetHours * 60), false);

date_default_timezone_set($timezoneIdentifier);
?>