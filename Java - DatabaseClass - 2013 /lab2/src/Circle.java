
public class Circle implements FigureGeometry {

	protected float radius; //sets the radius of this instance of Circle.
	protected int scale;//sets the scale of this instance of the Circle.
	
	//constructor for the circle variables.
	public Circle (float radius, int scale){
		this.radius=radius;
		this.scale=scale;} //constructor of circle
	//takes in a variable for radius at time of Circle instance.
	
	
	public double area() {
		//returns the area of the circle instance
		return PI * radius * radius;
	}

	public double perimeter() {
		// returns the perimeter of the Circle instance. 
		return 2 * PI * radius;
	}

	public void setScale(int scale) {
		// sets the object Circle instance variable scale to 
		//"scale" parameter
		this.scale= scale;
	}

	public double weight() {
		// sets the object Circle weight instance to weight
		//parameter
		return area()*scale;
	}
	
	

}
