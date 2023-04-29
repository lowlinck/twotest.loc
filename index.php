<?php

    // Подключение к базе данных
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "twotest";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Проверка подключения
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Запрос категорий из базы данных
    $sql = "SELECT categories_id, parent_id FROM categories";
    $result = $conn->query($sql);

    // Преобразование результата запроса в массив
    $categories = array();
    while ($row = $result->fetch_assoc()) {
        $categories[$row['categories_id']] = $row;
    }

    // Функция для построения дерева категорий
    function buildTree(&$categories, $parentId = 0) {
        $tree = array();

        foreach ($categories as $categoryId => $category) {
            if ($category['parent_id'] == $parentId) {
                // Удаляем элемент из массива, чтобы не обрабатывать его повторно
                unset($categories[$categoryId]);

                $subcategories = buildTree($categories, $categoryId);

                // Если есть подкатегории, добавляем их в текущую категорию
                if ($subcategories) {
                    $category['subcategories'] = $subcategories;
                }

                // Добавляем категорию в дерево
                $tree[$categoryId] = $category;
            }
        }

        return $tree;
    }

    // Построение дерева категорий
    $tree = buildTree($categories);

    // Вывод результата
    echo '<pre>';
    print_r($tree);
    echo '</pre>';

    // Закрытие соединения с базой данных
    $conn->close();

?>
