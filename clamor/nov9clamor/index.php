<?php  
require_once 'core/models.php'; 
require_once 'core/handleForms.php'; 

if (!isset($_SESSION['username'])) {
	header("Location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<style>
		body {
		font-family: "Arial";
		}
		input {
			font-size: 1.5em;
			height: 50px;
			width: 200px;
		}
		table, th, td {
			border:1px solid black;
		}
	</style>
</head>
<body>
<form action="core/handleForms.php" method="POST">
    <p>
        <label for="firstName">Table Number</label> 
        <input type="text" name="table_num">
    </p>
    <p>
        <label for="firstName">Customer Name</label> 
        <input type="text" name="cus_name">
    </p>
    <p>
        <label for="firstName">Order Name</label> 
        <input type="text" name="cus_order">
    </p>
    <p>
        <label for="firstName">Quantity</label> 
        <input type="text" name="quantity">
    </p>
    <p>
        <label for="firstName">Amount of Payment</label> 
        <input type="text" name="amount_of_money">
        <input type="submit" name="insertCustomerBtn">
    </p>
</form>
<?php $getAllCustomer = getAllCustomer($pdo); ?>
<?php foreach ($getAllCustomer as $row) { ?>
<div class="container" style="border-style: solid; width: 50%; height: 300px; margin-top: 20px;">
    <h3>Table Number: <?php echo $row['table_num']; ?></h3>
    <h3>Customer Name: <?php echo $row['cus_name']; ?></h3>
    <h3>Order Name: <?php echo $row['cus_order']; ?></h3>
    <h3>Quantity: <?php echo $row['quantity']; ?></h3>
    <h3>Amount of Payment: <?php echo $row['amount_of_money']; ?></h3>
    <h3>Date Added: <?php echo $row['date_added']; ?></h3>


    <div class="editAndDelete" style="float: right; margin-right: 20px;">
        <a href="viewUser.php?customer_id=<?php echo $row['customer_id']; ?>">View Order</a>
        <a href="editUser.php?customer_id=<?php echo $row['customer_id']; ?>">Edit</a>
        <a href="deleteInfo.php?customer_id=<?php echo $row['customer_id']; ?>">Delete</a>
    </div>


</div> 
<?php } ?>
</body>
</html>