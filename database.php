<?php

define ("DB_NAME", "bookshelf");

function create_statement(mysqli $conn, string $query) : mysqli_stmt {
    return $conn->prepare($query);
}

class database {
    private bool $connected;
    private mysqli $conn;
    private users_table $users_table;
    private books_table $books_table;
    private categories_table $categories_table;
    private carted_books_table $carted_books_table;

    public function __construct() {
        $this->connected = false;
    }

    public function connect(string $ip, string $user, string $psw) : bool {
        if ($this->connected)
            return true;

        $this->conn = new mysqli($ip, $user, $psw, "bookshelf");
        $this->connected = !$this->conn->connect_error;
        if(!$this->connected)
            return false;
        
        $this->users_table = new users_table($this->conn);
        $this->books_table = new books_table($this->conn);
        $this->categories_table = new categories_table($this->conn);
        $this->carted_books_table = new carted_books_table($this->conn);
        return true;
    }

    public function get_users() : users_table {
        return $this->users_table;
    }

    public function get_books() : books_table {
        return $this->books_table;
    }

    public function get_categories() : categories_table {
        return $this->categories_table;
    }

    public function get_carted_books() : carted_books_table {
        return $this->carted_books_table;
    }

    public function get_payment_methods() : payment_methods_table{
        return $this->payment_methods_table;
    }
}

class users_table {
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function add_user(string $email, string $hashed_psw, string $first_name, string $last_name) : bool {
        mysqli_stmt : $query = create_statement($this->conn, "INSERT INTO users (email, password, first_name, last_name) VALUES (?, ?, ?, ?)");
        $query->bind_param("ssss", $email, $hashed_psw, $first_name, $last_name);
        return $query->execute() && $query->affected_rows > 0;
    }

    public function check_credentials(string $email, string $hashed_psw) : bool {
        mysqli_stmt : $query = create_statement($this->conn, "SELECT email FROM users WHERE email = ? AND password = ?");
        $query->bind_param("ss", $email, $hashed_psw);
        return $query->execute() && $query->get_result()->num_rows > 0;
    }

    public function get_infos(string $email) : array {
        mysqli_stmt : $query = create_statement($this->conn, "SELECT * FROM users WHERE email = ? ");
        $query->bind_param("s", $email);
        return $query->execute() ? $query->get_result()->fetch_all(MYSQLI_ASSOC)[0] : [];
    }

    public function remove_user(string $email) : bool {
        mysqli_stmt : $query = create_statement($this->conn, "DELETE FROM users WHERE email = ?");
        $query->bind_param("s", $email);
        return $query->execute() && $query->affected_rows > 0;
    }
}

define("BOOK_FREE", "FREE");
define("BOOK_IN_CART", "IN_CART");
define("BOOK_SOLD", "SOLD");

class book_data {
    public int $id = 0;
    public string $title = "";
    public string $author = "";
    public ?int $category = NULL;
    public string $state = "";
    public string $price = "";
    public string $available = "";
    public string $user_email = "";

    public static function map_from_result(array $result) : book_data {
        $book = new book_data();
        $book->id = $result["id"];
        $book->title = $result["title"];
        $book->author = $result["author"];
        $book->category = $result["category"];
        $book->state = $result["state"];
        $book->price = $result["price"];
        $book->available = $result["available"];
        $book->user_email = $result["owner"];
        return $book;
    }
}

define("BOOKS_RESULT_PAGE_COUNT", 20);

class books_table {    
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function add_book(book_data $book) : bool {
        $query = create_statement($this->conn, "INSERT INTO books (title, author, category, state, price, owner) VALUES (?, ?, ?, ?, ?, ?)");
        $query->bind_param("ssisss", $book->title, $book->author, $book->category, $book->state, $book->price, $book->user_email);
        return $query->execute() && $query->affected_rows > 0;
    }

    public function get_user_books(string $user_email) : array {
        $query = create_statement($this->conn, "SELECT * FROM books WHERE owner = ? ORDER BY title");
        $query->bind_param("s", $user_email);
        if(!$query->execute())
            return [];
        
        $results = $query->get_result()->fetch_all(MYSQLI_ASSOC);
        $books = [];
        foreach ($results as $result)
            array_push($books, book_data::map_from_result($result));
        return $books;
    }

    public function get_books_in_category(int $category, int $order, int $page) : array {
        $order_column;
        switch($order) {
            default:
            case 0:
                $order_column = "title";
                break;
            case 1:
                $order_column = "author";
                break;
            case 2:
                $order_column = "price";
                break;
        }
        $query = create_statement($this->conn, "SELECT * FROM books WHERE category = ? ORDER BY $order_column LIMIT ?, ?");
        $page_count = BOOKS_RESULT_PAGE_COUNT;
        $page_offset = $page * $page_count;
        $query->bind_param("iii", $category, $page_offset, $page_count);
        if(!$query->execute())
            return [];
        
        $results = $query->get_result()->fetch_all(MYSQLI_ASSOC);
        $books = [];
        foreach ($results as $result)
            array_push($books, book_data::map_from_result($result));
        return $books;
    }

    public function get_book_pages_in_category_count(int $category) : int {
        $query = create_statement($this->conn, "SELECT COUNT(*) FROM books WHERE category = ?");
        $query->bind_param("i", $category);
        return $query->execute() ? ($query->get_result()->fetch_all(MYSQLI_NUM)[0][0] / BOOKS_RESULT_PAGE_COUNT) + 1 : 0;
    }

    public function get_book(int $id) : book_data {
        $query = create_statement($this->conn, "SELECT * FROM books WHERE id = ?");
        $query->bind_param("i", $id);
        if(!$query->execute())
            return [];
        
        $results = $query->get_result()->fetch_all(MYSQLI_ASSOC);
        return book_data::map_from_result($results[0]);
    }

    public function edit_book(book_data $book) : bool {
        $query = create_statement($this->conn, "UPDATE books SET title = ?, author = ?, category = ?, state = ?, price = ?, owner = ? WHERE id = ?");
        $query->bind_param("ssisssi", $book->title, $book->author, $book->category, $book->state, $book->price, $book->user_email, $book->id);
        return $query->execute();
    }

    public function search(string $key) : array {
        $key = "%" . $key . "%";
        $query = create_statement($this->conn, "SELECT * FROM books WHERE title LIKE ? OR author LIKE ? ORDER BY title");
        $query->bind_param("ss", $key, $key);
        if(!$query->execute())
            return [];
        
        $results = $query->get_result()->fetch_all(MYSQLI_ASSOC);
        $books = [];
        foreach ($results as $result)
            array_push($books, book_data::map_from_result($result));
        return $books;
    }
}

class categories_table {
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function get_categories() : array {
        $query = create_statement($this->conn, "SELECT * FROM categories");
        return $query->execute() ? $query->get_result()->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function get_category_name(int $id) : string {
        $query = create_statement($this->conn, "SELECT name FROM categories WHERE id = ?");
        $query->bind_param("i", $id);
        return $query->execute() ? $query->get_result()->fetch_all(MYSQLI_NUM)[0] : [];
    }
}

class carted_books_table {
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function get_carted_books(string $user_id) : array {
        $query = create_statement($this->conn, "SELECT * FROM carted_books, books WHERE  user_id = ? AND carted_books.book_id = books.id");
        $query->bind_param("s", $user_id);
        //$query = create_statement($this->conn, "SELECT * FROM books");
        if(!$query->execute())
            return [];
        
        $results = $query->get_result()->fetch_all(MYSQLI_ASSOC);
        $books = [];
        foreach ($results as $result)
            array_push($books, book_data::map_from_result($result));
        return $books;
    }

    public function add_book_to_cart(string $user_email, int $book_id) : bool {
        $query = create_statement($this->conn, "INSERT INTO carted_books VALUES(?, ?)");
        $query->bind_param("is", $book_id, $user_email);
        return $query->execute() && $query->affected_rows > 0;
    }

    public function is_book_in_cart(string $user_email, int $book_id) : bool {
        $query = create_statement($this->conn, "SELECT * FROM carted_books WHERE user_id = ? AND book_id = ?");
        $query->bind_param("si", $user_email, $book_id);
        return $query->execute() && $query->get_result()->num_rows > 0;
    }

    public function remove_book_to_cart(string $user_email, int $book_id) : bool {
        $query = create_statement($this->conn, "DELETE FROM carted_books WHERE user_id = ? AND book_id = ?");
        $query->bind_param("si", $user_email, $book_id);
        return $query->execute() && $query->affected_rows > 0;
    }
}

class payment_data {
    public int $payment_id = 0;
    public string $user_id = "";
    public string $type = "";
    public int $number = NULL;
    public int $cvv = "";
    public string $date = "";

    public static function map_from_result(array $result) : payment_data {
        $payment = new payment_data();
        $payment->payment_id = $result["payment_id"];
        $payment->user_id = $result["user_id"];
        $payment->type = $result["type"];
        $payment->number = $result["number"];
        $payment->cvv = $result["cvv"];
        $payment->date = $result["date"];
        return $payment;
    }
}

class address_data {
    public int $address_id = 0;
    public string $user_id = "";
    public string $address = "";

    public static function map_from_result(array $result) : address_data {
        $address = new address_data();
        $address->address_id = $result["address_id"];
        $address->user_id = $result["user_id"];
        $address->address = $result["address"];
        return $address;
    }
}

class payment_methods_table{
    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function get_carted_books(string $user_id) : array {
        $query = create_statement($this->conn, "SELECT * FROM payment_methods WHERE  user_id = ? ");
        $query->bind_param("s", $user_id);
        if(!$query->execute())
            return [];
        
        $results = $query->get_result()->fetch_all(MYSQLI_ASSOC);
        $books = [];
        foreach ($results as $result)
            array_push($books, book_data::map_from_result($result));
        return $books;
    }
}

?>