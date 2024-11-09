<?php  

function checkIfUserExists($pdo, $username) {
	$response = array();
	$sql = "SELECT * FROM user_accounts WHERE username = ?";
	$stmt = $pdo->prepare($sql);

	if ($stmt->execute([$username])) {

		$userInfoArray = $stmt->fetch();

		if ($stmt->rowCount() > 0) {
			$response = array(
				"result"=> true,
				"status" => "200",
				"userInfoArray" => $userInfoArray
			);
		}

		else {
			$response = array(
				"status" => "400",
				"message"=> "User doesn't exist from the database"
			);
		}
	}

	return $response;

}

function insertNewUser($pdo, $username, $first_name, $last_name, $password) {
	$response = array();
	$checkIfUserExists = checkIfUserExists($pdo, $username); 

	if (!$checkIfUserExists['result']) {

		$sql = "INSERT INTO user_accounts (username, first_name, last_name, password) 
		VALUES (?,?,?,?)";

		$stmt = $pdo->prepare($sql);

		if ($stmt->execute([$username, $first_name, $last_name, $password])) {
			$response = array(
				"status" => "200",
				"message" => "User successfully inserted!"
			);
		}

		else {
			$response = array(
				"status" => "400",
				"message" => "An error occured with the query!"
			);
		}
	}

	else {
		$response = array(
			"status" => "400",
			"message" => "User already exists!"
		);
	}

	return $response;
}


function insertCustomer($pdo, $table_num, $cus_name, $cus_order, 
	$quantity, $amount_of_money) {

	$sql = "INSERT INTO customer (table_num, cus_name, cus_order, 
		quantity, amount_of_money) VALUES(?,?,?,?,?)";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$table_num, $cus_name, $cus_order, 
		$quantity, $amount_of_money]);

	if ($executeQuery) {
		return true;
	}
}



function updateCustomer($pdo, $cus_name, $cus_order, 
	$quantity, $amount_of_money, $customer_id) {

	$sql = "UPDATE customer
				SET cus_name = ?,
					cus_order = ?,
					quantity = ?, 
					amount_of_money = ?
				WHERE customer_id = ?
			";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$cus_name, $cus_order, 
		$quantity, $amount_of_money, $customer_id]);
	
	if ($executeQuery) {
		return true;
	}

}


function deleteCustomer($pdo, $customer_id) {
	$deleteCustomerOrder = "DELETE FROM orders WHERE customer_id = ?";
	$deleteStmt = $pdo->prepare($deleteCustomerOrder);
	$executeDeleteQuery = $deleteStmt->execute([$customer_id]);

	if ($executeDeleteQuery) {
		$sql = "DELETE FROM customer WHERE customer_id = ?";
		$stmt = $pdo->prepare($sql);
		$executeQuery = $stmt->execute([$customer_id]);

		if ($executeQuery) {
			return true;
		}

	}
	
}




function getAllCustomer($pdo) {
	$sql = "SELECT * FROM customer";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute();

	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}

function getCustomerByID($pdo, $customer_id) {
	$sql = "SELECT * FROM customer WHERE customer_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$customer_id]);

	if ($executeQuery) {
		return $stmt->fetch();
	}
}


function getAllInfoByCustomerID($customer_id) {
    require 'dbConfig.php'; 

    try {
        $stmt = $pdo->prepare("SELECT * FROM customer WHERE customer_id = :customer_id");
        $stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ? $result : []; // Return an empty array if no result
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return [];
    }
}










function getOrderByCustomer($pdo, $customer_id) {
	
	$sql = "SELECT 
				orders.customer_id as customer_id,
				orders.order_id AS order_id,
				orders.order_name AS order_name,
				orders.price AS price,
				orders.date_added AS date_added,
				CONCAT(customer.cus_name,' ',customer.cus_order) AS Customer_ORDER
			FROM orders
			JOIN customer ON orders.customer_id = orders.customer_id
			WHERE orders.customer_id = ? 
			GROUP BY orders.order_name;
			";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$customer_id]);
	if ($executeQuery) {
		return $stmt->fetchAll();
	}
}


function insertOrder($pdo, $order_name, $price, $customer_id) {
	$sql = "INSERT INTO orders (order_name, price, customer_id) VALUES (?,?,?)";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$order_name, $price, $customer_id]);
	if ($executeQuery) {
		return true;
	}

}

function getOrderByID($pdo, $order_id) {
	$sql = "SELECT 
				orders.order_id AS order_id,
				orders.order_name AS order_name,
				orders.price AS price,
				orders.date_added AS date_added,
				CONCAT(customer.cus_name,' ',customer.cus_order) AS Customer
			FROM orders
			JOIN customer ON orders.customer_id = customer.customer_id
			WHERE orders.order_id  = ? 
			GROUP BY orders.order_name";

	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$order_id]);
	if ($executeQuery) {
		return $stmt->fetch();
	}
}

function updateOrder() {
	$sql = "UPDATE orders
			SET 
				
				cus_order = ?,
				quantity = ?,
				amount_of_money = ?
			WHERE customer_id = ?
			";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$order_id]);

	if ($executeQuery) {
		return true;
	}
}

function deleteOrder($pdo, $order_id) {
	$sql = "DELETE FROM orders WHERE order_id = ?";
	$stmt = $pdo->prepare($sql);
	$executeQuery = $stmt->execute([$order_id]);
	if ($executeQuery) {
		return true;
	}
}


?>