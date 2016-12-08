import java.awt.List;
import java.sql.*;
import java.util.ArrayList;

public class DBConnect {
	
	
	private Connection con;
	private Statement st;
	public ResultSet rs;
	
	public DBConnect(){
		try{
			Class.forName("com.mysql.jdbc.Driver");
			
			con = DriverManager.getConnection("jdbc:mysql://localhost:3306/educationdb","root","");
			st = con.createStatement();
			
		}catch(Exception ex){
			System.out.print("Error: "+ex);
		}
	}
	public void insertBatch(ArrayList<String> query){
		try{
			for(int i=0; i < query.size(); i++){
				st.addBatch(query.get(i));
			}
			st.executeBatch();
		}catch(Exception ex){
			System.out.print("ERROR: " +ex);
		}
	}
	public void insertBatch(String[] query){
		try{
			for(int i=0; i < query.length; i++){
				st.addBatch(query[i]);
			}
			st.executeBatch();
		}catch(Exception ex){
			System.out.print("ERROR: " +ex);
		}
	}
	
	public void insert(String query){
		try{
			st.executeUpdate(query);
		}catch(Exception ex){
			System.out.print("ERROR: " +ex);
		}
	} 

	public int createTable(String query){
		int temp = 0;
		try{
			st.executeUpdate(query);
		}catch(Exception ex){
			System.out.println(ex.getMessage());
			if(ex.getMessage().equals("Table 'major' already exists")){
				return 1;
			}else if(ex.getMessage().equals("Can't create database 'educationdb'; database exists")){
				return 2;
			}else{
				return 3;
			}
		}
		return temp;
	}
	
	
	public void get(String query){
		try{
			rs = st.executeQuery(query);
			
		}catch(Exception ex){
			System.out.print("ERROR: " +ex);
		}
	}
}
