import java.sql.*;


public class JdbcMysqlConnectionString {

static public final String driver = "com.mysql.jdbc.Driver";
static public final String connection =
"jdbc:mysql://localhost:3306/friends";
static public final String user = "will";
static public final String password = "pass";

public static void main(String args[]) {
try {
Class.forName(driver);
Connection con =
DriverManager.getConnection(connection, user, password);

System.out.println("Jdbc Mysql Connection String :");
System.out.println(connection);

System.out.println("User Name :" + user);
System.out.println("Password :" + password);


String query = "Select * FROM person";


	// load the driver class, make sure that you add the jar file that
	// contains the driver in the classpath of the project
	// you can config this in the properties-->Java build path --> libraries
	
	
	Statement stmt = con.createStatement();
	ResultSet rs = stmt.executeQuery(query);
	
	
if (!con.isClosed()) {
con.close();
}

} catch (Exception e) {
e.printStackTrace();
}
}
}

		

	