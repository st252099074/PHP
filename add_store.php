<?php # add_store.php

$page_title = 'Add Store';

include ('mysqli_connect.php');

// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

	$errors = array(); // Initialize error array.

	// Check for a name.
	if (empty($_POST['Store_Name'])) {
		$errors[] = 'You forgot to enter the name of the store.';
	} else {
		$store_name = $_POST['Store_Name'];
	}

	// Check for a last name.
	if (empty($_POST['Store_Phone'])) {
		$errors[] = 'You forgot to enter the last phone of the store.';
	} else {
		
			if (!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $_POST['Store_Phone']))
		{
				$errors[] = 'check phone format: 000-000-0000';		
		}	
		else {
		$store_phone = $_POST['Store_Phone'];
	         }
	       }
   
	   	if (empty($_POST['Store_Address'])) {
		$errors[] = 'You forgot to enter the place of store.';
	} else {
		$store_address = $_POST['Store_Address'];
   }
	
		   	if (empty($_POST['Store_City'])) {
		$errors[] = 'You forgot to enter the city.';
	} else {
    
			$store_city = $_POST['Store_City'];
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z]*$/",$store_city )) {
         	$errors[] = 'only letters allowed for city';
	}
       else {$store_city = $_POST['Store_City'];
	     
		 }
    
   }
	
			   	if (empty($_POST['Zip_Code'])) {
		$errors[] = 'You forgot to enter the zip code.';
	} else {
		$zip_code = $_POST['Zip_Code'];
			if (!preg_match('#[0-9]{5}#', $_POST['Zip_Code']))
		{
				$errors[] = 'check zip code format: 00000';		
		}	
		else {
		$zip_code = $_POST['Zip_Code'];
	         }
	}
	
   
   
	if (empty($_POST['State_ID'])) {
		$errors[] = 'You forgot to enter the State.';
	} else {
		$state_id = $_POST['State_ID'];

// Build the query
$query = "SELECT * FROM state WHERE State_ID=$state_id ORDER BY State_Name ASC";
$result = @mysqli_query ($dbc, $query);
while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
{
$state_name=$row['State_Name'];
}
	}
   
   
	
	if (empty($errors)) { // If everything's okay.
	
	     //check if the store exits
	    $query = "SELECT Store_Name  
		FROM store where Store_Name = '$store_name'";		
		$result = @mysqli_query ($dbc, $query); // Run the query.
	    if ($result -> num_rows == '0') {
	
		// Add to the database.
		
		// Make the query.
		$query = "INSERT INTO store (Store_Name, Store_Phone, Store_Address, Store_City, ZipCode, State_ID) VALUES 
		('$store_name', '$store_phone', '$store_address', '$store_city', '$zip_code', '$state_id')";		
		$result = @mysqli_query ($dbc, $query); // Run the query.
		if ($result) { // If it ran OK.
		
			// Print a message.
			echo '<h1 id="mainhead">Success!</h1>
		<p>You have added to the Store table:</p>';

		   echo "<table>
		<tr><td>Store Name:</td><td>{$store_name}</td></tr>
		<tr><td>Store_Phone:</td><td>{$store_phone}</td></tr>
		<tr><td>Store_Address:</td><td>{$store_address}</td></tr>
		<tr><td>Store_City:</td><td>{$store_city}</td></tr>
		<tr><td>Zip Code:</td><td>{$zip_code}</td></tr>
		<tr><td>State_ID:</td><td>{$state_id}</td></tr>
        <tr><td><button> <a href = 'add_store.php'>add another store</a></button></td></tr>
		
		</table>
<br />
<a href=\"add_order.php\">Go to Adding a Order</a>&nbsp;&nbsp;&nbsp;</a>&nbsp;&nbsp;&nbsp;<a href=\"index.php\">Go back to the Main Menu</a>";	
		
			exit();
			
		} else { // If it did not run OK.
			echo '<h1 id="mainhead">System Error</h1>
			<p class="error">The store could not be added due to a system error. We apologize for any inconvenience.</p>'; // Public message.
			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
			exit();
		}}
		
		else  {echo "<strong>This store already exits</strong><br /><a href=\"index.php\">Go back to the Main Menu</a>";}
		
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
 if (isset($_POST['State_ID'])) $this_state_id=$_POST['State_ID'];

?>


<h2>Add a Store</h2>
<form action="add_store.php" method="post">
	<p>Store Name: <input type="text" name="Store_Name" size="30" maxlength="30" value="<?php if (isset($_POST['Store_Name'])) echo $_POST['Store_Name']; ?>" /></p>
	<p>Store Phone: <input type="text" name="Store_Phone" size="30" maxlength="30" value="<?php if (isset($_POST['Store_Phone'])) echo $_POST['Store_Phone']; ?>" /> &nbsp;<i>phone format: 000-000-0000</i></p>
	<p>Store Address: <input type="text" name="Store_Address" size="60" maxlength="60" value="<?php if (isset($_POST['Store_Address'])) echo $_POST['Store_Address']; ?>" /></p>
	<p>Store City: <input type="text" name="Store_City" size="15" maxlength="15" value="<?php if (isset($_POST['Store_City'])) echo $_POST['Store_City']; ?>" /></p>
    <p>Zip_Code: <input type="text" name="Zip_Code" size="30" maxlength="30" value="<?php if (isset($_POST['Zip_Code'])) echo $_POST['Zip_Code']; ?>" /></p>
	<p>State <select name="State_ID">
<?php 
// Build the query
$query = "SELECT * FROM state ORDER BY State_Name ASC";
$result = @mysqli_query ($dbc, $query);
while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
{

if ($row['State_ID'] == $this_state_id) {
echo '<option value="'.$row['State_ID'].'" selected="selected">'.$row['State_Name'].'</option>';
}
 else {
echo'<option value="'.$row['State_ID'].'">'.$row['State_Name'].'</option>';}

}
?>
</p>
	
	<br />
	
	<p><input type="submit" name="submit" value="Add Store" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
</form>
<br />
<p><a href = 'index.php'>Get back to Main Page</a></p>
<?php mysqli_close($dbc); // Close the database connection. ?>