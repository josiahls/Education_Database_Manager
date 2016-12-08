
public class Sql {
	String majorCreate;
	String locationCreate;
	String depCreate;
	String personCreate;
	String facultyCreate;
	String eventCreate;
	String registeredCreate;
	String evaluationCreate;
	String staffCreate;
	String studentCreate;
	String[] depInsert = {"INSERT INTO department(name) VALUES ('Engineering');",
			"INSERT INTO department(name) VALUES ('Business');",
			"INSERT INTO department(name) VALUES ('Law');",
			"INSERT INTO department(name) VALUES ('Sciences');",
			"INSERT INTO department(name) VALUES ('Nursing');"};
	String[] majorInsert = {"INSERT INTO major(name) VALUES ('Accounting');",
			"INSERT INTO major(name) VALUES ('Architecture');",
		    "INSERT INTO major(name) VALUES ('Biology');",
		    "INSERT INTO major(name) VALUES ('Business');",
		    "INSERT INTO major(name) VALUES ('Business Analytics');",
		    "INSERT INTO major(name) VALUES ('Chemistry');",
		    "INSERT INTO major(name) VALUES ('Civil Engineering');",
		    "INSERT INTO major(name) VALUES ('Computer Engineering');",
		    "INSERT INTO major(name) VALUES ('Computer Science');",
		    "INSERT INTO major(name) VALUES ('Criminal Justice');",
		    "INSERT INTO major(name) VALUES ('Electrical Engineering');",
		    "INSERT INTO major(name) VALUES ('Finance');",
		    "INSERT INTO major(name) VALUES ('Mathematics');",
		    "INSERT INTO major(name) VALUES ('Mechanical Engineering');",
		    "INSERT INTO major(name) VALUES ('Metorology');",
		    "INSERT INTO major(name) VALUES ('Nursing');",
		    "INSERT INTO major(name) VALUES ('Physics');",
		    "INSERT INTO major(name) VALUES ('System Engineering');"};
	String[] locationInsert = {"INSERT INTO location(name) VALUES ('Atkins');",
			"INSERT INTO location(name) VALUES ('Bioinformatics');",
		    "INSERT INTO location(name) VALUES ('EPIC');",
		    "INSERT INTO location(name) VALUES ('Belk Gym');",
		    "INSERT INTO location(name) VALUES ('Duke Centennial Hall');",
		    "INSERT INTO location(name) VALUES ('Woodward Hall');",
		    "INSERT INTO location(name) VALUES ('Johnson Band Center');",
		    "INSERT INTO location(name) VALUES ('Kulwicki Laboratory');",
		    "INSERT INTO location(name) VALUES ('Student Union');"};
	String[] eventInsert = {"INSERT INTO event(name, description, startTime, endTime, locationID, sponserID) VALUES ('Job Fair','internships and full time','01-01-01T18:00:00','01-01-01T19:00:00',(SELECT id FROM location ORDER BY RAND() LIMIT 1),(SELECT id FROM faculty ORDER BY RAND() LIMIT 1));",
			"INSERT INTO event(name, description, startTime, endTime, locationID, sponserID) VALUES ('Engineering Meeting','Engineering college meeting','01-01-01T18:00:00','01-01-01T19:00:00',(SELECT id FROM location ORDER BY RAND() LIMIT 1),(SELECT id FROM faculty ORDER BY RAND() LIMIT 1));",
			"INSERT INTO event(name, description, startTime, endTime, locationID, sponserID) VALUES ('Sport Intrests','joinging sports','01-01-01T18:00:00','01-01-01T19:00:00',(SELECT id FROM location ORDER BY RAND() LIMIT 1),(SELECT id FROM faculty ORDER BY RAND() LIMIT 1));",
			"INSERT INTO event(name, description, startTime, endTime, locationID, sponserID) VALUES ('Class Sign Up','Help with Registration','01-01-01T18:00:00','01-01-01T19:00:00',(SELECT id FROM location ORDER BY RAND() LIMIT 1),(SELECT id FROM faculty ORDER BY RAND() LIMIT 1));",
			"INSERT INTO event(name, description, startTime, endTime, locationID, sponserID) VALUES ('Resume Helpter','Get help creating a killer resume','01-01-01T18:00:00','01-01-01T19:00:00',(SELECT id FROM location ORDER BY RAND() LIMIT 1),(SELECT id FROM faculty ORDER BY RAND() LIMIT 1));"}; 
	Sql(){
		this.majorCreate = "CREATE TABLE MAJOR("
				+"id INTEGER PRIMARY KEY AUTO_INCREMENT,"
				+"name VARCHAR(45))";
		this.locationCreate = "CREATE TABLE LOCATION("
				+"id INTEGER PRIMARY KEY AUTO_INCREMENT,"
				+"name VARCHAR(144))";
		this.depCreate = "CREATE TABLE DEPARTMENT("
				+"id INTEGER PRIMARY KEY AUTO_INCREMENT,"
				+"name VARCHAR(45))";
		this.personCreate = "CREATE TABLE PERSON("
				+"id INTEGER PRIMARY KEY AUTO_INCREMENT,"
				+"firstName VARCHAR(30),"
				+"middleInitial VARCHAR(1),"
				+"lastName VARCHAR(30),"
				+"address VARCHAR(75),"
				+"city VARCHAR(30),"
				+"state VARCHAR(30),"
				+"zip VARCHAR(5),"
				+"phone VARCHAR(13),"
				+"email VARCHAR(45),"
          		+"deptID INTEGER,"
          		+"FOREIGN KEY (deptID) REFERENCES DEPARTMENT(id));";
		this.facultyCreate = "CREATE TABLE FACULTY("
				+"id INTEGER AUTO_INCREMENT,"
				+"highestDegree ENUM('BA', 'PHD', 'Masters', 'BS', 'AS') DEFAULT null,"
	            +"higheredDate DATETIME NOT NULL DEFAULT NOW(),"
	            +"personID INTEGER,"
	            +"FOREIGN KEY (personID) REFERENCES PERSON (id),"
	            +"PRIMARY KEY (id, personID))";
		this.eventCreate = "CREATE TABLE EVENT("
				  +"id INTEGER PRIMARY KEY AUTO_INCREMENT,"
		          +"name VARCHAR(30),"
		          +"description VARCHAR(144),"
		          +"startTime DATETIME,"
		          +"endTime DATETIME,"
		          +"locationID INTEGER,"
		          +"sponserID INTEGER,"
		          +"FOREIGN KEY (locationID) REFERENCES LOCATION(id),"
		          +"FOREIGN KEY (sponserID) REFERENCES FACULTY(id))";
		this.registeredCreate = "CREATE TABLE REGISTERED("
				+"id INTEGER AUTO_INCREMENT,"
		          +"attended BOOLEAN,"
		          +"date DATETIME,"
		          +"eventID INTEGER,"
		          +"personID INTEGER,"
		          +"FOREIGN KEY (eventID) REFERENCES EVENT(id),"
		          +"FOREIGN KEY (personID) REFERENCES PERSON(id),"
		          +"PRIMARY KEY (id, eventID, personID)) ";
		this.evaluationCreate = "CREATE TABLE EVALUATION("
				+"id INTEGER AUTO_INCREMENT,"
				+"answer_one VARCHAR(144),"
				+"answer_two VARCHAR(144),"
				+"answer_three VARCHAR(144),"
				+"evaluationTime DATETIME NOT NULL DEFAULT NOW(),"
				+"ratings ENUM ('1','2','3','4','5') DEFAULT '5',"
				+"personID INTEGER,"
				+"eventID INTEGER,"
				+"FOREIGN KEY (personID) REFERENCES REGISTERED (personID),"
				+"FOREIGN KEY (eventID) REFERENCES REGISTERED (eventID),"
				+"PRIMARY KEY (id, personID, eventID))";
		this.staffCreate = "CREATE TABLE STAFF("
				+"id INTEGER AUTO_INCREMENT,"
		        +"posTitle VARCHAR(45),"
		        +"supervisorYorN BOOLEAN DEFAULT FALSE,"
		        +"personID INTEGER,"
		        +"FOREIGN KEY (personID) REFERENCES PERSON (id),"
		        +"PRIMARY KEY (id, personID))";
		this.studentCreate = "CREATE TABLE STUDENT("
				+"id INTEGER PRIMARY KEY AUTO_INCREMENT,"
		        +"satScore DOUBLE,"
		        +"majorID int,"
		        +"FOREIGN KEY (majorID) REFERENCES MAJOR(id),"
		        +"personID INTEGER,"
		        +"FOREIGN KEY (personID) REFERENCES PERSON(id))";
	}
}
