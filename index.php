<?php

const GOLDEN_NEPAL = 'golden_nepal';


const GOLDEN_NEPAL_WEBSITE = 'http://goldennepal.cz/denni-menu/';
const U_BILEHO_BERANKA_WEBSITE = 'http://www.ubilehoberanka.cz/menu-dne';
const LIGHT_OF_INDIA_WEBSITE = 'http://www.lightofindia.cz/lang-cs/denni-menu';

const MONDAY = 'Pondělí';
const TUESDAY = 'Úterý';
const WEDNESDAY = 'Středa';
const THURSDAY = 'Čtvrtek';
const FRIDAY = 'Pátek';

const WEEKDAYS = [
    1 => [
      'upper' => 'Pondělí',
      'lower' => 'pondělí',
      'regex' => '(pondělí|Pondělí|pondeli|Pondeli|PONDĚLÍ|PONDELI)',
    ],
    2 => [
      'upper' => 'Úterý',
      'lower' => 'úterý',
        'regex' => '(úterý|Úterý|utery|Utery|ÚTERÝ|UTERY|Uterý)',
    ],
    3 => [
      'upper' => 'Středa',
      'lower' => 'středa',
        'regex' => '(středa|Středa)',
    ],
    4 => [
      'upper' => 'Čtvrtek',
      'lower' => 'čtvrtek',
        'regex' => '(čtvrtek|Čtvrtek)',
    ],
    5 => [
      'upper' => 'Pátek',
      'lower' => 'pátek',
        'regex' => '(Pátek|patek)',
    ]
];

function getRestaurantMeals($website, $day, $oneFoodPattern, $startPattern = '')
{

    $dayName = WEEKDAYS[$day]['regex'];

    $data = file_get_contents($website);

    $pattern = '~' . $startPattern . $dayName . $oneFoodPattern . $oneFoodPattern . $oneFoodPattern . $oneFoodPattern . $oneFoodPattern . '~i';

    $matches = [];

    preg_match($pattern, $data, $matches);

    return $matches;
}

function printMealRow($name, $description, $price)
{
    echo "<strong>" . $name . '</strong> - ' . $description . ' - ' . $price;
    echo "<br>";
}

function printRestaurant($name, $data)
{
    echo "<div class='restaurant'>" . $name . "</div>";

    if(!isset($data[1])) {
        echo "Data pro tento den nejsou k dispozici.";
    } else {
        for ($i = 2; $i < 17; $i = $i + 3) {
            printMealRow($data[$i], $data[$i + 1], $data[$i + 2]);
        }
    }
}


echo "

<style>
    body {
        font-family: 'Open Sans', Arial, sans-serif;
    }

    a {
        text-decoration:none;
        color:black;
        font-weight: bold;
        display: inline-block;
        padding:5px;
    }
    
    a:hover {
        color: grey;
        cursor: pointer;
    }
    
    .selected {
        text-decoration: underline;
    }
    
    .restaurant {
        font-size: 120%;
        font-weight: bold;
        padding: 20px 0px 10px 0px;
    }
    
    .restaurantsContainer {
        padding-left: 5px;
    }
    
    .footer {
        position: absolute;
        bottom: 10px;
        left: 5px;
    }


</style>



";



$now = time();

$day = date('N', $now);

if(isset($_GET['day']) && in_array($_GET['day'], [1,2,3,4,5])) {
    $day = $_GET['day'];
}

foreach (WEEKDAYS as $key => $weekday) {
    if($day == $key) {
        echo "<a href='?day=$key' class='selected'>" . $weekday['upper'] . "</a>";
    } else {
        echo "<a href='?day=$key'>" . $weekday['upper'] . "</a>";
    }
}
echo "<br>";
echo "<br>";
echo "<div class='restaurantsContainer'>";
$matches = getRestaurantMeals(GOLDEN_NEPAL_WEBSITE, $day, '[\s\S]*?item_title">([^<]+)[\s\S]*?desc__content">([^<]+)[\s\S]*?item-price">([^<]+)', '.*?menu-list__title">');
printRestaurant('Golden Nepal', $matches);

$matches = getRestaurantMeals(U_BILEHO_BERANKA_WEBSITE, $day, '[\s\S]*?<div class="mc-item">[\s\S]+?mc-text">([^(]+?)\s(\([\d,\s]+\))[\s\S]+?mc-price">([\d\s\S^<]*?)<\/span>[\s\S]*?<\/div>', '<h2>');
printRestaurant('U Bílého Beránka', $matches);

$matches = getRestaurantMeals(LIGHT_OF_INDIA_WEBSITE, $day, '[\s\S]*?<br>\S*\s*([\s\S]*?)\s*?(\([\s\S]*?)\s(\d+\s*?Kč)', '<H2>');
printRestaurant('Light of India', $matches);

echo "</div>";

echo "<div class='footer'>Vytvořil Tíman. Bugy a požadavky na nové restaurace hlaste na timan@centrum.cz</div>";




