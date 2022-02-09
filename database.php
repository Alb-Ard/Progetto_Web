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
    private payment_methods_table $payment_methods_table;
    private addresses_table $addresses_table;
    private orders_table $orders_table;
    private notifications_table $notifications_table;

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
        $this->payment_methods_table = new payment_methods_table($this->conn);
        $this->addresses_table = new addresses_table($this->conn);
        $this->orders_table = new orders_table($this->conn);
        $this->notifications_table = new notifications_table($this->conn);
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

    public function get_addresses() : addresses_table{
        return $this->addresses_table;
    }

    public function get_orders() : orders_table{
        return $this->orders_table;
    }

    public function get_notifications() : notifications_table {
        return $this->notifications_table;
    }
}

class users_table {
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function add_user(string $email, string $hashed_psw, string $first_name, string $last_name) : bool {
        mysqli_stmt : $query = create_statement($this->conn, "INSERT INTO users (email, `password`, first_name, last_name) VALUES (?, ?, ?, ?)");
        $query->bind_param("ssss", $email, $hashed_psw, $first_name, $last_name);
        return $query->execute() && $query->affected_rows > 0;
    }

    public function check_credentials(string $email, string $hashed_psw) : bool {
        mysqli_stmt : $query = create_statement($this->conn, "SELECT COUNT(*) FROM users WHERE email = ? AND `password` = ?");
        $query->bind_param("ss", $email, $hashed_psw);
        return $query->execute() && $query->get_result()->fetch_array(MYSQLI_NUM)[0] > 0;
    }

    public function get_infos(string $email) : array {
        mysqli_stmt : $query = create_statement($this->conn, "SELECT * FROM users WHERE email = ?");
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
    public string $price = "0.01";
    public string $available = "";
    public string $image = "";
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
        $book->image = $result["image"];
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

    public function add_book(book_data $book) : int {
        $query = create_statement($this->conn, "INSERT INTO books (title, author, category, state, price, image, owner) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $query->bind_param("ssissss", $book->title, $book->author, $book->category, $book->state, $book->price, $book->image, $book->user_email);
        return $query->execute() && $query->affected_rows > 0 ? $conn->insert_id : -1;
    }

    public function get_user_books(string $user_email) : array {
        $query = create_statement($this->conn, "SELECT * FROM books WHERE owner = ? ORDER BY title, author, price");
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
        $orders = [ "title", "author", "price" ];
        $first_order = $orders[$order];
        $second_order = $orders[($order + 1) % 3];
        $third_order = $orders[($order + 1) % 3];
        // ORDER BY params don't work well when passed with bind_param (?)
        $query = create_statement($this->conn, "SELECT * FROM books WHERE category = ? ORDER BY $first_order, $second_order, $third_order LIMIT ?, ?");
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
        $query = create_statement($this->conn, "UPDATE books SET title = ?, author = ?, category = ?, state = ?, price = ?, image = ?, owner = ? WHERE id = ?");
        $query->bind_param("ssissssi", $book->title, $book->author, $book->category, $book->state, $book->price, $book->image, $book->user_email, $book->id);
        return $query->execute();
    }

    public function delete_book(int $book_id) : bool {
        $query = create_statement($this->conn, "DELETE FROM books WHERE id = ?");
        $query->bind_param("i", $book_id);
        return $query->execute() && $query->affected_rows > 0;
    }

    public function search(string $key) : array {
        $key = "%" . $key . "%";
        $query = create_statement($this->conn, "SELECT * FROM books WHERE title LIKE ? OR author LIKE ? ORDER BY title, author, price");
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
        $query = create_statement($this->conn, "SELECT * FROM categories ORDER BY name");
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
        $query = create_statement($this->conn, "SELECT available FROM books WHERE id = ? AND available = 'SOLD'");
        $query->bind_param("i", $book_id);
        if (!$query->execute() || $query->get_result()->num_rows > 0)
            return false;

        $query = create_statement($this->conn, "INSERT INTO carted_books VALUES(?, ?)");
        $query->bind_param("is", $book_id, $user_email);
        return $query->execute() && $query->affected_rows > 0;
    }

    public function is_book_in_cart(string $user_email, int $book_id) : bool {
        $query = create_statement($this->conn, "SELECT * FROM carted_books WHERE user_id = ? AND book_id = ?");
        $query->bind_param("si", $user_email, $book_id);
        return $query->execute() && $query->get_result()->num_rows > 0;
    }

    public function is_book_in_any_cart(int $book_id) : bool {
        $query = create_statement($this->conn, "SELECT * FROM carted_books WHERE book_id = ?");
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
    public int $number = 0;
    public int $cvv = 0;
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

    public function get_payment_methods(string $user_id) : array {
        $query = create_statement($this->conn, "SELECT * FROM payment_methods WHERE  user_id = ? ");
        $query->bind_param("s", $user_id);
        if(!$query->execute())
            return [];
        
        $results = $query->get_result()->fetch_all(MYSQLI_ASSOC);
        $payments = [];
        foreach ($results as $result)
            array_push($payments, payment_data::map_from_result($result));
        return $payments;
    }
    public function add_card(payment_data $card) : bool {
        $query = create_statement($this->conn, "INSERT INTO payment_methods (user_id, type, number, cvv, date) VALUES (?, ?, ?, ?, ?)");
        $query->bind_param("ssiis", $card->user_id, $card->type, $card->number, $card->cvv, $card->date);
        return $query->execute() && $query->affected_rows > 0;
    }

    public function remove_card(string $user_email, payment_data $card) : bool {
        $query = create_statement($this->conn, "DELETE FROM payment_methods WHERE user_id = ? AND payment_id = ?");
        $query->bind_param("si", $user_email, $card->$payment_id);
        return $query->execute() && $query->affected_rows > 0;
    }

    public function get_card(int $id) : payment_data {
        $query = create_statement($this->conn, "SELECT * FROM payment_methods WHERE payment_id = ?");
        $query->bind_param("i", $id);
        if(!$query->execute())
            return [];
        
        $results = $query->get_result()->fetch_all(MYSQLI_ASSOC);
        return payment_data::map_from_result($results[0]);
    }

    public function edit_card(payment_data $card) : bool {
        $query = create_statement($this->conn, "UPDATE payment_methods SET type = ?, number = ?, cvv = ?, date = ? WHERE payment_id = ?");
        $query->bind_param("siisi",  $card->type, $card->number, $card->cvv, $card->date, $card->payment_id);
        return $query->execute();
    }
}

class addresses_table{
    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function get_addresses(string $user_id) : array {
        $query = create_statement($this->conn, "SELECT * FROM addresses WHERE  user_id = ? ");
        $query->bind_param("s", $user_id);
        if(!$query->execute())
            return [];
        
        $results = $query->get_result()->fetch_all(MYSQLI_ASSOC);
        $addresses = [];
        foreach ($results as $result)
            array_push($addresses, address_data::map_from_result($result));
        return $addresses;
    }
    public function add_address(address_data $address) : bool {
        $query = create_statement($this->conn, "INSERT INTO addresses (user_id, address) VALUES (?, ?)");
        $query->bind_param("ss", $address->user_id, $address->address);
        return $query->execute() && $query->affected_rows > 0;
    }

    public function remove_address(string $user_email, address_data $card) : bool {
        $query = create_statement($this->conn, "DELETE FROM addresses WHERE user_id = ? AND address_id = ?");
        $query->bind_param("si", $user_email, $card->$payment_id);
        return $query->execute() && $query->affected_rows > 0;
    }

    public function get_address(int $id) : address_data {
        $query = create_statement($this->conn, "SELECT * FROM addresses WHERE address_id = ?");
        $query->bind_param("i", $id);
        if(!$query->execute())
            return [];
        
        $results = $query->get_result()->fetch_all(MYSQLI_ASSOC);
        return address_data::map_from_result($results[0]);
    }

    public function edit_address(address_data $card) : bool {
        $query = create_statement($this->conn, "UPDATE addresses SET address = ? WHERE address_id = ?");
        $query->bind_param("si",  $address->address, $address->address_id);
        return $query->execute();
    }
}

class orders_table{
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function get_seller_ordered_books(string $user_id) : array {
        $query = create_statement($this->conn, "SELECT * FROM orders, ordered_books, books WHERE owner = ? AND books.id = ordered_books.book_id AND orders.order_id = ordered_books.order_id");
        $query->bind_param("s", $user_id);
        if(!$query->execute())
            return [];
        
        return $query->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function set_ordered_book_state(int $book_id, int $order_id, string $state) : bool {
        $query = create_statement($this->conn, "UPDATE ordered_books SET advancement = ? WHERE book_id = ? AND order_id = ?");
        $query->bind_param("sii", $state, $book_id, $order_id);
        return $query->execute() && $query->affected_rows > 0;
    }

    public function add_ordered_book(int $order_id, int $book_id, string $state) : bool{
        $query = create_statement($this->conn, "INSERT INTO ordered_books (order_id, book_id, advancement) VALUES (?,?,?)");
        $query->bind_param("iis", $order_id, $book_id, $state);
        return $query->execute() && $query->affected_rows > 0;
    }
    public function add_order(string $user_email, int $payment_id, int $address_id) : int {
        $query = create_statement($this->conn, "INSERT INTO orders (user_id, payment_id, address_id) VALUES (?, ?, 1)");
        $query->bind_param("si", $user_email, $payment_id);
        return $query->execute() && $query->affected_rows > 0 ? $this->conn->insert_id : -1;
    }

    public function get_order_client(int $order_id, int $book_id) : string {
        $query = create_statement($this->conn, "SELECT orders.user_id FROM ordered_books, orders WHERE ordered_books.book_id = ? AND orders.order_id = ? AND ordered_books.order_id = orders.order_id");
        $query->bind_param("ii", $book_id, $order_id);
        return $query->execute() ? $query->get_result()->fetch_all(MYSQLI_NUM)[0][0] : "";
    }

    public function get_orders(string $user_id) : array{
        $query = create_statement($this->conn, "SELECT * FROM books, ordered_books, orders WHERE orders.user_id = ? AND books.id = ordered_books.book_id AND ordered_books.order_id = orders.order_id ORDER BY orders.date ASC, orders.order_id");
        $query->bind_param("s", $user_id);
        if(!$query->execute())
            return [];
        
        return $query->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function get_books_from_order(string $order_id) : array {
        $query = create_statement($this->conn, "SELECT * FROM books, ordered_books WHERE  ordered_books.order_id = ? AND books.id = ordered_books.book_id");
        $query->bind_param("s", $order_id);
        if(!$query->execute())
            return [];
        
        return $query->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function get_book_order(int $book_id) : array {
        $query = create_statement($this->conn, "SELECT * FROM order, ordered_books WHERE  ordered_books.book_id = ? AND orders.order_id = ordered_books.order_id");
        $query->bind_param("i", $book_id);
        if(!$query->execute())
            return [];
        
        return $query->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function cancel_order(int $order_id, int $book_id) : bool {
        $query = create_statement($this->conn, "DELETE FROM ordered_books WHERE book_id = ? AND order_id = ?");
        $query->bind_param("ii", $book_id, $order_id);
        if (!$query->execute() || $query->affected_rows == 0) {
            return false;
        }

        $query = create_statement($this->conn, "SELECT COUNT(*) FROM ordered_books WHERE order_id = ?");
        $query->bind_param("i", $order_id);
        if ($query->execute() && $query->get_result()->fetch_all(MYSQLI_NUM)[0] == 0) {
            $query = create_statement($this->conn, "DELETE FROM orders WHERE order_id = ?");
            $query->bind_param("i", $order_id);
            $query->execute();
        }
        return true;
    }
}

class notifications_table {
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function get_user(string $email) : array {
        mysqli_stmt : $query = create_statement($this->conn, "SELECT * FROM notifications, books WHERE notifications.book_id = books.id AND user = ? ORDER BY created_timestamp DESC");
        $query->bind_param("s", $email);
        if (!$query->execute())
            return [];
        
        $results = $query->get_result()->fetch_all(MYSQLI_ASSOC);
        $query = create_statement($this->conn, "UPDATE notifications SET seen = 1 WHERE user = ?");
        $query->bind_param("s", $email);
        if (!$query->execute())
            return [];

        return $results;
    }

    public function get_user_unseen_count(string $email) : int {
        mysqli_stmt : $query = create_statement($this->conn, "SELECT COUNT(*) FROM notifications WHERE user = ? AND seen = 0");
        $query->bind_param("s", $email);
        return !$query->execute() ? 0 : $query->get_result()->fetch_all(MYSQLI_NUM)[0][0];
    }

    public function add(string $to, string $from, int $order_id, int $book_id, string $order_state) : bool {
        mysqli_stmt : $query = create_statement($this->conn, "INSERT INTO notifications (user, from_user, order_id, book_id, order_state) VALUES (?, ?, ?, ?, ?)");
        $query->bind_param("ssiis", $to, $from, $order_id, $book_id, $order_state);
        return $query->execute() && $query->affected_rows > 0;
    }

    public function remove(int $id) : bool {
        mysqli_stmt : $query = create_statement($this->conn, "DELETE FROM notifications WHERE id = ?");
        $query->bind_param("i", $id);
        return $query->execute() && $query->affected_rows > 0;
    }
}

?>