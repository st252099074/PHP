<?php # add_customer.php

$page_title = 'Add Customer';

include ('mysqli_connect.php');

// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

	$errors = array(); // Initialize error array.

	// Check for a first name.
	if (empty($_POST['First_N'])) {
		$errors[] = 'You forgot to enter the first name of the customer.';
	} else {

		$first_name = $_POST['First_N'];
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z]*$/",$first_name)) {
         	$errors[] = 'only letters allowed for first name';
	}
       else {$first_name = $_POST['First_N'];
	        }
	}
	
	
	
	// Check for a last name.
	if (empty($_POST['Last_N'])) {
		$errors[] = 'You forgot to enter the last name of the customer.';
	} else {
	
		
				$last_name = $_POST['Last_N'];
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z]*$/",$last_name)) {
         	$errors[] = 'only letters allowed for last name';
	}
       else {$last_name = $_POST['Last_N'];
	        }
		
	}
	
	// Check for the date of birth.
	if (empty($_POST['Date_of_Birth'])) {
		$errors[] = 'You forgot to enter the date of birth.';
	} else { // Check for the date of birth.
			function validateDate($date, $format = 'Y-m-d H:i:s') {
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}
	$date_of_birth = $_POST['Date_of_Birth'];
	
	if (validateDate($date_of_birth, 'Y-m-d') == true){
	$date_of_birth = $_POST['Date_of_Birth'];
		
	} else {
		$errors[] = 'Date of birth is invalid.';
	}
		
           }
	
	if (empty($_POST['Email'])) {
		$errors[] = 'You forgot to enter the place of birth.';
	} else {
		
		$email = $_POST['Email'];
    // check if e-mail address is well-formed
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      	$errors[] = 'email invalid.';
    }
		else{
		$email = $_POST['Email'];
           }

  }
   
   
   
  
	
		if (empty($_POST['Gender'])) {
		$errors[] = 'You forgot to enter the gender.';
	} else {
		
			if ($_POST['Gender'] != 'M' &&
	$_POST['Gender'] != 'F') {
		$errors[] = 'You must enter capital "M" or "F" for Gender.';
	} else {
		$gender = $_POST['Gender'];
	}
		

	}
	

	
	
	   	if (empty($_POST['Street_Address'])) {
		$errors[] = 'You forgot to enter the place of birth.';
	} else {
		$street_address = $_POST['Street_Address'];
   }
	
		   	if (empty($_POST['City'])) {
		$errors[] = 'You forgot to enter the city.';
	} else {
		
		
		
		$city = $_POST['City'];
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z]*$/",$city)) {
         	$errors[] = 'only letters allowed for city';
	}
       else {$city = $_POST['City'];
	     
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
	
   
   
   			   	if (empty($_POST['Primary_Phone'])) {
		$errors[] = 'You forgot to enter the phone number.';
	} else {
		
			if (!preg_match("/^[0-9]{3}-[0-9]{3}-[0-9]{4}$/", $_POST['Primary_Phone']))
		{
				$errors[] = 'check phone format: 000-000-0000';		
		}	
		else {
		$primary_phone = $_POST['Primary_Phone'];
	         }
 


 }
   
   
   	// Check for  ID.
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
	
	     //check if the customer exists
		 
	    $query = "SELECT First_N, Last_N, Date_of_Birth   
		FROM customers where (First_N = '$first_name' && Last_N = '$last_name' && Date_of_Birth = '$date_of_birth' )";		
		$result = @mysqli_query ($dbc, $query); // Run the query.
	    	if ($result -> num_rows == '0') {
	
	
		// Add  the database.
		
		// Make the query.
		$query = "INSERT INTO customers (First_N, Last_N, Date_of_Birth, Gender, Email, Street_Address, City, Zip_Code, State_ID, Primary_Phone) VALUES 
		('$first_name', '$last_name', '$date_of_birth', '$gender', '$email','$street_address', '$city', '$zip_code', '$state_id', '$primary_phone' )";		
		$result = @mysqli_query ($dbc, $query); // Run the query.
		if ($result) { // If it ran OK.
		
			// Print a message.
			echo '<h1 id="mainhead">Success!</h1>
		<p>You have added to the Customers table:</p>';

		   echo "<table>
		<tr><td>First Name:</td><td>{$first_name}</td></tr>
		<tr><td>Last Name:</td><td>{$last_name}</td></tr>
		<tr><td>Date of Birth:</td><td>{$date_of_birth}</td></tr>
		<tr><td>Gender:</td><td>{$gender}</td></tr>
		<tr><td>Address:</td><td>{$street_address}</td></tr>
		<tr><td>City:</td><td>{$city}</td></tr>
		<tr><td>Zip_Code:</td><td>{$zip_code}</td></tr>
		<tr><td>State_ID:</td><td>{$state_id}</td></tr>
		<tr><td>Primary_Phone:</td><td>{$primary_phone}</td></tr>
		
		</table>
<br /><a href=\"add_customer.php\">Add another customer</a>&nbsp;&nbsp;&nbsp;
<a href=\"view_customers.php\">Go back to customer List</a>&nbsp;&nbsp;&nbsp;<a href=\"index.php\">Go back to the Main Menu</a>";	
		
			exit();
			
		} else { // If it did not run OK.
			echo '<h1 id="mainhead">System Error</h1>
			<p class="error">The customer could not be added due to a system error. We apologize for any inconvenience.</p>'; // Public message.
			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
			exit();
			}}
		else {
			
	        echo "<strong>This customer already exits</strong><br /><a href=\"view_customers.php\">
			Go back to customer List</a>";}
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


<h2>Add Customer</h2>
<form action="add_customer.php" method="post">
	<p>First Name: <input type="text" name="First_N" size="15" maxlength="15" value="<?php if (isset($_POST['First_N'])) echo $_POST['First_N']; ?>" /></p>
	<p>Last Name: <input type="text" name="Last_N" size="30" maxlength="30" value="<?php if (isset($_POST['Last_N'])) echo $_POST['Last_N']; ?>" /></p>
	<p>Date of Birth: <input type="text" name="Date_of_Birth" size="10" maxlength="10" value="<?php if (isset($_POST['Date_of_Birth'])) echo $_POST['Date_of_Birth']; ?>" />&nbsp;<i>YYYY-MM-DD</i></p>
	<p>Gender: <input type="text" name="Gender" size="2" maxlength="2" value="<?php if (isset($_POST['Gender'])) echo $_POST['Gender']; ?>" /></p>&nbsp;<i>F/M</i>
	<p>Email: <input type="text" name="Email" size="30" maxlength="30" value="<?php if (isset($_POST['Email'])) echo $_POST['Email']; ?>" /></p>
		<p>Phone number: <input type="text" name="Primary_Phone" size="30" maxlength="30" value="<?php if (isset($_POST['Primary_Phone'])) echo $_POST['Primary_Phone']; ?>" />&nbsp;<i>000-000-0000</i></p>
	<p>Address: <input type="text" name="Street_Address" size="30" maxlength="30" value="<?php if (isset($_POST['Street_Address'])) echo $_POST['Street_Address']; ?>" /></p>
	<p>City: <input type="text" name="City" size="30" maxlength="30" value="<?php if (isset($_POST['City'])) echo $_POST['City']; ?>" /></p>
	<p>Zip_Code: <input type="text" name="Zip_Code" size="30" maxlength="30" value="<?php if (isset($_POST['Zip_Code'])) echo $_POST['Zip_Code']; ?>" />&nbsp;<i>000000</i></p>
    <p>State: <select name="State_ID">
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
	<p><input type="submit" name="submit" value="Add Customer" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
</form>
<br />
<a href="index.php">Go back to the Main Page</a>
<?php mysqli_close($dbc); // Close the database connection. ?>