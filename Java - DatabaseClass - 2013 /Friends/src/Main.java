
import java.sql.Connection;
import java.sql.Date;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.text.DateFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Calendar;
import java.util.Iterator;
import java.util.LinkedHashMap;
import java.util.Map;
import java.util.Random;



public class Main {

	/**
	 * @param args
	 */
	public static void main(String[] args) throws ParseException {
		// Specifies the database location
		// protocol:vendor://hostname:port/Dbname
		
		/* database connection variables 
		 * 
		 */
		String dbUrl = "jdbc:mysql://localhost:3306/friends";
		String dbClass = "com.mysql.jdbc.Driver";
		Connection con = null;
		
		
		/*connects to database*/
		
		try {
			con = DriverManager.getConnection(dbUrl, "will", "pass");
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
		Statement stmt;
		try {
			
			String update ="";
			for (int x=1; x<100; x++){
				
				String fname=getfirstName();
				String lname = getlastName();
				String dob = RandomDOB();
				String phone = RandomPhonenum();
			update = "insert into person (id,fname,lname,bdate,phone) values (null,"+"'"+fname+
			"','"+lname+"','"+dob+"','"+phone+"');";
			stmt = con.createStatement();
			stmt.executeUpdate(update.toString());
			update="";
			}
			//for debugging only//
			//System.out.println(update.toString());
			
			
	
		
			
			
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

		System.out.println(getfirstName() + ","+getlastName()+","+RandomDOB()+","+RandomPhonenum());
		
		 /* creates hash map */
		
		String query = "Select * FROM person";

		/*hashmap added for string */
		
		Map<Integer, Person> allPersons = new LinkedHashMap<Integer, Person>();
		
		/*put into hash */
		
		try {
			ResultSet rs;
			stmt = con.createStatement();
			rs = stmt.executeQuery(query);
			while (rs.next()) {
				Person e = new Person(rs);
				allPersons.put(e.getdNo(), e);
				
			}
		} catch (SQLException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

		/*hashes added from query info */
		reportingChain(allPersons);
	
			//System.out.println(e);
		} // end while
		
	public static void reportingChain(Map<Integer, Person> all) {
		Iterator<Person> it = all.values().iterator();
		while(it.hasNext()) {
			Person e = it.next();
			
			while(e != null) {
				System.out.print(e.getFirstname() + " " + e.getLastname());
			}
		}
				
			}

	private static String getfirstName() {
		// TODO Auto-generated method stub
		
			String firstname = "Person";
	  	    
	  	    int first;
	  	   
	  	      Random ran1 = new Random(); 
	  	      first=ran1.nextInt(24); 
	  	  
	  	  
	        switch (first) {
	            case 1:  firstname = "Bob";
	                     break;
	            case 2:  firstname = "John";
	                     break;
	            case 3:  firstname = "Scott";
	                     break;
	            case 4:  firstname = "Will";
	                     break;
	            case 5:  firstname = "Peter";
	                     break;
	            case 6:  firstname = "June";
	                     break;
	            case 7:  firstname = "Sally";
	                     break;
	            case 8:  firstname = "Megan";
	                     break;
	            case 9:  firstname = "Aaron";
	                     break;
	            case 10: firstname = "Daniel";
	                     break;
	            case 11: firstname = "Ashley";
	                     break;
	            case 12: firstname = "Rebecca";
	                     break;
	            case 13:  firstname = "Robert";
             		break;
	            case 14:  firstname = "Jason";
             		break;
	            case 15:  firstname = "Brian";
	            		break;
	            case 16:  firstname = "Ben";
             		break;
	            case 17:  firstname = "Paige";
             		break;
	            case 18:  firstname = "Hannah";
             		break;
	            case 19:  firstname = "Abigail";
             		break;
	            case 20:  firstname = "Fred";
             		break;
	            case 21:  firstname = "Tom";
             		break;
	            case 22: firstname = "Anna";
             		break;
	            case 23: firstname = "Stacey";
             		break;
	            case 24: firstname = "Alex";
             		break;
             		
             		} return firstname;
	        }
	        
	        private static String getlastName() {
	        String lastname = "";
	         Random ran2 = new Random();
	         int last;
			 last= ran2.nextInt(24);
	        switch (last) {
         case 1:  lastname = "Baker";
                  break;
         case 2:  lastname = "Smith";
                  break;
         case 3:  lastname = "Edwards";
                  break;
         case 4:  lastname = "Pennington";
                  break;
         case 5:  lastname = "Nobel";
                  break;
         case 6:  lastname = "Zeller";
                  break;
         case 7:  lastname = "Price";
                  break;
         case 8:  lastname = "Ziegler";
                  break;
         case 9:  lastname = "Johnson";
                  break;
         case 10: lastname = "Simpson";
                  break;
         case 11: lastname = "Weiler";
                  break;
         case 12: lastname = "Burns";
                  break;
         case 13:  lastname = "Singleton";
         		break;
         case 14:  lastname = "Bourne";
         		break;
         case 15:  lastname = "Kennedy";
         		break;
         case 16:  lastname = "Weddington";
         		break;
         case 17:  lastname = "Bronson";
         		break;
         case 18:  lastname = "Euler";
         		break;
         case 19:  lastname = "Driscoll";
         		break;
         case 20:  lastname = "Walton";
         		break;
         case 21:  lastname = "Finley";
         		break;
         case 22: lastname = "Lewis";
         		break;
         case 23: lastname = "Faulkner";
         		break;
         case 24: lastname = "Meyer";
         		break;
         default: lastname = "Jones";
  		break;
     } return lastname;
	        } //end getlastname
	        
	        
	     
	        
	    	private static int[] RandomFriends(int random, int index) {
	    		// TODO Auto-generated method stub
	    		int[] friendarray;
	        	friendarray = new int[random];
	        	int rand;
	        	boolean flag=false;
	        	
	        	for(int x=0;x<=random-1; x++){
	        		
	        		
	        		rand = randBetween(1, 1000);
	        		
	        		if (x==0){friendarray[x]=rand;}
	        		
	        		for (int y=0; y<x; y++)
	        		if ((rand!=index) && (rand!=friendarray[y])){
	        			flag=true;
	        			break;}else {flag=false;}
	        		
	        		if (flag){friendarray[x]=rand;
	        		flag=false;
	        		}
	        	}
	        	
	    		return friendarray;
	        	
	    	}
	        public String RandomDateMet(String date1,String date2){
	        	
	        	DateFormat df = new SimpleDateFormat("MM/dd/yyyy");
	        	
	        	String dateMet = null;
	        	
	        	try {
					do {
					
					int year = randBetween(1930, 2000);

					int month = randBetween(1, 12);
					
					int day = randBetween(1, 30);
					
	       
					
					dateMet = (month + "/" + day + "/" + year);
	     
					} while ((df.parse(dateMet)).compareTo(df.parse(date2))<=0||(df.parse(dateMet)).compareTo(df.parse(date1))<=0);
				} catch (ParseException e) {
					// TODO Auto-generated catch block
					e.printStackTrace();
				}
  		     
  		     return dateMet;
  		     
	        }
	        
	  	
	  		public static String RandomDOB() {

	  		    

	  		        int year = randBetween(1930, 2000);

	  		        int month = randBetween(1, 12);
	  		        
	  		        int day = randBetween(1, 30);
	  		        
	  		     String birthdate = (month + "/" + day + "/" + year);
	  		     
	  		     return birthdate;}
	  		     
			private static int randBetween(int start, int end) {
						// TODO Auto-generated method stub
						return start + (int)Math.round(Math.random() * (end - start));
					}

				
				public static String RandomPhonenum() {

		  		    

	  		        int areacode = randBetween(111, 999);
	  		        int prefix = randBetween(200, 999);
	  		      int suffix = randBetween(1111, 9999);
	  		        
	  		    String area = Integer.toString(areacode);
	  		  String pre = Integer.toString(prefix);
	  		String suf = Integer.toString(suffix);
	  		     String phone = area+pre+suf;
	  		     
	  		     return phone;}
	  		     
				

	  		    

	  		  
	  		


	  	  
	}
	




