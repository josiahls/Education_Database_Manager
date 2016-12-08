import java.io.IOException;
import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.ArrayList;
import java.util.Random;

import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;
import org.jsoup.nodes.Element;
import org.jsoup.select.Elements;
public class Insert extends Main{
	int studentPop;
	Sql sql;
	Insert(int studPop){
		this.studentPop = studPop;
		this.sql = new Sql();
		insertBatch();
	}
	
	public void insertBatch(){
		System.out.println("Inserting table Data!");
		connect.insertBatch(sql.depInsert);
		connect.insertBatch(sql.majorInsert);
		connect.insertBatch(sql.locationInsert);
		//insert people here
		createPerson((int) Math.round(studentPop * .10),"Staff");
		createPerson((int) Math.round(studentPop * .20),"Faculty");
		createPerson(studentPop,"Student");
		connect.insertBatch(sql.eventInsert);
		createRegistered((int) Math.round(studentPop * .60));
		System.out.println("All Table Inserts Complete!");
	}
	
	public int randInt(int min, int max){
		 Random rand = new Random();
		 int randomNum = rand.nextInt((max - min) + 1) + min;
		 return randomNum;
	}
	
	public void createRegistered(int regAmt){
		ArrayList<String> sql = new ArrayList<String>();
		 for(int i = 0; i < regAmt; i++){
		        int attended = randInt(0,1);
		        int betweenDate = randInt(1420070400, 1451606400);
		        DateFormat df = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
	            String Date = df.format(betweenDate);
		        sql.add("INSERT INTO registered(attended,`date`, eventID, personID) VALUES ('"+attended+"','"+Date+"',(SELECT id FROM event ORDER BY RAND() LIMIT 1),(SELECT id FROM person ORDER BY RAND() LIMIT 1));");
		 }
		 connect.insertBatch(sql);
		 sql.clear();
		 for(int i = 0; i < (regAmt * .50); i++){
		        int rating = randInt(1,5);
		        int betweenDate = randInt(1420070400, 1451606400);
		        DateFormat df = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
	            String Date = df.format(betweenDate);
		        int registeredID = randInt(1, (int) Math.round(regAmt * .50));
		        sql.add("INSERT INTO evaluation(answer_one, answer_two, answer_three, evaluationTime, ratings, personID, eventID) VALUES ('Answer One','Answer Two','Answer Three','"+Date+"','"+rating+"',(SELECT personID FROM registered WHERE id='"+registeredID+"'),(SELECT eventid FROM registered WHERE id='"+registeredID+"'));");
		 }
		 connect.insertBatch(sql);
	}
	
	public void createPerson(int pop, String type){
		String sql;
	    for(int i = 0; i < pop; i++){
	        //get fake person here
	        Document html;
			try {
				html = Jsoup.connect("http://www.fakenamegenerator.com/gen-random-us-us.php").userAgent("Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.152 Safari/537.36").get();
				Elements name = html.getElementsByClass("address");
				String[] personName = Jsoup.parse(name.toString()).select("h3").text().split(" ");
		        String fName = personName[0];
		        String Mini = personName[1].replace(".", "");
		        String lName = personName[2];
		        String add = Jsoup.parse(name.toString()).getElementsByClass("adr").toString();
		        
		        String address = getAdddres(add,"street");
		        String city= getAdddres(add,"city");
		        String state = getAdddres(add,"state");
		        String zip = getAdddres(add,"zip");
		        Elements d1 = html.getElementsByClass("dl-horizontal");
		        String phone = Jsoup.parse(d1.get(3).toString()).select("dd").text();
		        String[] e = Jsoup.parse(d1.get(8).toString()).select("dd").text().split(" ");
		        String email = e[0];
		        //System.out.println(Mini);
		        //System.out.println(zip);
		        sql = "INSERT INTO person(firstName,middleInitial,lastName,address,city,state,zip,phone,email,deptID) VALUES ('"+fName+"','"+Mini+"','"+lName+"','"+address+"','"+city+"','"+state+"','"+zip+"','"+phone+"','"+email+"',(SELECT id FROM department ORDER BY RAND() LIMIT 1));";
		        connect.insert(sql);
		        if(type.equals("Faculty")){
		            String[] highDegree = {"BA", "PHD", "Masters", "BS", "AS"};
		            int randKey = randInt(0, 4);
		            int betweenDate = randInt(1420070400, 1451606400);
		            DateFormat df = new SimpleDateFormat("yyyy-MM-dd HH:mm:ss");
		            String hireDate = df.format(betweenDate);
		            sql = "INSERT INTO faculty(highestDegree, higheredDate,personID) VALUES ('"+highDegree[randKey]+"','"+hireDate+"',(SELECT id FROM person WHERE firstName='"+fName+"' AND lastName='"+lName+"'));"; //add insert statment into sql for faculty
		        }else if(type.equals("Student")){
		            int satScore = randInt(800 , 1600);
		            sql = "INSERT INTO student(satScore, majorID, personID) VALUES ('"+satScore+"',(SELECT id FROM major ORDER BY RAND() LIMIT 1),(SELECT id FROM person WHERE firstName='"+fName+"' AND lastName='"+lName+"'));"; //add insert statment into sql for student
		        }else if(type.equals("Staff")){
		            int superSelect = randInt(0,1);
		            String[] positions = {"Dean", "Administrator","Manager", "Worker"};
		            int poSelect = randInt(0,3);
		            sql = "INSERT INTO staff(posTitle,supervisorYorN,personID) VALUES ('"+positions[poSelect]+"','"+superSelect+"',(SELECT id FROM person WHERE firstName='"+fName+"' AND lastName='"+lName+"'));"; //add insert statment into sql for staff
		        }
		        connect.insert(sql);
			} catch (IOException e) {
				e.printStackTrace();
			}
	    }
	}
	
	public String getAdddres(String add, String type){
		String temp = null;
		String[] textStr = add.split("\\r\\n|\\n|\\r");
		//System.out.println(textStr[2].replaceFirst(" <br>", ""));
		if(type.equals("street")){
			temp = textStr[1].replaceFirst("  ", "");
		}else{
			String[] ans = textStr[2].replaceFirst(" <br>", "").split(",");
			//System.out.println(ans);
			if(type.equals("city")){
				temp = ans[0];
				//System.out.println(temp);
			}else if(type.equals("state")){
				temp = ans[1].split(" ")[1];
			}else if(type.equals("zip")){
				temp = ans[1].split(" ")[2];
			}
		}
		return temp;
	}
}
