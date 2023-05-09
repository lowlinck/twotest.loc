<?php
    $start = microtime(true);

    // Подключение к базе данных
    $dsn = "mysql:host=localhost;dbname=twotest";
    $username = "root";
    $password = "";
    
    try {
        $pdo = new PDO($dsn, $username, $password);
    } catch (PDOException $e) {
        die("Ошибка подключения к базе данных: " . $e->getMessage());
    }
    
    // Запрос на получение всех категорий и их идентификаторов
    $sql = "SELECT categories_id, categories_name, parent_id FROM categories ORDER BY parent_id ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Построение дерева категорий
    $tree = array();
    foreach ($categories as $category) {
        $id = $category['categories_id'];
        $parent = $category['parent_id'];
        $name = $category['categories_name'];
        $node = array('name' => $name);
        if ($parent == 0) {
            $tree[$id] = $node;
        } else {
            $tree[$parent]['children'][$id] = $node;
        }
    }
    
    echo '<pre>';
    print_r($tree);
    echo '</pre>';
    
    $end = microtime(true);
    $time = $end - $start;
    echo "Время выполнения скрипта: $time секунд";
    