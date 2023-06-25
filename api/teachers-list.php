<?php
    
    require_once 'db_connect.php';
    
	$sql = "SELECT * FROM teacher";
	$result = mysqli_query($conn, $sql);

	if (mysqli_num_rows($result) > 0) {
        $list = array();
        while($row = mysqli_fetch_assoc($result)){
            $list[] = $row;
        }
	} else {
        echo "0 results";
	}
    header('Content-Type: application/json');
    print json_encode($list);

	mysqli_close($conn);
?>