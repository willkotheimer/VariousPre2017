
import java.util.InputMismatchException;
import java.util.Scanner;
public class Main {

		//Programmed created by Will Kotheimer
		//code adapted from Dr. Wang and Data Structures book.
		//CS II
		//date: September 7, 2011.
	
		//this is a program that prompts the user for parameters 
		//and then displays information about the 
		//circle and rectangle based on inputs by the user.
	
	//no compilation error, but logic error: 
	
	//it works if the user enters a number
	//I could not get the user input loop just right like I wanted.
	//Preferably, if user entered string value, or the wrong number
	//it wouldn't allow them. The try catches aren't working. They 
	//end the program instead. 
	//I appreciate if you could check this error
	//and suggest proper code. thanks!
	
	
	
	public static void main(String[] args) {
		
		Scanner in = new Scanner (System.in);
		
		
		float r= 0;
		boolean adouble=false;
		do {
			try
			{ adouble=true;
			System.out.println("Please input a number for radius:");
			r=in.nextFloat();
			}
			catch(InputMismatchException e)
			{
				System.out.println("Enter an number please.");
			}
		} while (!adouble);
		
		int n= 0;
		boolean awholenum=false;
		do {
			try
			{ awholenum=true;
			System.out.println("Please enter a whole number for a circle scale: ");
			n=in.nextInt();
			}
			catch(InputMismatchException e)
			{
				System.out.println("Enter an whole number please.");
			}
		} while (!awholenum);
	
		
		int h= 0;
		awholenum=false;
		do {
			try
			{ awholenum=true;
			System.out.println("Please enter a whole number for a rectangle height: ");
			h=in.nextInt();
			}
			catch(InputMismatchException e)
			{
				System.out.println("Enter an whole number please.");
			}
		} while (!awholenum);

		int w= 0;
		awholenum=false;
		do {
			try
			{ awholenum=true;
			System.out.println("Please enter a whole number for a rectangle width: ");
			w=in.nextInt();
			}
			catch(InputMismatchException e)
			{
				System.out.println("Enter an whole number please.");
			}
		} while (!awholenum);

		int j= 0;
		awholenum=false;
		do {
			try
			{ awholenum=true;
			System.out.println("Please enter a whole number for a rectangle scale: ");
			j=in.nextInt();
			}
			catch(InputMismatchException e)
			{
				System.out.println("Enter an whole number please.");
			}
		} while (!awholenum);
		
	
		
		
		
		Circle c1=new Circle (r,n);
		Rectangle r1 = new Rectangle(j,w,h);
		
		double circarea=c1.area();
		System.out.println("The area of the circle is"+
		circarea);
		double cperimeter=c1.perimeter();
		System.out.println("The perimeter of the circle is"+
		cperimeter);
		double cweight=c1.weight();
		System.out.println("The value of the weight of the circle is"+
		cweight);
		double rectarea=r1.area();
		System.out.println("The perimeter of the area of the  is"+
		rectarea);
		double rperimeter=r1.perimeter();
		System.out.println("The value of the weight of the rectangle is"+
		rperimeter);
		double rweight=r1.weight();
		System.out.println("The value of the weight of the circle is"+
		rweight);
		
		
		
		
		

	}
	
}
