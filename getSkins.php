<?php
include "functions.php";

$items = getItems();
$tab = [];
for($i = 0; $i < count($items); $i++){
		$tab1["name"] = $items[$i]->name;
		$tab1["color"] = $items[$i]->color;
		$tab1["url"] = $items[$i]->url;
		$tab1["price"] = $items[$i]->price;
		$tab1["username"] = $items[$i]->username;
		$tab1["avatar"] = $items[$i]->avatar;
		$tab1["userid"] = $items[$i]->userid;
		$tab[$i] = $tab1;
}
//echo var_dump($tab);

header('Content-Type: application/json');
echo json_encode(utf8ize($tab));
?>
