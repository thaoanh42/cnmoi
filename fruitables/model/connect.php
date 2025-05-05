<?php
class Connect {
    private $conn;

    // Method to establish a database connection
    public function connectDB() {
        // Establish a connection to the MySQL database
        $this->conn = new mysqli('localhost', 'root', '', 'nongsan');
        
        // Check if the connection was successful
        if ($this->conn->connect_error) {
            // Log the error and return null for better error handling in the calling code
            error_log("Connection failed: " . $this->conn->connect_error);
            return null;
        }
        return $this->conn;
    }

    // Method to fetch student data (example)
    public function xuatsinhvien($sql) {
        $link = $this->connectDB();
        
        if ($link === null) {
            echo json_encode([]); // Return empty array if connection failed
            return;
        }
        
        $result = $link->query($sql);
        
        if ($result && $result->num_rows > 0) {
            $dulieu = array();
            // Fetch all rows and store them in an associative array
            while ($row = $result->fetch_assoc()) {
                $dulieu[] = array(
                    'id' => $row['id'], 
                    'cauhoi' => $row['cauhoi'], 
                    'traloi' => $row['traloi']
                );
            }
            
            // Output the results as JSON
            header("Content-Type: application/json; charset=UTF-8");
            echo json_encode($dulieu);
        } else {
            // Return an empty array if no data was found
            echo json_encode([]);
        }
    }

    // Method to query chatbot answers
    public function chatbot($sp) {
        $con = $this->connectDB();
        
        if ($con === null) {
            error_log("Database connection failed");
            return false; // Unable to connect to DB
        }
        
        // Use prepared statements to prevent SQL injection
        $stmt = $con->prepare("SELECT traloi FROM chatbot WHERE cauhoi LIKE ? LIMIT 1");
        if ($stmt === false) {
            error_log("Failed to prepare statement: " . $con->error);
            return false;
        }
        
        $searchTerm = "%$sp%";
        $stmt->bind_param("s", $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            return $row['traloi'];
        } else {
            return false; // No answer found
        }
    }

    // Method for executing a query (general use case)
    public function truyvandulieu($sql) {
        $link = $this->connectDB();
        
        if ($link === null) {
            error_log("Database connection failed");
            return false; // Unable to connect to DB
        }
        
        if ($link->query($sql)) {
            return true; // Query executed successfully
        } else {
            // Log error details and return false
            error_log("SQL Error: " . $link->error);
            return false; // Query failed
        }
    }
}
?>
