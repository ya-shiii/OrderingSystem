<?php
require_once 'db_connect.php';

// Get today's date
$today = date('Y-m-d');
// Get the start date of the current week (Sunday)
$startDate = date('Y-m-d', strtotime('last Sunday'));
// Calculate the start date of the previous week (Sunday to Sunday)
$startDate1 = date('Y-m-d', strtotime('last Sunday -1 week'));

// Get the start date of the current month (first day of the month)
$startDateMonth = date('Y-m-01');
// Get the start date of the previous month (first day of the month)
$startDateMonth1 = date('Y-m-01', strtotime('last month'));


// Initialize the response array
$response = array(
    'today' => array(
        'totalEarnings' => 0,
        'topSelling' => '',
        'soldQuantity' => 0,
        'leastSoldItem' => ''
    ),
    'week' => array(
        'totalEarnings' => 0,
        'topSelling' => '',
        'soldQuantity' => 0,
        'leastSoldItem' => ''
    ),
    'prevweek' => array(
        'totalEarnings' => 0,
        'topSelling' => '',
        'soldQuantity' => 0,
        'leastSoldItem' => ''
    ),
    'month' => array(
        'totalEarnings' => 0,
        'topSelling' => '',
        'soldQuantity' => 0,
        'leastSoldItem' => ''
    )
);

// Query to get today's total earnings
$todayTotalEarningsQuery = "SELECT COALESCE(SUM(earnings), 0) AS totalEarnings
    FROM orders_statistics
    WHERE DATE(date_ordered) = '$today'";
$todayTotalEarningsResult = mysqli_query($conn, $todayTotalEarningsQuery);
if ($todayTotalEarningsResult && mysqli_num_rows($todayTotalEarningsResult) > 0) {
    $todayTotalEarningsData = mysqli_fetch_assoc($todayTotalEarningsResult);
    $response['today']['totalEarnings'] = $todayTotalEarningsData['totalEarnings'];
}

// Query to get the top-selling item and sold quantity for today
$todayTopSellingQuery = "SELECT items_list.item_name AS topSelling, SUM(orders_statistics.quantity) AS soldQuantity
    FROM orders_statistics
    JOIN items_list ON orders_statistics.item_id = items_list.item_id
    WHERE DATE(orders_statistics.date_ordered) = '$today'
    GROUP BY orders_statistics.item_id
    ORDER BY soldQuantity DESC
    LIMIT 1";
$todayTopSellingResult = mysqli_query($conn, $todayTopSellingQuery);
if ($todayTopSellingResult && mysqli_num_rows($todayTopSellingResult) > 0) {
    $todayTopSellingData = mysqli_fetch_assoc($todayTopSellingResult);
    $response['today']['topSelling'] = $todayTopSellingData['topSelling'];
    $response['today']['soldQuantity'] = (int) $todayTopSellingData['soldQuantity']; // Convert to integer
}

// Query to get the least sold item for today
$todayLeastSoldQuery = "SELECT items_list.item_name AS leastSoldItem, SUM(orders_statistics.quantity) AS totalQuantity
    FROM orders_statistics
    JOIN items_list ON orders_statistics.item_id = items_list.item_id
    WHERE DATE(orders_statistics.date_ordered) = '$today'
    GROUP BY orders_statistics.item_id
    HAVING totalQuantity = (
        SELECT MIN(subquery.totalQuantity)
        FROM (
            SELECT SUM(orders_statistics.quantity) AS totalQuantity
            FROM orders_statistics
            WHERE DATE(orders_statistics.date_ordered) = '$today'
            GROUP BY orders_statistics.item_id
        ) AS subquery
    )
    LIMIT 1";
$todayLeastSoldResult = mysqli_query($conn, $todayLeastSoldQuery);
if ($todayLeastSoldResult && mysqli_num_rows($todayLeastSoldResult) > 0) {
    $todayLeastSoldData = mysqli_fetch_assoc($todayLeastSoldResult);
    $response['today']['leastSoldItem'] = $todayLeastSoldData['leastSoldItem'];
}

//===================================================================================================================
// Query to get the total earnings for the current week
$weekTotalEarningsQuery = "SELECT COALESCE(SUM(earnings), 0) AS totalEarnings
    FROM orders_statistics
    WHERE DATE(date_ordered) >= '$startDate' AND DATE(date_ordered) <= '$today'";
$weekTotalEarningsResult = mysqli_query($conn, $weekTotalEarningsQuery);
if ($weekTotalEarningsResult && mysqli_num_rows($weekTotalEarningsResult) > 0) {
    $weekTotalEarningsData = mysqli_fetch_assoc($weekTotalEarningsResult);
    $response['week']['totalEarnings'] = $weekTotalEarningsData['totalEarnings'];
}

// Query to get the top-selling item and sold quantity for the current week
$weekTopSellingQuery = "SELECT items_list.item_name AS topSelling, SUM(orders_statistics.quantity) AS soldQuantity
    FROM orders_statistics
    JOIN items_list ON orders_statistics.item_id = items_list.item_id
    WHERE DATE(orders_statistics.date_ordered) >= '$startDate' AND DATE(orders_statistics.date_ordered) <= '$today'
    GROUP BY orders_statistics.item_id
    ORDER BY soldQuantity DESC
    LIMIT 1";
$weekTopSellingResult = mysqli_query($conn, $weekTopSellingQuery);
if ($weekTopSellingResult && mysqli_num_rows($weekTopSellingResult) > 0) {
    $weekTopSellingData = mysqli_fetch_assoc($weekTopSellingResult);
    $response['week']['topSelling'] = $weekTopSellingData['topSelling'];
    $response['week']['soldQuantity'] = (int) $weekTopSellingData['soldQuantity']; // Convert to integer
}

// Query to get the least sold item for the current week
$weekLeastSoldQuery = "SELECT items_list.item_name AS leastSoldItem, SUM(orders_statistics.quantity) AS totalQuantity
    FROM orders_statistics
    JOIN items_list ON orders_statistics.item_id = items_list.item_id
    WHERE DATE(orders_statistics.date_ordered) >= '$startDate' AND DATE(orders_statistics.date_ordered) <= '$today'
    GROUP BY orders_statistics.item_id
    HAVING totalQuantity = (
        SELECT MIN(subquery.totalQuantity)
        FROM (
            SELECT SUM(orders_statistics.quantity) AS totalQuantity
            FROM orders_statistics
            WHERE DATE(orders_statistics.date_ordered) >= '$startDate' AND DATE(orders_statistics.date_ordered) <= '$today'
            GROUP BY orders_statistics.item_id
        ) AS subquery
    )
    LIMIT 1";
$weekLeastSoldResult = mysqli_query($conn, $weekLeastSoldQuery);
if ($weekLeastSoldResult && mysqli_num_rows($weekLeastSoldResult) > 0) {
    $weekLeastSoldData = mysqli_fetch_assoc($weekLeastSoldResult);
    $response['week']['leastSoldItem'] = $weekLeastSoldData['leastSoldItem'];
}
//===================================================================================================================
// Query to get the total earnings for the previous week
$prevweekTotalEarningsQuery = "SELECT COALESCE(SUM(earnings), 0) AS totalEarnings
    FROM orders_statistics
    WHERE DATE(date_ordered) >= '$startDate1' AND DATE(date_ordered) < '$startDate'";
$prevweekTotalEarningsResult = mysqli_query($conn, $prevweekTotalEarningsQuery);
if ($prevweekTotalEarningsResult && mysqli_num_rows($prevweekTotalEarningsResult) > 0) {
    $prevweekTotalEarningsData = mysqli_fetch_assoc($prevweekTotalEarningsResult);
    $response['prevweek']['totalEarnings'] = $prevweekTotalEarningsData['totalEarnings'];
}

// Query to get the top-selling item and sold quantity for the previous week
$prevweekTopSellingQuery = "SELECT items_list.item_name AS topSelling, SUM(orders_statistics.quantity) AS soldQuantity
    FROM orders_statistics
    JOIN items_list ON orders_statistics.item_id = items_list.item_id
    WHERE DATE(orders_statistics.date_ordered) >= '$startDate1' AND DATE(orders_statistics.date_ordered) < '$startDate'
    GROUP BY orders_statistics.item_id
    ORDER BY soldQuantity DESC
    LIMIT 1";
$prevweekTopSellingResult = mysqli_query($conn, $prevweekTopSellingQuery);
if ($prevweekTopSellingResult && mysqli_num_rows($prevweekTopSellingResult) > 0) {
    $prevweekTopSellingData = mysqli_fetch_assoc($prevweekTopSellingResult);
    $response['prevweek']['topSelling'] = $prevweekTopSellingData['topSelling'];
    $response['prevweek']['soldQuantity'] = (int) $prevweekTopSellingData['soldQuantity']; // Convert to integer
}

// Query to get the least sold item for the previous week
$prevweekLeastSoldQuery = "SELECT items_list.item_name AS leastSoldItem, SUM(orders_statistics.quantity) AS totalQuantity
    FROM orders_statistics
    JOIN items_list ON orders_statistics.item_id = items_list.item_id
    WHERE DATE(orders_statistics.date_ordered) >= '$startDate1' AND DATE(orders_statistics.date_ordered) < '$startDate'
    GROUP BY orders_statistics.item_id
    HAVING totalQuantity = (
        SELECT MIN(subquery.totalQuantity)
        FROM (
            SELECT SUM(orders_statistics.quantity) AS totalQuantity
            FROM orders_statistics
            WHERE DATE(orders_statistics.date_ordered) >= '$startDate1' AND DATE(orders_statistics.date_ordered) < '$startDate'
            GROUP BY orders_statistics.item_id
        ) AS subquery
    )
    LIMIT 1";
$prevweekLeastSoldResult = mysqli_query($conn, $prevweekLeastSoldQuery);
if ($prevweekLeastSoldResult && mysqli_num_rows($prevweekLeastSoldResult) > 0) {
    $prevweekLeastSoldData = mysqli_fetch_assoc($prevweekLeastSoldResult);
    $response['prevweek']['leastSoldItem'] = $prevweekLeastSoldData['leastSoldItem'];
}
//===================================================================================================================
// Query to get the total earnings for the current month
$monthTotalEarningsQuery = "SELECT COALESCE(SUM(earnings), 0) AS totalEarnings
    FROM orders_statistics
    WHERE DATE(date_ordered) >= '$startDateMonth' AND DATE(date_ordered) <= '$today'";
$monthTotalEarningsResult = mysqli_query($conn, $monthTotalEarningsQuery);
if ($monthTotalEarningsResult && mysqli_num_rows($monthTotalEarningsResult) > 0) {
    $monthTotalEarningsData = mysqli_fetch_assoc($monthTotalEarningsResult);
    $response['month']['totalEarnings'] = $monthTotalEarningsData['totalEarnings'];
}

// Query to get the top-selling item and sold quantity for the current month
$monthTopSellingQuery = "SELECT items_list.item_name AS topSelling, SUM(orders_statistics.quantity) AS soldQuantity
    FROM orders_statistics
    JOIN items_list ON orders_statistics.item_id = items_list.item_id
    WHERE DATE(orders_statistics.date_ordered) >= '$startDateMonth' AND DATE(orders_statistics.date_ordered) <= '$today'
    GROUP BY orders_statistics.item_id
    ORDER BY soldQuantity DESC
    LIMIT 1";
$monthTopSellingResult = mysqli_query($conn, $monthTopSellingQuery);
if ($monthTopSellingResult && mysqli_num_rows($monthTopSellingResult) > 0) {
    $monthTopSellingData = mysqli_fetch_assoc($monthTopSellingResult);
    $response['month']['topSelling'] = $monthTopSellingData['topSelling'];
    $response['month']['soldQuantity'] = (int) $monthTopSellingData['soldQuantity']; // Convert to integer
}

// Query to get the least sold item for the current month
$monthLeastSoldQuery = "SELECT items_list.item_name AS leastSoldItem, SUM(orders_statistics.quantity) AS totalQuantity
    FROM orders_statistics
    JOIN items_list ON orders_statistics.item_id = items_list.item_id
    WHERE DATE(orders_statistics.date_ordered) >= '$startDateMonth' AND DATE(orders_statistics.date_ordered) <= '$today'
    GROUP BY orders_statistics.item_id
    HAVING totalQuantity = (
        SELECT MIN(subquery.totalQuantity)
        FROM (
            SELECT SUM(orders_statistics.quantity) AS totalQuantity
            FROM orders_statistics
            WHERE DATE(orders_statistics.date_ordered) >= '$startDateMonth' AND DATE(orders_statistics.date_ordered) <= '$today'
            GROUP BY orders_statistics.item_id
        ) AS subquery
    )
    LIMIT 1";
$monthLeastSoldResult = mysqli_query($conn, $monthLeastSoldQuery);
if ($monthLeastSoldResult && mysqli_num_rows($monthLeastSoldResult) > 0) {
    $monthLeastSoldData = mysqli_fetch_assoc($monthLeastSoldResult);
    $response['month']['leastSoldItem'] = $monthLeastSoldData['leastSoldItem'];
}
//===================================================================================================================
// Query to get the total earnings for the prev month
$prevmonthTotalEarningsQuery = "SELECT COALESCE(SUM(earnings), 0) AS totalEarnings
    FROM orders_statistics
    WHERE DATE(date_ordered) >= '$startDateMonth1' AND DATE(date_ordered) < '$startDateMonth'";
$prevmonthTotalEarningsResult = mysqli_query($conn, $prevmonthTotalEarningsQuery);
if ($prevmonthTotalEarningsResult && mysqli_num_rows($prevmonthTotalEarningsResult) > 0) {
    $prevmonthTotalEarningsData = mysqli_fetch_assoc($prevmonthTotalEarningsResult);
    $response['prevmonth']['totalEarnings'] = $prevmonthTotalEarningsData['totalEarnings'];
}

// Query to get the top-selling item and sold quantity for the current month
$prevmonthTopSellingQuery = "SELECT items_list.item_name AS topSelling, SUM(orders_statistics.quantity) AS soldQuantity
    FROM orders_statistics
    JOIN items_list ON orders_statistics.item_id = items_list.item_id
    WHERE DATE(orders_statistics.date_ordered) >= '$startDateMonth1' AND DATE(orders_statistics.date_ordered) < '$startDateMonth'
    GROUP BY orders_statistics.item_id
    ORDER BY soldQuantity DESC
    LIMIT 1";
$prevmonthTopSellingResult = mysqli_query($conn, $prevmonthTopSellingQuery);
if ($prevmonthTopSellingResult && mysqli_num_rows($prevmonthTopSellingResult) > 0) {
    $prevmonthTopSellingData = mysqli_fetch_assoc($prevmonthTopSellingResult);
    $response['prevmonth']['topSelling'] = $prevmonthTopSellingData['topSelling'];
    $response['prevmonth']['soldQuantity'] = (int) $prevmonthTopSellingData['soldQuantity']; // Convert to integer
}

// Query to get the least sold item for the current month
$prevmonthLeastSoldQuery = "SELECT items_list.item_name AS leastSoldItem, SUM(orders_statistics.quantity) AS totalQuantity
    FROM orders_statistics
    JOIN items_list ON orders_statistics.item_id = items_list.item_id
    WHERE DATE(orders_statistics.date_ordered) >= '$startDateMonth1' AND DATE(orders_statistics.date_ordered) < '$startDateMonth'
    GROUP BY orders_statistics.item_id
    HAVING totalQuantity = (
        SELECT MIN(subquery.totalQuantity)
        FROM (
            SELECT SUM(orders_statistics.quantity) AS totalQuantity
            FROM orders_statistics
            WHERE DATE(orders_statistics.date_ordered) >= '$startDateMonth1' AND DATE(orders_statistics.date_ordered) < '$startDateMonth'
            GROUP BY orders_statistics.item_id
        ) AS subquery
    )
    LIMIT 1";
$prevmonthLeastSoldResult = mysqli_query($conn, $prevmonthLeastSoldQuery);
if ($prevmonthLeastSoldResult && mysqli_num_rows($prevmonthLeastSoldResult) > 0) {
    $prevmonthLeastSoldData = mysqli_fetch_assoc($prevmonthLeastSoldResult);
    $response['prevmonth']['leastSoldItem'] = $prevmonthLeastSoldData['leastSoldItem'];
}

// Convert the response array to JSON
$jsonResponse = json_encode($response);

// Print the JSON response
echo $jsonResponse;
?>
