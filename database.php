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

class book_data {
    public int $id;
    public string $title;
    public string $author;
    public int $category;
    public string $state;
    public string $price;
    public string $user_email;
}

class books_table {
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function add_book(book_data $book) : bool {
        mysqli : $query = create_statement($this->conn, "INSERT INTO books (title, author, category, state, price, owner) VALUES (?, ?, ?, ?, ?, ?)");
        $query->bind_param("ssisss", $book->title, $book->author, $book->category, $book->state, $book->price, $book->user_email);
        return $query->execute() && $query->affected_rows > 0;
    }

    public function get_user_books(string $user_email) : array {
        mysqli : $query = create_statement($this->conn, "SELECT * FROM books WHERE owner = ?");
        $query->bind_param("s", $user_email);
        if(!$query->execute())
            return [];
        
        $results = $query->get_result()->fetch_all(MYSQLI_ASSOC);
        $books = [];
        foreach ($results as $result)
            array_push($books, map_result_to_book($result));
        return $books;
    }

    public function get_books_in_category(int $category) : array {
        mysqli : $query = create_statement($this->conn, "SELECT * FROM books WHERE category = ?");
        $query->bind_param("i", $category);
        if(!$query->execute())
            return [];
        
        $results = $query->get_result()->fetch_all(MYSQLI_ASSOC);
        $books = [];
        foreach ($results as $result)
            array_push($books, map_result_to_book($result));
        return $books;
    }

    private function map_result_to_book(array $result) : book_data {
        book_data : $book = new book_data();
        $book->id = $result["id"];
        $book->title = $result["title"];
        $book->author = $result["author"];
        $book->category = $result["category"];
        $book->state = $result["state"];
        $book->owner = $result["owner"];
        return $book;
    }
}

class categories_table {
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function get_categories() : array {
        mysqli : $query = create_statement($this->conn, "SELECT * FROM categories");
        return $query->execute() ? $query->get_result()->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function get_category_name(int $id) : string {
        mysqli : $query = create_statement($this->conn, "SELECT name FROM categories WHERE id = ?");
        $query->bind_param("i", $id);
        return $query->execute() ? $query->get_result()->fetch_all(MYSQLI_NUM)[0] : [];
    }
}

?>