<?php

include("../database.php");
include("../session.php");

function create_book_from_data(array $data): ?book_data {
    if (!isset($data["title"]) || !isset($data["author"]) || !isset($data["state"]) || !isset($data["category"]) || !isset($data["price"]))
        return NULL;
    if (!is_numeric($data["price"]) || !is_numeric($data["category"]))
        return NULL;

    $book = new book_data();
    $book->title = $data["title"];
    $book->author = $data["author"];
    $book->state = $data["state"];
    $book->category = $data["category"];
    $book->price = $data["price"];
    $book->user_email = get_client_info()["email"];

    return $book;
}

function add_image_data(book_data $book, string $name) : bool {
    $types = explode("/", $_FILES[$name]["type"]);
    if (count($types) < 2) {
        return false;
    }

    $image_type = $types[1];
    if (!in_array($image_type, [ "jpg", "jpeg", "png", "gif" ])) {
        return false;
    }

    if (!file_exists("../imgs/uploads/")) {
        mkdir("../imgs/uploads/");
    }

    $path = "./imgs/uploads/{$book->id}.$image_type";
    file_put_contents(".$path", file_get_contents($_FILES[$name]["tmp_name"]));
    $book->image = $path;
    return true;
}

try {
    $db_conn = new database();
    if (!$db_conn->connect("localhost", "root", "") || !isset($_POST["action"])) {
        echo json_encode(false);
        return;
    }

    switch($_POST["action"]) {
        case "list":
            echo json_encode($db_conn->get_books()->get_user_books($_POST["email"]));
            break;
        case "add":
            if (!is_user_logged() || !isset($_FILES["image"])) {
                echo json_encode(false);
                break;
            }

            $book = create_book_from_data($_POST);
            if ($book == NULL) {
                echo json_encode(false);
                break;
            }
            
            $new_id = $db_conn->get_books()->add_book($book);
            if ($new_id == -1) {
                echo json_encode(false);
                break;
            }

            $book->id = $new_id;
            if (!add_image_data($book, "image")) {
                echo json_encode(false);
                break;
            }

            echo json_encode($db_conn->get_books()->edit_book($book));
            break;
        case "edit":
            if (!is_user_logged() || !isset($_POST["id"])) {
                echo json_encode(false);
                break;
            }

            $old_book = $db_conn->get_books()->get_book($_POST["id"]);
            if ($old_book->user_email != get_client_info()["email"]) {
                echo json_encode(false);
                break;
            }
            
            $book = create_book_from_data($_POST);
            if ($book == NULL) {
                echo json_encode(false);
                break;
            }

            $book->id = $_POST["id"];
            if (isset($_FILES["image"]) && $_FILES["image"]["name"] != "") {
                if (!add_image_data($book, "image")) {
                    echo json_encode(false);
                    break;
                }
            } else {
                $book->image = $old_book->image;
            }

            echo json_encode($db_conn->get_books()->edit_book($book));
            break;
        case "remove":
            if (!is_user_logged() || !isset($_POST["id"])) {
                echo json_encode(false);
                break;
            }
            
            $book = $db_conn->get_books()->get_book($_POST["id"]);
            if ($book->user_email != get_client_info()["email"]) {
                echo json_encode(false);
                break;
            }

            $relative_img_path = "." . $book->image;
            if (file_exists($relative_img_path)) {
                unlink($relative_img_path);
            }
            echo json_encode($db_conn->get_books()->delete_book($book->id));
            break;
    }
} catch(exception $e) {
    echo json_encode(false);
}
?>