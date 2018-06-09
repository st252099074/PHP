<?php # add_store.php

$page_title = 'Add Store';

include ('mysqli_connect.php');

// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

	$errors = array(); // Initialize error array.

	// Check for a name.
	
		if (empty($_POST['Item_ID'])) {
		$errors[] = 'You forgot to enter the id of the item.';
	} else
{
		
	




	
		if (preg_match('/^[0-9]+$/', $_POST['Item_ID'])){
			
			if ($_POST['Item_ID'] < 10000  || $_POST['Item_ID']>99999) {
			
		$errors[] = 'the Interger must between 10000 and 99999.';
		}
			
		else{  $item_id = $_POST['Item_ID'];}
				
			
}
else {
			
			
		$errors[] = 'the input must be an interger.';}



	}
	

	
	
	
	
	if (empty($_POST['Item_Dec'])) {
		$errors[] = 'You forgot to enter the dec of the item.';
	} else {
		$item_dec = $_POST['Item_Dec'];
	}

	// Check for a last name.
	if (empty($_POST['Item_Name'])) {
		$errors[] = 'You forgot to enter the name of the item.';
	} else {
		$item_name = $_POST['Item_Name'];
	}
	
   //if (!preg_match('/^[0-9]+(\.[0-9]{1,2})?$/', "100"))
	   	if (empty($_POST['Unit_Price'])) {
		$errors[] = 'You forgot to enter the price of each unit.';
	} else {
		
		if (!preg_match('/^(?:0|[1-9]\d*)(?:\.\d{2})?$/', $_POST['Unit_Price']))
		{
				$errors[] = 'Unit price must be 2 decimal number or integer';		
		}	
		else {
	$unit_price = $_POST['Unit_Price'];}
  	}

   
	if (empty($_POST['Issuer_ID'])) {
		$errors[] = 'You forgot to enter the Issuer.';
	} else {
		$issuer_id = $_POST['Issuer_ID'];

// Build the query
$query = "SELECT * FROM prescription_issuers WHERE Issuer_ID=$issuer_id ORDER BY Issuer_Name ASC";
$result = @mysqli_query ($dbc, $query);
while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
{
$issuer_name=$row['Issuer_Name'];
}
	}
   
   
	
	if (empty($errors)) { // If everything's okay.
	
	    //check if item exists
	    $query = "SELECT Item_ID FROM items WHERE Item_ID = '$item_id'";		
		$result = @mysqli_query ($dbc, $query); // Run the query.
	    	if ($result -> num_rows == '0') {
		// Add the item to the database.
		
		// Make the query.
		$query = "INSERT INTO items (Item_ID, Item_Dec, Item_Name, Unit_Price, Issuer_ID) VALUES 
		('$item_id', '$item_dec', '$item_name', '$unit_price', '$issuer_id')";		
		$result = @mysqli_query ($dbc, $query); // Run the query.
		if ($result) { // If it ran OK.
		
		// Print a message.
			echo '<h1 id="mainhead">Success!</h1>
		<p>You have added to the item table:</p>';

		   echo "<table>
		<tr><td>Item_ID:</td><td>{$item_id}</td></tr>
		<tr><td>Item_Dec:</td><td>{$item_dec}</td></tr>
		<tr><td>Item_Name:</td><td>{$item_name}</td></tr>
		<tr><td>Unit_Price:</td><td>{$unit_price}</td></tr>
		<tr><td>Issuer_ID:</td><td>{$issuer_id}</td></tr>
		</table>
        <br /><a href=\"add_item.php\">Add another item</a>&nbsp;&nbsp;&nbsp;
        <a href=\"view_items.php\">Go to view item list</a>&nbsp;&nbsp;&nbsp;<a href=\"index.php\">Go back to the Main Menu</a>";	
		
			exit();
			
		} 
		else { // If it did not run OK.
			echo '<h1 id="mainhead">System Error</h1>
			<p class="error">The customer could not be added due to a system error. We apologize for any inconvenience.</p>'; // Public message.
			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
			exit();
			}}
			echo "<strong>This item already exits</strong><br />
			<a href=\"view_items.php\">Go to view item list</a>";
		
	} else { // Report the errors.
	
		echo '<h1 id="mainhead">Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';
		
	} // End of if (empty($errors)) IF.


	
		
} // End of the main Submit conditional.
?>






<?php 
 if (isset($_POST['Issuer_ID'])) $this_issuer_id=$_POST['Issuer_ID'];

?>


<h2>Add an Item</h2>
<form action="add_item.php" method="post">
<p>Item ID: <input type="text" name="Item_ID" size="10" maxlength="10" value="<?php if (isset($_POST['Item_ID'])) echo $_POST['Item_ID']; ?>" /></p>&nbsp;<i>only 5 degits integer, ex:10000</i></p>
	<p>Item Description: <input type="text" name="Item_Dec" size="100" maxlength="100" value="<?php if (isset($_POST['Item_Dec'])) echo $_POST['Item_Dec']; ?>" /></p>
	<p>Item Name: <input type="text" name="Item_Name" size="30" maxlength="30" value="<?php if (isset($_POST['Item_Name'])) echo $_POST['Item_Name']; ?>" /></p>
	<p>Unit Price: <input type="text" name="Unit_Price" size="15" maxlength="15" value="<?php if (isset($_POST['Unit_Price'])) echo $_POST['Unit_Price']; ?>" /></p>&nbsp;<i>only number, ex:99.99</i></p>
	<p>Issuer <select name="Issuer_ID">
<?php 
// Build the query
$query = "SELECT * FROM prescription_issuers ORDER BY Issuer_Name ASC";
$result = @mysqli_query ($dbc, $query);
while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
{

if ($row['Issuer_ID'] == $this_issuer_name) {
echo '<option value="'.$row['Issuer_ID'].'" selected="selected">'.$row['Issuer_Name'].'</option>';
}
 else {
echo'<option value="'.$row['Issuer_ID'].'">'.$row['Issuer_Name'].'</option>';}

}
?>
</p>
	
	<br />
	
	<p><input type="submit" name="submit" value="Add Item" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
</form>
<br />
<a href="index.php">Go back to Main Page</a>

<?php mysqli_close($dbc); // Close the database connection. ?>








