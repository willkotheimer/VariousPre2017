public class Rectangle implements FigureGeometry {

	protected int width; //sets the width of this instance of rectangle
	protected int height; //sets the height of this instance of the rectangle
	protected int scale;
	
	public Rectangle (int scale, int width, int height){
		this.width=width;
		this.height=height;
		this.scale=scale;} //constructor of rectangle
	//takes in a variables for width, height at time of 
	// rectangle's instance.
	
	//returns area of rectangle
	public double area() {
		//computes the area of the rectangle
		return width * height;
	}

	//returns perimeter of rectangle
	public double perimeter() {
		//computes the object instance rectangle's perimeter.
		return 2 * width + 2 * height;
	}

	//sets the scale of the rectangle
	public void setScale(int scale) {
		
		this.scale= scale;
	}
	
	public double weight() {
		// computes the object instance rectangle 
		//weight from area and scale parameters
		//parameter.
		return area()*scale;
	}
	
	

}

