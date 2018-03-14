<?php
include "functions.php";

$items = getItems();
$tab = [];
for($i = 0; $i < count($items); $i++){
		$tab1["name"] = $items[$i]->name;
		$tab1["color"] = $items[$i]->color;
		$tab1["url"] = $items[$i]->url;
		$tab[$i] = $tab1;
}
//echo var_dump($tab);

header('Content-Type: application/json');
echo json_encode(utf8ize($tab));
?>
