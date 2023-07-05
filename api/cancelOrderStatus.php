<?php
// Include the database connection file
require_once "db_connect.php";

// Check if the orderId is provided in the GET request
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['order_id'])) {
    // Retrieve the orderId
    $orderId = $_GET['order_id'];

    // Start a transaction
    $conn->begin_transaction();

    try {
        // Prepare and execute the query to delete the order from "orders_list" table
        $stmt = $conn->prepare("DELETE FROM orders_list WHERE order_id = ?");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();

        // Prepare and execute the query to delete the order from "orders_statistics" table
        $stmt = $conn->prepare("DELETE FROM orders_statistics WHERE order_id = ?");
        $stmt->bind_param("i", $orderId);
        $stmt->execute();

        // Commit the transaction
        $conn->commit();

        // Order deleted successfully
        echo "Order deleted successfully.";
        echo "<script>window.location.href='../orders.php';</script>";
    } catch (Exception $e) {
        // Rollback the transaction if any error occurs
        $conn->rollback();

        // Error deleting order
        echo "Error deleting order: " . $e->getMessage();
        echo "<script>alert('Error in deleting data'); window.location.href='../orders.php';</script>";
    }

    // Close the prepared statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // Handle case when order ID is not provided
    echo "Order ID not provided.";
}
?>
