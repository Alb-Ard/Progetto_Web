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
}

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
        $query = create_statement($this->conn, "SELECT * FROM books WHERE owner = ?");
        $query->bind_param("s", $user_email);
        if(!$query->execute())
            return [];
        
        $results = $query->get_result()->fetch_all(MYSQLI_ASSOC);
        $books = [];
        foreach ($results as $result)
            array_push($books, $this->map_result_to_book($result));
        return $books;
    }

    public function get_books_in_category(int $category) : array {
        $query = create_statement($this->conn, "SELECT * FROM books WHERE category = ?");
        $query->bind_param("i", $category);
        if(!$query->execute())
            return [];
        
        $results = $query->get_result()->fetch_all(MYSQLI_ASSOC);
        $books = [];
        foreach ($results as $result)
            array_push($books, $this->map_result_to_book($result));
        return $books;
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
            array_push($books, $this->map_result_to_book($result));
        return $books;
    }

    public function get_book(int $id) : book_data {
        $query = create_statement($this->conn, "SELECT * FROM books WHERE id = ?");
        $query->bind_param("i", $id);
        if(!$query->execute())
            return [];
        
        $results = $query->get_result()->fetch_all(MYSQLI_ASSOC);
        return $this->map_result_to_book($results[0]);
    }

    public function edit_book(book_data $book) : bool {
        $query = create_statement($this->conn, "UPDATE books SET title = ?, author = ?, category = ?, state = ?, price = ?, owner = ? WHERE id = ?");
        $query->bind_param("ssisssi", $book->title, $book->author, $book->category, $book->state, $book->price, $book->user_email, $book->id);
        return $query->execute();
    }

    public function search(string $key) : array {
        $key = "%" . $key . "%";
        $query = create_statement($this->conn, "SELECT * FROM books WHERE title LIKE ? OR author LIKE ?");
        $query->bind_param("ss", $key, $key);
        if(!$query->execute())
            return [];
        
        $results = $query->get_result()->fetch_all(MYSQLI_ASSOC);
        $books = [];
        foreach ($results as $result)
            array_push($books, $this->map_result_to_book($result));
        return $books;
    }

    private function map_result_to_book(array $result) : book_data {
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

}

?>