<?php
require_once 'db_connect.php';
session_start();
$usertype = $_SESSION['usertype'];

$output = array('data' => array());
$sql = "SELECT * FROM orders_list WHERE status != 'Completed'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $editButton = "<button class='bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg col-span-8 mb-1' data-userid='" . $row['order_id'] . "' onclick='window.location.href = \"api/updateOrderStatus.php?order_id=" . $row['order_id'] . "\"'>Update</button>";
        $readyButton = "<button class='bg-green-600 hover:bg-green-500 text-white font-bold py-2 px-4 rounded-lg col-span-8 mb-1' data-userid='" . $row['order_id'] . "' onclick='window.location.href = \"api/updateOrderStatus.php?order_id=" . $row['order_id'] . "\"'>Serve</button>";
        $cancelButton = "<button class='bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg col-span-8 mb-1' data-userid='" . $row['order_id'] . "' onclick='confirmCancelOrder(" . $row['order_id'] . ")'>Cancel</button>";
        
        if (($row['status'] == 'Preparing')&&(($usertype == 'Kitchen Staff')||($usertype == 'Admin'))) {
            if($usertype == 'Admin'){
                $options = $readyButton."  ".$cancelButton;
            }else{
                $options = $readyButton;
            }
        } elseif (($row['status'] == 'Serving')&&(($usertype == 'Front Desk')||($usertype == 'Admin'))){
            if($usertype == 'Admin'){
                $options = $editButton." ".$cancelButton;
            }else{
                $options = $editButton;
            }
            
            
        } else {
            $options = "";
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
