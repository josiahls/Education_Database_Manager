import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;

public class Main {
	static DBConnect connect;
	
	public static void main(String[] args) {
		//Create Database
		Create();
		connect = new DBConnect();
		Sql sql = new Sql();
		//Create Major
		TableCreate(sql.majorCreate,"Major");
		//Create Location
		TableCreate(sql.locationCreate,"Location");
		//Create Department
		TableCreate(sql.depCreate,"Department");
		//Create Person
		TableCreate(sql.personCreate,"Person");
		//Create Faculty
		TableCreate(sql.facultyCreate,"Faculty");
		//Create Event
		TableCreate(sql.eventCreate,"Event");
		//Create Registered
		TableCreate(sql.registeredCreate,"Registered");
		//Create Evaluation
		TableCreate(sql.evaluationCreate,"Evaluation");
		//Create Staff
		TableCreate(sql.staffCreate,"Staff");
		//Create Student
		TableCreate(sql.studentCreate,"Student");
		//Create Trigger
		connect.insert(sql.triggerCreate);
		//Create Stored Procedure
		connect.insert(sql.procejureCreate);
		
		Insert in = new Insert(150);
		
		menu();
	}
	
	public static void menu(){
		System.out.println("Event Report:");
		//Hosted by
		connect.get("SELECT person.firstName, person.lastName, event.name FROM person INNER JOIN event ON event.sponserID=person.id");
		try {
			System.out.println("Event hosts:");
			while(connect.rs.next()){
				System.out.println(connect.rs.getString("name") + " is hosted by " + connect.rs.getString("firstName") + " " + connect.rs.getString("lastName"));
			}
		} catch (SQLException e) {
			e.printStackTrace();
		}
		System.out.println("Faculty Report:");
		connect.get("SELECT highestDegree,COUNT(*) as count FROM faculty GROUP BY highestDegree ORDER BY count DESC;");
		try {
			System.out.println("Degree Breakdown Amoung Faculty:");
			System.out.println("Degree - Count");
			while(connect.rs.next()){
				System.out.println(connect.rs.getString("highestDegree") + " - " + connect.rs.getString("count"));
			}
		} catch (SQLException e) {
			e.printStackTrace();
		}
		System.out.println("Student Report:");
		connect.get("SELECT major.name, count FROM (SELECT majorID,COUNT(*) as count FROM student GROUP BY majorID ORDER BY count DESC) my_select INNER JOIN major ON major.id=my_select.majorID");
		try {
			System.out.println("Student Major's Count:");
			System.out.println("Major - Count");
			while(connect.rs.next()){
				System.out.println(connect.rs.getString("name") + " - " + connect.rs.getString("count"));
			}
		} catch (SQLException e) {
			e.printStackTrace();
		}
		System.out.println("Department Population Report:");
		connect.get("SELECT department.name, count FROM (SELECT deptID,COUNT(*) as count FROM person GROUP BY deptID ORDER BY count DESC) my_select INNER JOIN department ON department.id=my_select.deptID;");
		try {
			System.out.println("Department - Count");
			while(connect.rs.next()){
				System.out.println(connect.rs.getString("name") + " - " + connect.rs.getString("count"));
			}
		} catch (SQLException e) {
			e.printStackTrace();
		}
	}
	
	public static void Create(){
		Statement st1 = null;
		String query = "CREATE DATABASE `educationdb`;";
		try{											//server location               ,username, password
			Connection con1 = DriverManager.getConnection("jdbc:mysql://localhost:3306/","root",""); //TODO change server location, change username, change password
			st1 = con1.createStatement();
			st1.executeUpdate(query);
		}catch(Exception ex){
			if(ex.getMessage().equals("Can't create database 'educationdb'; database exists")){
				try {
					st1.executeUpdate("DROP DATABASE `educationdb`;");
					System.out.println("Old Database Dropped!");
					st1.executeUpdate(query);
					System.out.println("New Database Created Successfully!");
				} catch (SQLException e) {
					e.printStackTrace();
				}
			}
		}
	}
	
	public static void TableCreate(String query, String tableName){
		int x = connect.createTable(query);
		if(x == 0){
			System.out.println(tableName + " Created Successfully!");
		}else if(x == 1){
			System.out.println(tableName + " Already Exists!");
		}else{
			System.out.println("Another error has occurred while creating "+ tableName +"!");
		}
	}
}
