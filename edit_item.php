<?php 



$page_title = 'Edit_Item';

// Check for a valid movie ID, through GET or POST.
if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // Accessed through view_moives.php
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
	
	
	if (empty($_POST['Issuer_ID'])) {
		$errors[] = 'You forgot to enter the issuer.';
	} else {
		$issuer_id = $_POST['Issuer_ID'];
	}
	
	
	
	
	
	if (empty($_POST['Item_Dec'])) {
		$errors[] = 'You forgot to item dec.';
	} else {
		$item_dec = $_POST['Item_Dec'];
	}
	
	
	
			
	if (empty($_POST['Item_Name'])) {
		$errors[] = 'You forgot to item name.';
	} else {
		$item_name = $_POST['Item_Name'];
	}
	
	
	   	if (empty($_POST['Unit_Price'])) {
		$errors[] = 'You forgot to enter the price of each unit.';
	} else {
		
		if (!preg_match('/^[0-9]+(\.[0-9]{1,2})?$/', $_POST['Unit_Price']))
		{
				$errors[] = 'Unit price must 2 deciaml number or integer';		
		}	
		else {
	$unit_price = $_POST['Unit_Price'];}
  	}

	
	
	if (empty($errors)) { // If everything's OK.
	
	
			// Make the query.
			$query = "UPDATE items SET Issuer_ID='$issuer_id', Item_Dec ='$item_dec',  Item_Name= '$item_name', Unit_Price = '$unit_price'  WHERE Item_ID = $id";
			$result = @mysqli_query ($dbc, $query); // Run the query.
			if ((mysqli_affected_rows($dbc) == 1) || (mysqli_affected_rows($dbc) == 0)) { // If it ran OK.
			
				// Print a message.
				echo '<h1 id="mainhead">Edit a Item</h1>
				<p>The itemr record has been edited.</p><p><br /><br /></p>';	
							
			} else { // If it did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The item could not be edited due to a system error. We apologize for any inconvenience.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
				exit();
			}
				
	} // End of if (empty($errors)) IF.
	
	else { // Report the errors.
	
		echo '<h1 id="mainhead">Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		} // End of foreach
		echo '</p><p>Please try again.</p><p><br /></p>';
		
	}  // End of report errors else()

} // End of submit conditional.



// Always show the form.

// Retrieve the item's information.
$query = "SELECT Item_ID, Item_Name, Item_Dec, items.Issuer_ID, Issuer_Name, Unit_Price  FROM items
JOIN prescription_issuers ON prescription_issuers.Issuer_ID = items.Issuer_ID where Item_ID = $id ";		
$result = @mysqli_query ($dbc, $query); // Run the query.

if (mysqli_num_rows($result) == 1) { // Valid movie ID, show the form.

	// Get the movie's information.
	$row = mysqli_fetch_array ($result, MYSQL_NUM);
	
	$this_issuer_id=$row[3];
	// Create the form.


echo '<h2>Edit a Item</h2>

<form action="edit_item.php" method="post">
<p>Item_Name: <input type="text" name="Item_Name" size="20" maxlength="40" value="' . $row[1] . '"  /> </p>';

echo '<p>Issuer_ID: <select name="Issuer_ID">';
// Build the query for director drop-down
$query = "SELECT Issuer_ID, Issuer_Name FROM prescription_issuers";
$result = @mysqli_query ($dbc, $query);

while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
{
	if ($row['Issuer_ID'] == $this_issuer_id) 
	{
		echo '<option value="'.$row['Issuer_ID'].'" selected="selected">' . 	$row['Issuer_Name'] . '</option>';
	}
	else 
	{
		echo '<option value="'.$row['Issuer_ID'].'">'. $row['Issuer_Name'] . '</option>';
	}   
}
echo '</select> </p>';



$query = "SELECT Item_ID, Item_Name, Item_Dec, Issuer_ID, Unit_Price FROM items WHERE Item_ID = $id ";		
$result = @mysqli_query ($dbc, $query); // Run the query.
	$row = mysqli_fetch_array ($result, MYSQL_NUM);
echo '

<p>Item_Dec: <input type="text" name="Item_Dec" size="60" maxlength="60" value="' . $row[2] . '"  /> </p>
<p>Unit_Price: <input type="text" name="Unit_Price" size="10" maxlength="10" value="' . $row[4] . '"  /></p>
<input type="hidden" name="submitted" value="TRUE" />
<input type="hidden" name="id" value="' . $id . '" />
<p><input type="submit" name="submit" value="Submit" /></p>
</form>


';

} else { // Not a valid movie ID.
	echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error. Not a valid Order ID.</p><p><br /><br /></p>';
}
echo '<a href="add_item.php">Add a item</a>&nbsp;&nbsp;&nbsp;
<a href="view_items.php">Go back to item list</a>&nbsp;&nbsp;&nbsp;<a href="index.php">Go back to the Main Menu</a>';
mysqli_close($dbc); // Close the database connection.
		
?>