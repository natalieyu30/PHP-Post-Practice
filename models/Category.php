<?php

class Category {
    // DB
    private $conn;
    private $table = 'categories';

    // Category properties
    public $id;
    public $name;
    public $created_at;

    // Constructor with DB
    public function __construct($db) {
        $this->conn = $db;
    }

    // Get Categories
    public function read() {
        $query = 
                'SELECT id, name FROM categories
                ORDER BY name';
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Get Single Category
    public function read_single() {
        // Create query
        $query = 
            'SELECT id, name FROM categories
            WHERE id = ?';

        // Prepare statement
        $stmt = $this->conn->prepare($query);

        // Bind ID to placeholder
        $stmt->bindParam(1, $this->id);

        // Execute query
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Set properties
        $this->id = $row['id'];
        $this->name = $row['name'];
    }

    // Create Category
    public function create() {
        $query = 'INSERT INTO Categories
                SET name = :name';

        $stmt = $this->conn->prepare($query);

        // Clean data & Bind to params
        $this->name = htmlspecialchars(strip_tags($this->name));
        $stmt->bindParam(':name', $this->name);

        // Excute query & show response message
        if ($stmt->execute()) {
            return true;
        }
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    // Update Category
    public function update() {
        $query = 'UPDATE categories
            SET name = :name
            WHERE id = :id';

        $stmt = $this->conn->prepare($query);

        // Clean data & Bind to Param
        $this->name = htmlspecialchars(strip_tags($this->name));
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':id', $this->id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }
        // Print error if something goes wrong
        printf("Error: %s.\n", $stmt->error);
        return false;
    }

    // Delete Category
    public function delete() {
        $query = "DELETE FROM categories WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        // Clean data & Bind to Param
        $this->id = htmlspecialchars(strip_tags($this->id));
        $stmt->bindParam(':id', $this->id);

        // Execute query
        if ($stmt->execute()) {
            return true;
        }
        // Print error
        printf("Error: %s.\n", $stmt->error);
        return false;
    }
}