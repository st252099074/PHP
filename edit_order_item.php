<?php

$page_title = 'Edit_Order_Item';

// Check for a valid act. ID, through GET or POST.
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // Accessed through view_ s.php
	$id = $_GET['id'];
} elseif ( (isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form has been submitted.
	$id = $_POST['id'];
} else { // No valid ID, kill the script.
	echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error.</p><p><br /><br /></p>';
	exit();
}

include ('mysqli_connect.php');

// Connect to the db.

// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

	$errors = array(); // Initialize error array.
	
		// Check for a movie ID.
	if (empty($_POST['Order_ID'])) {
		$errors[] = 'You forgot to enter the order.';
	} else {
		$order_id = $_POST['Order_ID'];
	}
	
	if (empty($_POST['Item_ID'])) {
		$errors[] = 'You forgot to enter the item.';
	} else {
		$item_id = $_POST['Item_ID'];
	}
	

	
	   	if (empty($_POST['Quantity'])) {
		$errors[] = 'You forgot to enter Quantity.';
	} else {
		
		if(preg_match('/^[0-9]+$/', $_POST['Quantity']))
		{ $quantity = $_POST['Quantity'];
						
		}	
		else {$errors[] = 'Quantity must be interger';
	}
  	}
	
	
	
	
	
	
	if (empty($errors)) { // If everything's OK.
	
	
			// Make the query.
			$query = "UPDATE order_items SET Order_ID='$order_id', Item_ID='$item_id', Quantity='$quantity' WHERE ID = $id";
			$result = @mysqli_query ($dbc, $query); // Run the query.
			if ((mysqli_affected_rows($dbc) == 1) || (mysqli_affected_rows($dbc) == 0)) { // If it ran OK.
			
				// Print a message.
				echo '<h1 id="mainhead">Edit a order item</h1>
				<p>The order item record has been edited.</p><p><br /><br /></p>';	
							
			} else { // If it did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The order item could not be edited due to a system error. We apologize for any inconvenience.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
				exit();
			}
				
	} else { // Report the errors.
	
		echo '<h1 id="mainhead">Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';
		
	} // End of if (empty($errors)) IF.

} // End of submit conditional.

// Always show the form.


// Retrieve the  's information.
$query = "SELECT order_items.Order_ID, order_items.Item_ID, Item_Name, Quantity, Unit_Price, ID FROM order_items 
JOIN prescription_orders ON prescription_orders.Order_ID = order_items.Order_ID
JOIN items ON items.Item_ID = order_items.Item_ID Where ID = $id";
$result = @mysqli_query ($dbc, $query); // Run the query.

if (mysqli_num_rows($result) == 1) { // Valid   ID, show the form.

	// Get the  's information.
	$row = mysqli_fetch_array ($result, MYSQL_NUM);
	$this_order_id=$row[0];
	$this_item_id=$row[1];
	// Create the form.
	echo '<h2>Edit a Order Item</h2>
<form action="edit_order_item.php" method="post">
<p>Order: <select name="Order_ID">';

// Build the query
$query = "SELECT Order_ID FROM prescription_orders";
$result = @mysqli_query ($dbc, $query);
while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
{

if ($row['Order_ID'] == $this_order_id) {
echo '<option value="'.$row['Order_ID'].'" selected="selected">'.$row['Order_ID'].'</option>';
}
 else {
echo '<option value="'.$row['Order_ID'].'">'.$row['Order_ID'].'</option>';
}


}


echo '</select>
</p>

<p>Items: <select name="Item_ID">';

// Build the query
$query = "SELECT Item_ID, Item_Name, Unit_Price FROM items";
$result = @mysqli_query ($dbc, $query);
while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
{

if ($row['Item'] == $this_item_id) {
echo '<option value="'.$row['Item_ID'].'" selected="selected">'.$row['Item_Name'].'</option>';
}
 else {
echo '<option value="'.$row['Item_ID'].'">'.$row['Item_Name'].'</option>';
}


}



echo '</select>
</p>
';

$query = "SELECT Order_ID, Quantity, Item_ID, ID  FROM order_items WHERE ID = $id";		
$result = @mysqli_query ($dbc, $query); // Run the query.
$row = mysqli_fetch_array($result, MYSQL_ASSOC);

echo '
<p>Quantity: <input type="text" name="Quantity" size="15" maxlength="15" value="' . $row['Quantity'] . '" /></p>
<input type="hidden" name="submitted" value="TRUE" />
<input type="hidden" name="id" value="' . $id . '" />
<p><input type="submit" name="submit" value="Submit" /></p>
</form>

';

} else { // Not a valid   ID.
	echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error. Not a valid   ID.</p><p><br /><br /></p>';
}
echo '<a href="view_order_items.php?id=' .$this_order_id. '">Get back to the order</a>&nbsp;&nbsp;&nbsp;
<a href="view_orders.php">Go back to Orders</a>&nbsp;&nbsp;&nbsp;<a href="index.php">Go back to the Main Menu</a>';
mysqli_close($dbc); // Close the database connection.
		
?>