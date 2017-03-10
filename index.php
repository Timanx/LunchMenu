<?php

const GOLDEN_NEPAL = 'golden_nepal';


const GOLDEN_NEPAL_WEBSITE = 'http://goldennepal.cz/denni-menu/';
const U_BILEHO_BERANKA_WEBSITE = 'http://www.ubilehoberanka.cz/menu-dne';

const MONDAY = 'Pondělí';
const TUESDAY = 'Úterý';
const WEDNESDAY = 'Středa';
const THURSDAY = 'Čtvrtek';
const FRIDAY = 'Pátek';

const WEEKDAYS = [MONDAY, TUESDAY, WEDNESDAY, THURSDAY, FRIDAY];

function getRestaurantMeals($website, $dayName, $oneFoodPattern, $startPattern = '')
{
    $data = file_get_contents($website);

    $pattern = '~' . $startPattern . $dayName . $oneFoodPattern . $oneFoodPattern . $oneFoodPattern . $oneFoodPattern . $oneFoodPattern . '~i';

    $matches = [];

    preg_match($pattern, $data, $matches);

    return $matches;
}

function printMealRow($name, $description, $price)
{
    echo $name . ' - ' . $description . ' - ' . $price;
    echo "<br>";
}

function printRestaurant($name, $data)
{
    echo $name;
    echo "<br>";

    for($i = 1; $i < 16; $i = $i + 3) {
        printMealRow($data[$i], $data[$i + 1], $data[$i + 2]);
    }
}


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

if(isset($_GET['day']) && in_array($_GET['day'], WEEKDAYS)) {
    $dayName = $_GET['day'];
}

foreach (WEEKDAYS as $weekday) {
    echo "<a href='?day=$weekday'>$weekday</a>";
}
echo "<br>";
echo "<br>";

$matches = getRestaurantMeals(GOLDEN_NEPAL_WEBSITE, $dayName, '[\s\S]*?item_title">([^<]+)[\s\S]*?desc__content">([^<]+)[\s\S]*?item-price">([^<]+)', '.*?menu-list__title">');
printRestaurant('Golden Nepal', $matches);

$matches = getRestaurantMeals(U_BILEHO_BERANKA_WEBSITE, $dayName, '[\s\S]*?<div class="mc-item">[\s\S]+?mc-text">([^(]+?)\s(\([\d,\s]+\))[\s\S]+?mc-price">([\d\s\S^<]*?)<\/span>[\s\S]*?<\/div>', '<h2>(\S)+.*?[\s\S]+?<\/div>');
printRestaurant('U Bílého Beránka', $matches);




