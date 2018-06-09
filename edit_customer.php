<?php # Script 8.4 - edit_customer.php



$page_title = 'Edit_Customer';


if ( (isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { // Accessed through viewW
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
	
		// Check for a first name.
	if (empty($_POST['First_N'])) {
		$errors[] = 'You forgot to enter the first name of the customer.';
	} else {$first_n = $_POST['First_N'];
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z]*$/",$first_n)) {
         	$errors[] = 'only letters allowed for first name';
	}
       else {$first_n = $_POST['First_N'];
	        }
		
	}
	
	// Check for last name
	if (empty($_POST['Last_N'])) {
		$errors[] = 'You forgot to enter the last name of the customer.';
	} else {	$last_n = $_POST['Last_N'];
    // check if name only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z]*$/",$last_n)) {
         	$errors[] = 'only letters allowed for last name';
	}
       else {$last_n = $_POST['Last_N'];
	        }
		
	}
	
	// Check for date
	if (empty($_POST['Date_of_Birth'])) {
		$errors[] = 'You forgot to enter the date of birth.';
	} else {// Check for the date of birth.
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
	
	// Check for email
	if (empty($_POST['Email'])) {
		$errors[] = 'You forgot to enter the email.';
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
	
	
		// Check for gender
	if (empty($_POST['Gender'])) {
		$errors[] = 'You forgot to enter the gender.';
	} else {
		
			if ($_POST['Gender'] != 'M' &&
	$_POST['Gender'] != 'F') {
		$errors[] = 'You must enter capitalized "M" or "F" for Gender.';
	} else {
		$gender = $_POST['Gender'];
	}
		
	}

	
		// Check for address
	if (empty($_POST['Street_Address'])) {
		$errors[] = 'You forgot to enter the address.';
	} else {
		$street_address = $_POST['Street_Address'];
	}
	
		// Check for city
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
	

	// Check for zip
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
	
	
	
	
	
		if (empty($_POST['State_ID'])) {
		$errors[] = 'You forgot to enter the state.';
	} else {
		$state_id = $_POST['State_ID'];
	}
	
	
	
	if (empty($errors)) { // If everything's OK.
	
	
			// Make the query.
			$query = "UPDATE customers SET First_N='$first_n', Last_N ='$last_n', Date_of_Birth='$date_of_birth', Email='$email', Gender = '$gender' , Street_Address = '$street_address', 
			City=  '$city' ,  Zip_Code = '$zip_code' , Primary_Phone = '$primary_phone', State_ID = '$state_id'  WHERE Customer_ID = $id";
			$result = @mysqli_query ($dbc, $query); // Run the query.
			if ((mysqli_affected_rows($dbc) == 1) || (mysqli_affected_rows($dbc) == 0)) { // If it ran OK.
			
				// Print a message.
				echo '<h1 id="mainhead">Edit a customer</h1>
				<p>The customer record has been edited.</p><p><br /><br /></p>';	
							
			} else { // If it did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The customer could not be edited due to a system error. We apologize for any inconvenience.</p>'; // Public message.
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

// Retrieve the information.
$query = "SELECT First_N, Last_N, Date_of_Birth, Email, Gender, Street_Address, City, Zip_Code, Primary_Phone, state.State_ID, state.State_Name FROM customers
JOIN state on state.State_ID  =  customers.State_ID   WHERE Customer_ID = $id";		
$result = @mysqli_query ($dbc, $query); // Run the query.

if (mysqli_num_rows($result) == 1) { 

	// Get the  information.
	$row = mysqli_fetch_array ($result, MYSQL_NUM);
	$this_state_id=$row[9];

	// Create the form.
	echo '<h2>Edit a customer</h2>
<form action="edit_customer.php" method="post">



<p>First Name: <input type="text" name="First_N" size="15" maxlength="15" value="' . $row[0] . '" /></p>
<p>Last Name: <input type="text" name="Last_N" size="15" maxlength="15" value="' . $row[1] . '" /></p>
<p>Date of Birth: <input type="text" name="Date_of_Birth" size="15" maxlength="15" value="' . $row[2] . '" /><i>&nbsp;&nbsp; Format: YYYY-MM-DD</i></p>
<p>Email: <input type="text" name="Email" size="30" maxlength="30" value="' . $row[3] . '" /></p>

<p>Gender: <input type="text" name="Gender" size="2" maxlength="2" value="' . $row[4] . '" /></p>
<p>Street_Address: <input type="text" name="Street_Address" size="30" maxlength="30" value="' . $row[5] . '" /></p>
<p>City: <input type="text" name="City" size="15" maxlength="15" value="' . $row[6] . '" /></p>
<p>Zip_Code: <input type="text" name="Zip_Code" size="10" maxlength="10" value="' . $row[7] . '" /><i>&nbsp;&nbsp;Format: 00000</i></p>
<p>Primary_Phone: <input type="text" name="Primary_Phone" size="15" maxlength="15" value="' . $row[8] . '" /><i>&nbsp;&nbsp; Format: 000-000-0000</i></p>';

echo '<p>State: <select name="State_ID">';
// Build the query for drop-down
$query = "SELECT State_ID, State_Name  FROM state";
$result = @mysqli_query ($dbc, $query);

while ($row = mysqli_fetch_array($result, MYSQL_ASSOC))
{
	if ($row['State_ID'] == $this_state_id) 
	{
		echo '<option value="'.$row['State_ID'].'" selected="selected">' . 	$row['State_Name'] . '</option>';
	}
	else 
	{
		echo '<option value="'.$row['State_ID'].'">'. $row['State_Name'] . '</option>';
	}   
}
echo '</select> </p>';

echo'

<input type="hidden" name="submitted" value="TRUE" />
<input type="hidden" name="id" value="' . $id . '" />
<p><input type="submit" name="submit" value="Submit" /></p>
</form>

';

} else { // Not a valid  ID.
	echo '<h1 id="mainhead">Page Error</h1>
	<p class="error">This page has been accessed in error. Not a valid  ID.</p><p><br /><br /></p>';
}
echo '<a href="add_customer.php">Add another customer</a>&nbsp;&nbsp;&nbsp;
<a href="view_customers.php">Go back to customer List</a>&nbsp;&nbsp;&nbsp;<a href="index.php">Go back to the Main Menu</a>';
mysqli_close($dbc); // Close the database connection.
		
?>