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
// Запрос на получение всех категорий
	$sql = "SELECT * FROM categories";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
	$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Функция для построения дерева категорий
	function buildCategoryTree($categories, $parent_id = 0)
	{
		$tree = array();
		foreach ($categories as $category) {
			if ($category['parent_id'] == $parent_id) {
				$id = $category['categories_id'];
				// Рекурсивный вызов функции для получения подкатегорий
				$subcategories = buildCategoryTree($categories, $id);
				// Добавление категории в дерево
				if (empty($subcategories)) {
					$tree[$id] = $id;
				} else {
					$tree[$id] = $subcategories;
				}
			}
		}
		return $tree;
	}
// Построение дерева категорий
	$result = buildCategoryTree($categories);
	echo '<pre>';
	print_r($result);
	echo '</pre>';
	$end = microtime(true); // сохраняем текущее время в переменной $end
	$time = $end - $start; // вычисляем разницу времени в секундах
	echo "Время выполнения скрипта: $time секунд";
