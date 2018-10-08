import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Date;
	
public class Person {

	private String firstname;
	private String lastname;
	private String bdate;
	private String phone;
	private int dNo;
	
	public Person(ResultSet rs) throws SQLException {
			
		super();
		this.firstname = rs.getString(2);
		this.lastname = rs.getString(3);
		this.bdate = rs.getString(4); 
		this.phone = rs.getString(5);
		this.dNo = rs.getInt(1);
	}
	
	

	
	public Person() {
		
	}
		
	
		public String getFirstname() {
			return firstname;
		}
		public void setFirstname(String firstname) {
			this.firstname = firstname;
		}
		public String getLastname() {
			return lastname;
		}
		public void setLastname(String lastname) {
			this.lastname = lastname;
		}
		public String getBdate() {
			return bdate;
		}
		public void setBdate(String bdate) {
			this.bdate = bdate;
		}
		public String getPhone() {
			return phone;
		}
		public void setPhone(String phone) {
			this.phone = phone;
		}
		public int getdNo() {
			return dNo;
		}
		public void setdNo(int dNo) {
			this.dNo = dNo;
		}
		
		public String toString() {
			return "Friend [fname=" + firstname + ", lname=" + lastname +",bdate="
					+ bdate + ", phone=" + phone + ", dNo=" + dNo + "]";
		}
		
		
	}