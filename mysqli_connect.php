<?php

// Set the database access information as constants.
//DEFINE ('DB_USER', 'root');
//DEFINE ('DB_PASSWORD', 'tsukYsd111');
//DEFINE ('DB_HOST', 'localhost');
//DEFINE ('DB_NAME', 'cvs');

// Make the connnection, select the database and assign the connection script the $dbc.
$dbc = @mysqli_connect ('localhost', 'st252099_cvs', 'tsukYsd111', 'st252099_cvs') OR die ('Could not connect to MySQL: ' . mysqli_connect_error() );

?>
