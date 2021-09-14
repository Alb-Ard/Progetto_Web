<?php

include("../database.php");
include("../session.php");

function create_book_from_data(array $data): ?book_data {
    if (!isset($data["title"]) || !isset($data["author"]) || !isset($data["state"]) || !isset($data["category"]) || !isset($data["price"]) || !isset($_FILES["image"]))
        return NULL;
    if (!is_numeric($data["price"]) || !is_numeric($data["category"]))
        return NULL;

    $name = $_FILES["image"]["name"];
    $file_name = basename($name);
    $image_type = $_FILES["image"]["type"];

    if (!in_array($image_type, [ "image/jpg", "image/jpeg", "image/png", "image/gif" ]))
        return NULL;

    $book = new book_data();
    $book->title = $data["title"];
    $book->author = $data["author"];
    $book->state = $data["state"];
    $book->category = $data["category"];
    $book->price = $data["price"];
    $book->user_email = get_client_info()["email"];
    $book->image = "data:" . $image_type . ";base64," . base64_encode(file_get_contents($_FILES["image"]["tmp_name"]));

    return $book;
}

try {
    $db_conn = new database();
    if (!$db_conn->connect("localhost", "root", "") || !isset($_POST["action"])) {
        echo json_encode(false);
        return;
    }

    switch($_POST["action"]) {
        case "list":
            $result = $db_conn->get_books()->get_user_books($_POST["email"]);
            echo json_encode($result);
            break;
        case "add":
            if (!is_user_logged()) {
                echo json_encode(false);
                break;
            }

            $book = create_book_from_data($_POST);
            echo json_encode($book == NULL ? false : $db_conn->get_books()->add_book($book));
            break;
        case "edit":
            if (!is_user_logged() || !isset($_POST["id"])) {
                echo json_encode(false);
                break;
            }

            if ($db_conn->get_books()->get_book($_POST["id"])->user_email != get_client_info()["email"]) {
                echo json_encode(false);
                break;
            }
            
            $book = create_book_from_data($_POST);
            if ($book == NULL) {
                echo json_encode(false);
                break;
            }
            
            $book->id = $_POST["id"];
            echo json_encode($db_conn->get_books()->edit_book($book));
            break;
        case "remove":
            break;
    }
} catch(exception $e) {
    echo json_encode(false);
}
?>