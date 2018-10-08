import java.text.DateFormat;
import java.text.SimpleDateFormat;
import java.util.Date;


public class Utils {
	public static String dateToStringYMD(Date d) {
		DateFormat df = new SimpleDateFormat("yyyy-MM-dd");
		
		if(d == null)
			d = new Date();
		return df.format(d);
	}
}
