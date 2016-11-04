<?php
/**
 * Created by IntelliJ IDEA.
 * User: Josiah
 * Date: 10/14/2016
 * Time: 11:44 AM
 *
 */

$config = parse_ini_file(dirname(__FILE__).'/..'.'/..'.'/config/my.ini'); // Finds the my.ini file

$host=$config['host']; // Establishing the host

$root=$config['root'];; // Establishing the DB root user and the password
$root_password=$config['root_password'];;

$user=$config['user'];; // Establishing the user and their login password
$password=$config['password'];;
$db=$config['db'];; // Name of the database


try{
    $conn = new PDO("mysql:host=$host;dbname=$db", $root, $root_password); // New PDO connection

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "DROP DATABASE $db";


    $conn->exec($sql); // Try executing the sql statement
    echo "DB Drop Success";

}
catch(PDOException $e){
    echo "DB Drop Failed";
}




// Create Database
try { // Try catch for if the database already exists
    $conn = new PDO("mysql:host=$host", $root, $root_password); // New PDO connection
    // Create the database
    $conn->exec("CREATE DATABASE `$db`;
                CREATE USER '$user'@'localhost' IDENTIFIED BY '$password';
                GRANT ALL ON `$db`.* TO '$user'@'localhost';
                FLUSH PRIVILEGES;"); // Creates Database and also creates a user with all privileges

    $conn = new PDO("mysql:host=$host;dbname=$db", $root, $root_password); // New PDO connection

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Sets the connection to throw errors

    echo "\nSuccess - Database Created"; // Should display if command succeeds

} catch (PDOException $e) { // If there is an issue, or the database is already created


    echo "\nDatabase already exists or there was an error " . $e; // Display error
}

$conn = new PDO("mysql:host=$host;dbname=$db", $root, $root_password); // New PDO connection

$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create Person Table
try{
    $sql = "CREATE TABLE PERSON(
          id INTEGER PRIMARY KEY AUTO_INCREMENT,
          firstName VARCHAR(30),
          Minit VARCHAR(1),
          lastName VARCHAR(30),
          adress VARCHAR(30),
          city VARCHAR(15),
          state VARCHAR(30),
          zip VARCHAR(5),
          phone VARCHAR(13),
          email VARCHAR(45)
        ) ";

    $stmpt = $conn->exec($sql); // Try executing the sql statement

    echo "\nSuccess - Table Created PERSON"; // Displays if succeeded
}
catch(PDOException $e) {// If there is an error
    if($e->getCode() == "42S01") { // 42S01 is an error that is thrown when the table already exists
        echo "\nTable PERSON already exists"; // Display error
    }
    else {
        echo "\nFailed: There was an error in creating PERSON: " . $e; // Display error
    }
}





try{
    $sql = "CREATE TABLE EVALUATION(
          id INTEGER PRIMARY KEY AUTO_INCREMENT,
          answer_one VARCHAR(144),
          answer_two VARCHAR(144),
          answer_three VARCHAR(144)
        ) "; // TODO add sql code Josiah will code the person Table

    $stmpt = $conn->exec($sql);

    echo "\nSuccess - Table Created EVALUATION";
}
catch(PDOException $e) {
    if($e->getCode() == "42S01") {
        echo "\nTable EVALUATION already exists"; // Display error
    }
    else {
        echo "\nFailed: There was an error in creating EVALUATION: " . $e; // Display error
    }
}

try{
    $sql = "CREATE TABLE EVENT(
          Event_id INTEGER PRIMARY KEY AUTO_INCREMENT,
          name VARCHAR(30),
          description VARCHAR(144),
          length VARCHAR(30)
        ) ";

    $stmpt = $conn->exec($sql);

    echo "\nSuccess - Table Created EVENT";
}
catch(PDOException $e) {
    if($e->getCode() == "42S01") {
        echo "\nTable EVENT already exists"; // Display error
    }
    else {
        echo "\nFailed: There was an error in creating EVENT: " . $e; // Display error
    }
}

try{
    $sql = "CREATE TABLE REGISTERED(
          id INTEGER PRIMARY KEY AUTO_INCREMENT,
          attended BOOLEAN,
          date DATETIME
          
        ) "; // TODO add sql code Josiah will code the person Table

    $stmpt = $conn->exec($sql);

    echo "\nSuccess - Table Created REGISTERED";
}
catch(PDOException $e) {
    if($e->getCode() == "42S01") {
        echo "\nTable REGISTERED already exists"; // Display error
    }
    else {
        echo "\nFailed: There was an error in creating REGISTERED: " . $e; // Display error
    }
}

try{
    $sql = "CREATE TABLE STAFF(
          id INTEGER PRIMARY KEY AUTO_INCREMENT,
          posTitle VARCHAR(45)
          
          
        ) "; // TODO add sql code Josiah will code the person Table

    $stmpt = $conn->exec($sql);

    echo "\nSuccess - Table Created STAFF";
}
catch(PDOException $e) {
    if($e->getCode() == "42S01") {
        echo "\nTable STAFF already exists"; // Display error
    }
    else {
        echo "\nFailed: There was an error in creating STAFF: " . $e; // Display error
    }
}
// TODO test enum 'highestDegree' ENUM('BA', 'PHD', 'Masters', 'BS', 'BA') DEFAULT null
try{
    $sql = "CREATE TABLE FACULTY(
          id INTEGER PRIMARY KEY AUTO_INCREMENT,
          highestDegree ENUM('BA', 'PHD', 'Masters', 'BS', 'AS') DEFAULT null
          
          
        ) "; // TODO add sql code Josiah will code the person Table

    $stmpt = $conn->exec($sql);

    echo "\nSuccess - Table Created FACULTY";
}
catch(PDOException $e) {
    if($e->getCode() == "42S01") {
        echo "\nTable FACULTY already exists"; // Display error
    }
    else {
        echo "\nFailed: There was an error in creating FACULTY: " . $e; // Display error
    }
}

try{
    $sql = "CREATE TABLE STUDENT(
          id INTEGER PRIMARY KEY AUTO_INCREMENT,
          satScore DOUBLE
          
          
        ) "; // TODO add sql code Josiah will code the person Table

    $stmpt = $conn->exec($sql);

    echo "\nSuccess - Table Created STUDENT";
}
catch(PDOException $e) {
    if($e->getCode() == "42S01") {
        echo "\nTable STUDENT already exists"; // Display error
    }
    else {
        echo "\nFailed: There was an error in creating STUDENT: " . $e; // Display error
    }
}


