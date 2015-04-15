<style>
    * {
        margin: 0;
        padding: 0;
    }
    b {
        display: block;
        float: left;
        width: 1px;
        height: 25px;
    }
    b.emp {
        background-color: #fff;
    }
    b.pee {
        background-color: #aaa;
    }
    b.poo {
        background-color: #222;
    }
    b.mix {
        background-color: #777;
    }
    b.wat {
        background-color: #0f0;
    }
</style><?php

//phpinfo();

//$dbhandle = sqlite_open('./EasyLog.db');
//$query = sqlite_query($dbhandle, 'SELECT * FROM Diaper LIMIT 25');
//$result = sqlite_fetch_all($query, SQLITE_ASSOC);
//foreach ($result as $entry) {
//    print_r($entry);
//}

// loop through every hour from the first entry to now, and display it as an entry.
// no diaper events? mark as 'empty'
// Single event? pee, poo, or mix, label as such
// Multiple, and all the same, mark as same
// Multiple, and not all the same, mark as mixed


date_default_timezone_set('US/Pacific');


$db = new SQLite3('./EasyLog.db');

$sql = "SELECT min(Time) as min FROM Diaper";
$result = $db->query($sql)->fetchArray(SQLITE3_ASSOC);
$time = round($result['min']);
$days = 0;
while ($time < time()) {
    $nextTime = $time + 3600;
    $sql = "SELECT Status FROM Diaper WHERE Time BETWEEN $time AND $nextTime GROUP BY Status";
    $result = $db->query($sql);

    $hasPee = $hasPoo = $hasMix = false;
    while($res = $result->fetchArray(SQLITE3_ASSOC)) {
        if ($res['Status'] == 0) { $hasPee = true; }
        if ($res['Status'] == 1) { $hasPoo = true; }
        if ($res['Status'] == 2) { $hasMix = true; }
    }

    if (!$hasPee && !$hasPoo && !$hasMix) {
        $c = 'emp';
    }
    elseif (($hasPee && $hasPoo) || $hasMix) {
        $c = 'mix';
    }
    elseif ($hasPee && !$hasPoo && !$hasMix) {
        $c = 'pee';
    }
    elseif (!$hasPee && $hasPoo && !$hasMix) {
        $c = 'poo';
    }
    else {
        $c = 'wat';
    }

    echo "<b class='$c'></b>\n";

    $time = $nextTime;
    $days++;
}

echo "hours: $days, days " . ($days/24);
