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


try{// DONE
    $sql = "CREATE TABLE MAJOR(
          id INTEGER PRIMARY KEY AUTO_INCREMENT,
          name VARCHAR(45)
        ) ";

    $stmpt = $conn->exec($sql);

    echo "\nSuccess - Table Created MAJOR";
}
catch(PDOException $e) {
    if($e->getCode() == "42S01") {
        echo "\nTable MAJOR already exists"; // Display error
    }
    else {
        echo "\nFailed: There was an error in creating MAJOR: " . $e; // Display error
    }
}

try{// DONE
    $sql = "CREATE TABLE LOCATION(
          id INTEGER PRIMARY KEY AUTO_INCREMENT,
          name VARCHAR(144)
        ) ";

    $stmpt = $conn->exec($sql);

    echo "\nSuccess - Table Created LOCATION";
}
catch(PDOException $e) {
    if($e->getCode() == "42S01") {
        echo "\nTable LOCATION already exists"; // Display error
    }
    else {
        echo "\nFailed: There was an error in creating LOCATION: " . $e; // Display error
    }
}

try{// DONE
    $sql = "CREATE TABLE DEPARTMENT(
          id INTEGER PRIMARY KEY AUTO_INCREMENT,
          name VARCHAR(45)
        ) ";

    $stmpt = $conn->exec($sql);

    echo "\nSuccess - Table Created DEPARTMENT";
}
catch(PDOException $e) {
    if($e->getCode() == "42S01") {
        echo "\nTable DEPARTMENT already exists"; // Display error
    }
    else {
        echo "\nFailed: There was an error in creating DEPARTMENT: " . $e; // Display error
    }
}

// Create Person Table
try{ // DONE
    $sql = "CREATE TABLE PERSON(
          id INTEGER PRIMARY KEY AUTO_INCREMENT,
          firstName VARCHAR(30),
          middleInitial VARCHAR(1),
          lastName VARCHAR(30),
          address VARCHAR(30),
          city VARCHAR(15),
          state VARCHAR(30),
          zip VARCHAR(5),
          phone VARCHAR(13),
          email VARCHAR(45),
          
          deptID INTEGER,
          FOREIGN KEY (deptID) REFERENCES DEPARTMENT(id)
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

try{ // DONE
    $sql = "CREATE TABLE FACULTY(
          id INTEGER AUTO_INCREMENT,
          highestDegree ENUM('BA', 'PHD', 'Masters', 'BS', 'AS') DEFAULT null,
          higheredDate DATETIME NOT NULL DEFAULT NOW(),
          
          personID INTEGER,
          
          FOREIGN KEY (personID) REFERENCES PERSON (id),
          
          PRIMARY KEY (id, personID)
          
        ) ";

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

try{ // DONE
    $sql = "CREATE TABLE EVENT(
          id INTEGER PRIMARY KEY AUTO_INCREMENT,
          name VARCHAR(30),
          description VARCHAR(144),
          startTime DATETIME,
          endTime DATETIME,
          
          locationID INTEGER,
          sponserID INTEGER,
          
          FOREIGN KEY (locationID) REFERENCES LOCATION(id),
          FOREIGN KEY (sponserID) REFERENCES FACULTY(id)
          
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

try{ // DONE
    // Composite key was needed for this to work
    $sql = "CREATE TABLE REGISTERED(
          id INTEGER AUTO_INCREMENT,
          attended BOOLEAN,
          date DATETIME,
          
          eventID INTEGER,
          personID INTEGER,
          
          FOREIGN KEY (eventID) REFERENCES EVENT(id),
          FOREIGN KEY (personID) REFERENCES PERSON(id),
          
          PRIMARY KEY (id, eventID, personID) 
          
        ) ";

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

try{ // DONE
    $sql = "CREATE TABLE EVALUATION(
          id INTEGER AUTO_INCREMENT,
          answer_one VARCHAR(144),
          answer_two VARCHAR(144),
          answer_three VARCHAR(144),
          
          evaluationTime DATETIME NOT NULL DEFAULT NOW(),
          
          ratings ENUM ('1','2','3','4','5') DEFAULT '5',
          
          personID INTEGER,
          eventID INTEGER,
          
          FOREIGN KEY (personID) REFERENCES REGISTERED (personID),
          FOREIGN KEY (eventID) REFERENCES REGISTERED (eventID),
          
          PRIMARY KEY (id, personID, eventID)
          
        ) ";

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

try{ // DONE
    $sql = "CREATE TABLE STAFF(
          id INTEGER AUTO_INCREMENT,
          posTitle VARCHAR(45),
          supervisorYorN BOOLEAN DEFAULT FALSE,
          
          personID INTEGER,
          
          FOREIGN KEY (personID) REFERENCES PERSON (id),
          
          PRIMARY KEY (id, personID)
          
        ) ";

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


try{ // DONE
    $sql = "CREATE TABLE STUDENT(
          id INTEGER PRIMARY KEY AUTO_INCREMENT,
          satScore DOUBLE,
          
          majorID int,
          FOREIGN KEY (majorID) REFERENCES MAJOR(id),
          
          personID INTEGER,
          FOREIGN KEY (personID) REFERENCES PERSON(id)         
          
        ) ";

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

// TODO ANDREW - calls some object to fill the database


