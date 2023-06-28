<?php
require_once 'db_connect.php';

$output = array('data' => array());
$sql = "SELECT * FROM orders_list WHERE status != 'Completed'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $editButton = "<button class='edit-btn bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg col-span-8 mb-1' data-userid='" . $row['order_id'] . "' onclick='window.location.href = \"api/updateOrderStatus.php?order_id=" . $row['order_id'] . "\"'>Update</button>";
        if ($row['status'] == 'Completed') {
            $options = "";
        } else {
            $options = $editButton;
        }

        $output['data'][] = [
            $row['order_id'],
            $row['order_data'],
            $row['total_price'],
            $row['status'],
            $options
        ];
    }
}

mysqli_close($conn);

header('Content-Type: application/json');
echo json_encode($output);
?>
