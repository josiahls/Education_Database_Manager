<?php
/**
 * Created by IntelliJ IDEA.
 * User: Josiah
 * Date: 10/14/2016
 * Time: 11:44 AM
 *
 * TODO do this thing
 *
 *
 */

$host="localhost"; // Establishing the host

$root="root"; // Establishing the DB root user and the password
$root_password="rootpass";

$user='root'; // Establishing the user and their login password
$pass='rootpass';
$db="EducationDB"; // Name of the database

$conn = new PDO("mysql:host=$host", $root, $root_password); // New PDO connection

// Create Database
try { // Try catch for if the database already exists


    // Create the database
    $conn->exec("CREATE DATABASE `$db`;
                CREATE USER '$user'@'localhost' IDENTIFIED BY '$pass';
                GRANT ALL ON `$db`.* TO '$user'@'localhost';
                FLUSH PRIVILEGES;"); // Creates Database and also creates a user with all privileges
    echo "Success - Database Created"; // Should display if command succeeds
} catch (PDOException $e) { // If there is an issue, or the database is already created
    echo "Database already exists or there was an error " . $conn->errorInfo(); // Display error
}

// Create Person Table
try{
    //$sql = "CREATE TABLE PERSON "; // TODO add sql code Josiah will code the person Table

    //$stmpt = $conn->exec($sql);

    echo "Success - Table Created PERSON";
}
catch(PDOException $e){
    echo "Table already exists or there was an error " . $conn->errorInfo(); // Display error
}


