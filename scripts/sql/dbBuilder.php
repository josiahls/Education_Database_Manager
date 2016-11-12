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
          address VARCHAR(75),
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
include("simple_html_dom.php");
echo "<br>";
echo "<br>";
echo "<br>";
//input Department
try{ // DONE
    $sql ="INSERT INTO department(name) VALUES ('Engineering');
    INSERT INTO department(name) VALUES ('Business');
    INSERT INTO department(name) VALUES ('Law');
    INSERT INTO department(name) VALUES ('Sciences');
    INSERT INTO department(name) VALUES ('Nursing');";


    $stmpt = $conn->exec($sql);

    echo "\nSuccess - Insert Into Department";
}
catch(PDOException $e) {
    echo "\nFailed: There was an error in inputing into Departement: " . $e; // Display error
}
//input majors
try{ // DONE
    $sql = "INSERT INTO major(name) VALUES ('Accounting');
    INSERT INTO major(name) VALUES ('Architecture');
    INSERT INTO major(name) VALUES ('Biology');
    INSERT INTO major(name) VALUES ('Business');
    INSERT INTO major(name) VALUES ('Business Analytics');
    INSERT INTO major(name) VALUES ('Chemistry');
    INSERT INTO major(name) VALUES ('Civil Engineering');
    INSERT INTO major(name) VALUES ('Computer Engineering');
    INSERT INTO major(name) VALUES ('Computer Science');
    INSERT INTO major(name) VALUES ('Criminal Justice');
    INSERT INTO major(name) VALUES ('Electrical Engineering');
    INSERT INTO major(name) VALUES ('Finance');
    INSERT INTO major(name) VALUES ('Mathematics');
    INSERT INTO major(name) VALUES ('Mechanical Engineering');
    INSERT INTO major(name) VALUES ('Metorology');
    INSERT INTO major(name) VALUES ('Nursing');
    INSERT INTO major(name) VALUES ('Physics');
    INSERT INTO major(name) VALUES ('System Engineering');";

    $stmpt = $conn->exec($sql);

    echo "\nSuccess - Insert Into Major";
}
catch(PDOException $e) {
    echo "\nFailed: There was an error in inputing into Major: " . $e; // Display error
}
//input location
try{ // DONE
    $sql = "INSERT INTO location(name) VALUES ('Atkins');
    INSERT INTO location(name) VALUES ('Bioinformatics');
    INSERT INTO location(name) VALUES ('EPIC');
    INSERT INTO location(name) VALUES ('Belk Gym');
    INSERT INTO location(name) VALUES ('Duke Centennial Hall');
    INSERT INTO location(name) VALUES ('Woodward Hall');
    INSERT INTO location(name) VALUES ('Johnson Band Center');
    INSERT INTO location(name) VALUES ('Kulwicki Laboratory);
    INSERT INTO location(name) VALUES ('Student Union');";

    $stmpt = $conn->exec($sql);

    echo "\nSuccess - Insert Into Location";
}
catch(PDOException $e) {
    echo "\nFailed: There was an error in inputing into Location: " . $e; // Display error
}

$studPop = 30;
//input people 
    //insert STAFF   2% of student popluation
    $staffPop = $studPop * .10;
    createPerson($staffPop,"Staff",$conn);
    $stmpt = $conn->exec($sql);
    //insert Faculty  10% of sutdent poplation
    $facultyPop = $studPop * .20;
    createPerson($facultyPop,"Faculty",$conn);
    //insert Student
    createPerson($studPop,"Student",$conn);
//insert event
try{ // DONE
    $sql = "INSERT INTO event(name, description, startTime, endTime, locationID, sponserID) VALUES ('Job Fair','internships and full time','12:00pm','4:00pm',(SELECT id FROM location ORDER BY RAND() LIMIT 1),(SELECT id FROM faculty ORDER BY RAND() LIMIT 1));
    INSERT INTO event(name, description, startTime, endTime, locationID, sponserID) VALUES ('Engineering Meeting','Engineering college meeting','6:00pm','7:30p',(SELECT id FROM location ORDER BY RAND() LIMIT 1),(SELECT id FROM faculty ORDER BY RAND() LIMIT 1));
    INSERT INTO event(name, description, startTime, endTime, locationID, sponserID) VALUES ('Sport Intrests','joinging sports','6:00pm','7:30p',(SELECT id FROM location ORDER BY RAND() LIMIT 1),(SELECT id FROM faculty ORDER BY RAND() LIMIT 1));
    INSERT INTO event(name, description, startTime, endTime, locationID, sponserID) VALUES ('Class Sign Up','Help with Registration','6:00pm','7:30p',(SELECT id FROM location ORDER BY RAND() LIMIT 1),(SELECT id FROM faculty ORDER BY RAND() LIMIT 1));
    INSERT INTO event(name, description, startTime, endTime, locationID, sponserID) VALUES ('Resume Helpter','Get help creating a killer resume','6:00pm','7:30p',(SELECT id FROM location ORDER BY RAND() LIMIT 1),(SELECT id FROM faculty ORDER BY RAND() LIMIT 1));";

    $stmpt = $conn->exec($sql);

    echo "\nSuccess - Insert Into Event";
}
catch(PDOException $e) {
    echo "\nFailed: There was an error in inputing into Event: " . $e; // Display error
}
//input registered
/*$RegAmt = $studPop * .5;
    for($i = 0; $i < $RegAmt; $i++){
        $attended = mt_rand(0,1);
        $betweenDate = mt_rand(1420070400, 1451606400);
        $Date = date("Y-m-d H:i:s",$betweenDate);
        $sql = "INSERT INTO registered(attended,`date`, eventID, personID) VALUES ('".$attended."','".$Date."',(SELECT id FROM event ORDER BY RAND() LIMIT 1),(SELECT id FROM person ORDER BY RAND() LIMIT 1));";
        try{
            $stmpt = $conn->exec($sql);
            echo "\nSuccess - Insert Into Registered";
        }
        catch(PDOException $e) {
            echo "\nFailed: There was an error in inputing into Registered: " . $e; // Display error
        }
    }
//input evaluations
$inputAmt = $RegAmt *.25;
    for($i = 0; $i < $inputAmt; $i++){
        $rating = mt_rand(1,5);
        $betweenDate = mt_rand(1420070400, 1451606400);
        $Date = date("Y-m-d H:i:s",$betweenDate);
        $sql = "INSERT INTO evaluation(answer_one, answer_two, answer_three, evaluationTime, ratings, personID, eventID) VALUES ('Answer One','Answer Two','Answer Three','".$Date."','".$rating."',(SELECT id FROM person ORDER BY RAND() LIMIT 1),(SELECT id FROM event ORDER BY RAND() LIMIT 1));";
        try{
            $stmpt = $conn->exec($sql);
            echo "\nSuccess - Insert Into evaluation";
        }
        catch(PDOException $e) {
            echo "\nFailed: There was an error in inputing into evaluation: " . $e; // Display error
        }
    }*/
function createPerson($pop, $type,$conn){
    $sql = "";
    for($i = 0; $i < $pop; $i++){
        //get fake person here
        $html = file_get_html("http://www.fakenamegenerator.com/gen-random-us-us.php");
        $name = $html->find("div[class=address] h3",0)->innertext;
        $address = $html->find("div[class=adr]",0)->innertext;
        $email = $html->find("dl[class=dl-horizontal] dd",8)->innertext;

        $address = preg_split('/<br[^>]*>/i', $address);
        $city = explode(",", $address[1]);
        $state = explode(" ", $city[1]);
        $email = explode(" ", $email);
        $name = explode(" ", $name);    

        $phone = $html->find("dl[class=dl-horizontal] dd",3)->innertext;
        $fName = $name[0];
        $city = $city[0];
        $zip = $state[2];
        $state = $state[1];
        $Mini = $name[1][0];
        $lName = $name[2];
        $email = $email[0];
        $address = trim($address[0]);


        $sql .= "INSERT INTO person(firstName,middleInitial,lastName,address,city,state,zip,phone,email,deptID) VALUES ('". $fName . "','". $Mini . "','". $lName . "','". $address . "','". $city . "','". $state . "','". $zip . "','". $phone . "','". $email . "',(SELECT id FROM department ORDER BY RAND() LIMIT 1));";
        if($type == "Faculty"){
            $highDegree = array('BA', 'PHD', 'Masters', 'BS', 'AS');
            $randKey = mt_rand(0, 4);
            $betweenDate = mt_rand(1420070400, 1451606400);
            $hireDate = date("Y-m-d H:i:s",$betweenDate);
            $sql .= "INSERT INTO faculty(highestDegree, higheredDate,personID) VALUES ('".$highDegree[$randKey]."','".$hireDate."',(SELECT id FROM person WHERE firstName='". $fName ."' AND lastName='".$lName."'));"; //add insert statment into sql for faculty
        }elseif($type == "Student"){
            $satScore = mt_rand(800 , 1600);
            $sql .= "INSERT INTO student(satScore, majorID, personID) VALUES ('".$satScore."',(SELECT id FROM major ORDER BY RAND() LIMIT 1),(SELECT id FROM person WHERE firstName='". $fName ."' AND lastName='".$lName."'));"; //add insert statment into sql for student
        }elseif($type == "Staff"){
            $superSelect = mt_rand(0,1);
            $positions =  array("Dean", "Administrator","Manager", "Worker");
            $poSelect = array_rand($positions,1);
            $sql .= "INSERT INTO staff(posTitle,supervisorYorN,personID) VALUES ('".$positions[$poSelect]."','".$superSelect."',(SELECT id FROM person WHERE firstName='". $fName ."' AND lastName='".$lName."'));"; //add insert statment into sql for staff
        }
    }
    try{
        //echo $sql;
        $stmpt = $conn->exec($sql);
        echo "\nSuccess - Insert Into " .$type;
    }
    catch(PDOException $e) {
        echo "\nFailed: There was an error in inputing into" . $type . ": " . $e; // Display error
    }
}