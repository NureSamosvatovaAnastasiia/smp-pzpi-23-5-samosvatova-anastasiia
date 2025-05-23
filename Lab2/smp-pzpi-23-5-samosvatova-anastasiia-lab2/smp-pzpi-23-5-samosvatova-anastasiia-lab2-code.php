#!/bin/php
<?php

$products = [
    1 => ['name' => 'Морозиво "Гран-прі"', 'price' => 25],
    2 => ['name' => 'Лаваш', 'price' => 70],
    3 => ['name' => 'Сир КОМО', 'price' => 21],
    4 => ['name' => 'Сметана 15%', 'price' => 45],
    5 => ['name' => 'Кефір 3%', 'price' => 19],
    6 => ['name' => 'Вода слабогазована 2л', 'price' => 28],
    7 => ['name' => 'Авокадо', 'price' => 114],
];

$basket = [];
$profile = ['name' => null, 'age' => null];

function printMenu() {
    echo "#######################################\n";
    echo "#   ПРОДОВОЛЬЧИЙ МАГАЗИН \"АНАСТАСІЯ\"  #\n";
    echo "#######################################\n";
    echo "1 Вибрати товари\n";
    echo "2 Отримати підсумковий рахунок\n";
    echo "3 Налаштувати свій профіль\n";
    echo "0 Вийти з програми\n";
    echo "Введіть команду: ";
}

function printProducts($products) {
    echo "№  НАЗВА                             ЦІНА\n";
    echo "   --------------------------------------\n";
    foreach ($products as $key => $product) {
        printf("%-2d %-30s %10d\n", $key, $product['name'], $product['price']);
    }
    echo "   --------------------------------------\n";
    echo "0  ПОВЕРНУТИСЯ\n";
}



function clearScreen() {
    echo "\n";
}

while (true) {
    printMenu();
    $choice = trim(fgets(STDIN));

    switch ($choice) {
        case '1':
            while (true) {
                clearScreen();
                printProducts($products);
                echo "Виберіть товар: ";
                $item = trim(fgets(STDIN));
                if ($item == '0') break;
                if (!isset($products[$item])) {
                    echo "ПОМИЛКА! ВКАЗАНО НЕПРАВИЛЬНИЙ НОМЕР ТОВАРУ\n";
                    continue;
                }

                echo "Вибрано: " . $products[$item]['name'] . "\n";
                echo "Введіть кількість, штук: ";
                $qty = trim(fgets(STDIN));
                if (!is_numeric($qty) || $qty < 0 || $qty > 100) {
                    echo "ПОМИЛКА! Введіть коректну кількість (1-100)\n";
                    continue;
                }

                if ($qty == 0) {
                    unset($basket[$item]);
                    echo "ВИДАЛЯЮ З КОШИКА\n";
                    if (empty($basket)) echo "КОШИК ПОРОЖНІЙ\n";
                } else {
                    $basket[$item] = $qty;
                }

                echo "У КОШИКУ:\nНАЗВА        КІЛЬКІСТЬ\n";
                foreach ($basket as $id => $q) {
                    echo $products[$id]['name'] . "  " . $q . "\n";
                }
            }
            break;

        case '2':
            clearScreen();
            echo "№  НАЗВА                    ЦІНА  КІЛЬКІСТЬ  ВАРТІСТЬ\n";
            $total = 0;
            $num = 1;
            foreach ($basket as $id => $qty) {
                $name = $products[$id]['name'];
                $price = $products[$id]['price'];
                $cost = $qty * $price;
                printf("%-2d %-30s %5d %9d %8d\n", $num++, $name, $price, $qty, $cost);
                $total += $cost;
            }
            echo "РАЗОМ ДО CПЛАТИ: $total\n";
            break;

        case '3':
            echo "Ваше імʼя: ";
            $name = trim(fgets(STDIN));
            if (!preg_match('/[a-zA-Zа-яА-ЯіІїЇєЄ]/u', $name)) {
                echo "ПОМИЛКА! Імʼя повинно містити хоча б одну літеру.\n";
                break;
            }
            echo "Ваш вік: ";
            $age = trim(fgets(STDIN));
            if (!is_numeric($age) || $age < 7 || $age > 150) {
                echo "ПОМИЛКА! Вік має бути від 7 до 150.\n";
                break;
            }
            $profile['name'] = $name;
            $profile['age'] = $age;
            echo "Профіль оновлено.\n";
            break;

        case '0':
            echo "Дякуємо за візит до магазину \"АНАСТАСІЯ\"!\n";
            exit(0);

        default:
            echo "ПОМИЛКА! Введіть правильну команду\n";
    }
}
?>
