<?php  
require_once 'dbConfig.php';
require_once 'models.php';




if (isset($_POST['insertCustomerBtn'])) {

	$query = insertCustomer($pdo, $_POST['table_num'], $_POST['cus_name'], 
		$_POST['cus_order'], $_POST['quantity'], $_POST['amount_of_money']);

	if ($query) {
		header("Location: ../index.php");
	}
	else {
		echo "Insertion failed";
	}

}


if (isset($_POST['insertNewOrderBtn'])) {
	$query = insertOrder($pdo, $_POST['order_name'], $_POST['price'], $_GET['customer_id']);

	if ($query) {
		header("Location: ../viewUser.php?customer_id=" .$_GET['customer_id']);
	}
	else {
		echo "Insertion failed";
	}
}




if (isset($_POST['editOrderBtn'])) {
	$query = updateOrder($pdo, $_POST['order_name'], $_POST['price'], $_GET['order_id']);

	if ($query) {
		header("Location: ../viewUser.php?customer_id=" .$_GET['customer_id']);
	}
	else {
		echo "Update failed";
	}

}




if (isset($_POST['deleteOrderBtn'])) {
	$query = deleteOrder($pdo, $_GET['order_id']);

	if ($query) {
		header("Location: ../viewUser.php?customer_id=" .$_GET['order_id']);
	}
	else {
		echo "Deletion failed";
	}
}


if (isset($_POST['insertNewUserBtn'])) {
	$username = trim($_POST['username']);
	$first_name = trim($_POST['first_name']);
	$last_name = trim($_POST['last_name']);
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);

	if (!empty($username) && !empty($first_name) && !empty($last_name) && !empty($password) && !empty($confirm_password)) {

		if ($password == $confirm_password) {

			$insertQuery = insertNewUser($pdo, $username, $first_name, $last_name, password_hash($password, PASSWORD_DEFAULT));

			if ($insertQuery['status'] == '200') {
				$_SESSION['message'] = $insertQuery['message'];
				$_SESSION['status'] = $insertQuery['status'];
				header("Location: ../login.php");
			}

			else {
				$_SESSION['message'] = $insertQuery['message'];
				$_SESSION['status'] = $insertQuery['status'];
				header("Location: ../register.php");
			}

		}
		else {
			$_SESSION['message'] = "Please make sure both passwords are equal";
			$_SESSION['status'] = "400";
			header("Location: ../register.php");
		}

	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = "400";
		header("Location: ../register.php");
	}
}

if (isset($_POST['loginUserBtn'])) {
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);

	if (!empty($username) && !empty($password)) {

		$loginQuery = checkIfUserExists($pdo, $username);

		if ($loginQuery['status'] == '200') {
			$usernameFromDB = $loginQuery['userInfoArray']['username'];
			$passwordFromDB = $loginQuery['userInfoArray']['password'];

			if (password_verify($password, $passwordFromDB)) {
				$_SESSION['username'] = $usernameFromDB;
				header("Location: ../index.php");
			}
		}

		else {
			$_SESSION['message'] = $loginQuery['message'];
			$_SESSION['status'] = $loginQuery['status'];
			header("Location: ../login.php");
		}
	}

	else {
		$_SESSION['message'] = "Please make sure no input fields are empty";
		$_SESSION['status'] = "400";
		header("Location: ../login.php");
	}
}

if (isset($_GET['logoutUserBtn'])) {
	unset($_SESSION['username']);
	header("Location: ../login.php");
}

?>