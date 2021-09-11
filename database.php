<?php

define ("DB_NAME", "bookshelf");

function create_statement(mysqli $conn, string $query) : mysqli_statement {
    return $conn->prepare($query);
}

class database {
    private mysqli $conn;
    private users_table $usersTable;

    public function __construct(string $ip, string $user, string $psw) {
        $this->conn = new mysqli($ip, $user, $psw, bookshelf);
        if($this->conn->connect_error)
            die("Connection to the Database failed.");
        
        $usersTable = new users_table($conn);
    }

    public function get_users() : users_table {
        return $this->usersTable;
    }
}

class users_table {
    private mysqli $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function add_user(string $email, string $hashed_psw, string $first_name, string $last_name) : bool {
        mysqli_statement : $query = create_statement($this->conn, "INSERT INTO users ('email', 'password', 'first_name', 'last_name') VALUES (?, ?, ?, ?)");
        $query->bind_param("ssss", $email, $hashed_psw, $first_name, $last_name);
        $query->execute();
        return $query->get_result()->num_rows > 0;
    }

    public function check_credentials(string $email, string $hashed_psw) : bool {
        mysqli_statement : $query = create_statement($this->conn, "SELECT * FROM users WHERE 'email' = ? AND 'password' = ?");
        $query->bind_param("ss", $email, $hashed_psw);
        return $query->execute();
    }
}

?>