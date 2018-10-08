import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Statement;
import java.text.DateFormat;
import java.text.ParseException;
import java.text.SimpleDateFormat;
import java.util.Iterator;
import java.util.LinkedHashMap;
import java.util.Map;


public class DBConn {

	/**
	 * @param args
	 * @throws ParseException 
	 */
	
	public static void main(String[] args) throws ParseException {
		// Specifies the database location
		// protocol:vendor://hostname:port/Dbname
		
		/* database connection variables 
		 * 
		 */
		String dbUrl = "jdbc:mysql://localhost:3306/friends";
		String dbClass = "com.mysql.jdbc.Driver";
		
		
		String query = "Select * FROM person";

		/*hashmap added for string */
		
		Map<String, Employee> allEmployee = new LinkedHashMap<String, Employee>();
		
		/*connection made */
		
		Connection con = null;
		try {
			// load the driver class, make sure that you add the jar file that
			// contains the driver in the classpath of the project
			// you can config this in the properties-->Java build path --> libraries
			
			Class.forName(dbClass);
			con = DriverManager.getConnection(dbUrl, "will", "pass");
			Statement stmt = con.createStatement();
			
			/*query made */
			
			ResultSet rs = stmt.executeQuery(query);

			/*hashes added from query info */
			
			while (rs.next()) {
				Employee e = new Employee(rs);
				allEmployee.put(e.getSsn(), e);
				//System.out.println(e);
			} // end while
			
			/*hashinfo printed*/
			
			reportingChain(allEmployee);
			
			/*dateformat created */
			
			DateFormat df = new SimpleDateFormat("MM/dd/yyyy");
			// make sure that you understand that if you would like to insert another time
			// create a new employee with a different id
			
			/* new object created */
			
			Employee newEm = new Employee("David", "M", "Robinson", "198765432", df.parse("01/30/1960"), "123 College", "M", 1000000000.0 , "123456789", 5);
			newEmployee(newEm, stmt);
		} // end try

		catch (ClassNotFoundException e) {
			e.printStackTrace();
		}

		catch (SQLException e) {
			e.printStackTrace();
		}
		

	}
	
	
	/* string for selecct statement for update statement created */
	
	public static void newEmployee(Employee e, Statement stmt) throws SQLException {
		StringBuilder update = new StringBuilder("insert into employee values (");
		update.append("'").append(e.getFirstName()).append("',").
		append("'").append(e.getmInit()).append("',").
		append("'").append(e.getLastName()).append("',").
		append(e.getSsn()).append(",").
		append("'").append(Utils.dateToStringYMD(e.getbDate())).append("',").
		append("'").append(e.getAddress()).append("',").
		append("'").append(e.getSex()).append("',").
		append(e.getSalary()).append(",").
		append(e.getSupervisorSsn()).append(",").
		append(e.getdNo()).append(")");
	
		stmt.executeUpdate(update.toString());
	}
	
	/* Repoprting chain iterates thorugh the hashmap and prints it */
	
	public static void reportingChain(Map<String, Employee> all) {
		Iterator<Employee> it = all.values().iterator();
		while(it.hasNext()) {
			Employee e = it.next();
			
			while(e != null) {
				System.out.print(e.getFirstName() + " " + e.getLastName());
					
				if(e.getSupervisorSsn() != null) {
					System.out.print( " --> ");
					String supervisorSsn = e.getSupervisorSsn();
					Employee supervisor = all.get(supervisorSsn);
					e = supervisor;
				}
				else 
					e = null;
			}
			
			System.out.println();
		}
	}

}
