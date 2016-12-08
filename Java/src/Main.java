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
		
		//Create Stored Procedure
		
		//Insert Department
		Insert in = new Insert(150);
		//Insert Majors
		
		//Insert Location
		
		//Insert people
		
		//Insert event
		
		//Insert registered
		
		//Insert Evaluations
		
		
	}
	public static void Create(){
		Statement st1 = null;
		String query = "CREATE DATABASE `educationdb`;";
		try{
			Connection con1 = DriverManager.getConnection("jdbc:mysql://localhost:3306/","root","");
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
