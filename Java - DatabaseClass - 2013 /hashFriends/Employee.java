import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.Date;


public class Friend {
	private String firstName;
	private String mInit;
	private String lastName;
	private String ssn;
	private Date bDate;
	private String address;
	private String sex;
	private double salary;
	private String supervisorSsn;
	private int dNo;
	
	
	
	public Friend() {
		super();
		// TODO Auto-generated constructor stub
	}
	
	/**
	 * 
	 * @param rs
	 *            has the complete info about the Friend with the column order
	 *            of FNAME MINIT LNAME SSN BDATE ADDRESS SEX SALARY SUPER_SSN
	 *            DNO
	 * @throws SQLException 
	 */
	public Friend(ResultSet rs) throws SQLException {
		super();
		this.firstName = rs.getString(1);
		this.mInit = rs.getString(2);
		this.lastName = rs.getString(3);
		this.ssn = rs.getString(4);
		this.bDate = rs.getDate(5);
		this.address = rs.getString(6);
		this.sex = rs.getString(7);
		this.salary = rs.getDouble(8);
		this.supervisorSsn = rs.getString(9);
		this.dNo = rs.getInt(10);
	}
	
	public Friend(String firstName, String mInit, String lastName,
			String ssn, Date bDate, String address, String sex, double salary,
			String supervisorSsn, int dNo) {
		super();
		this.firstName = firstName;
		this.mInit = mInit;
		this.lastName = lastName;
		this.ssn = ssn;
		this.bDate = bDate;
		this.address = address;
		this.sex = sex;
		this.salary = salary;
		this.supervisorSsn = supervisorSsn;
		this.dNo = dNo;
	}
	public String getFirstName() {
		return firstName;
	}
	public void setFirstName(String firstName) {
		this.firstName = firstName;
	}
	public String getmInit() {
		return mInit;
	}
	public void setmInit(String mInit) {
		this.mInit = mInit;
	}
	public String getLastName() {
		return lastName;
	}
	public void setLastName(String lastName) {
		this.lastName = lastName;
	}
	public String getSsn() {
		return ssn;
	}
	public void setSsn(String ssn) {
		this.ssn = ssn;
	}
	public Date getbDate() {
		return bDate;
	}
	public void setbDate(Date bDate) {
		this.bDate = bDate;
	}
	public String getAddress() {
		return address;
	}
	public void setAddress(String address) {
		this.address = address;
	}
	public String getSex() {
		return sex;
	}
	public void setSex(String sex) {
		this.sex = sex;
	}
	public double getSalary() {
		return salary;
	}
	public void setSalary(double salary) {
		this.salary = salary;
	}
	public String getSupervisorSsn() {
		return supervisorSsn;
	}
	public void setSupervisorSsn(String supervisorSsn) {
		this.supervisorSsn = supervisorSsn;
	}
	public int getdNo() {
		return dNo;
	}
	public void setdNo(int dNo) {
		this.dNo = dNo;
	}

	@Override
	public String toString() {
		return "Friend [firstName=" + firstName + ", mInit=" + mInit
				+ ", lastName=" + lastName + ", ssn=" + ssn + ", bDate="
				+ bDate + ", address=" + address + ", sex=" + sex + ", salary="
				+ salary + ", supervisorSsn=" + supervisorSsn + ", dNo=" + dNo
				+ "]";
	}
	
	
}
