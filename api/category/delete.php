<?php

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: DELETE');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers,Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate DB & Connect
$database = new Database();
$db = $database->connect();

// Instantiate blog post object
$category = new Category($db);

// Get category ID
$category->id = isset($_GET['cat_id']) ? $_GET['cat_id'] : die();
// Get raw posted data
// $data = json_decode(file_get_contents("php://input"));

// Delete Category
if ($category->delete()) {
    echo json_encode(array('message' => 'Category Deleted'));
} else {
    echo json_encode(array('message' => 'Category Not Deleted'));
}
