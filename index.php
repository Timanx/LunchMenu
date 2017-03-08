<?php

const GOLDEN_NEPAL = 'golden_nepal';


const GOLDEN_NEPAL_WEBSITE = 'http://goldennepal.cz/denni-menu/';


const MONDAY = 'Pondělí';
const TUESDAY = 'Úterý';
const WEDNESDAY = 'Středa';
const THURSDAY = 'Čtvrtek';
const FRIDAY = 'Pátek';


$now = time();

$day = date('N', $now);

$dayName = MONDAY;

switch($day) {
    case 1: $dayName = MONDAY; break;
    case 2: $dayName = TUESDAY; break;
    case 3: $dayName = WEDNESDAY; break;
    case 4: $dayName = THURSDAY; break;
    case 5: $dayName = FRIDAY; break;
    default: break;
}

$data = file_get_contents(GOLDEN_NEPAL_WEBSITE);

$start = strpos($data, 'pixcode--grid');
$end = strpos($data, 'pixcode--separator');

$data = substr($data, $start, $end);

$oneFoodPattern = '[\s\S]*?item_title">([^<]+)[\s\S]*?desc__content">([^<]+)[\s\S]*?item-price">([^<]+)';

//$pattern = '~menu-list__title">([^ <]+)[\s\S]*?item_title">([^<]+)~';
$pattern = '~.*?menu-list__title">' . $dayName . $oneFoodPattern . $oneFoodPattern . $oneFoodPattern . $oneFoodPattern . $oneFoodPattern . '~';
//$pattern = '~menu-list__title">([^<]+)[^]*?item_title">([^<]+)~im';
//$pattern = '~pix~m';

$matches = [];

preg_match($pattern, $data, $matches);

echo "Golden Nepal " . $dayName;
echo "<br>";

for($i = 1; $i < 16; $i = $i + 3) {
    echo $matches[$i] . ' - ' . $matches[$i + 1] . ' - ' . $matches[$i + 2];
    echo "<br>";
}

$a = 1;

