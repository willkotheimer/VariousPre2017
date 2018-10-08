
public class randomtest {

	/**
	 * @param args
	 * @return 
	 */
	private static int randBetween(int start, int end) {
		// TODO Auto-generated method stub
		return start + (int)Math.round(Math.random() * (end - start));
	}
	public static void main(String[] args) {
		// TODO Auto-generated method stub

		int a=1;
		int[] friendarray=RandomFriends(300,a);
		for(int x=1; x<=300; x++){
		System.out.println(a+" "+friendarray[x]);
		}
	}
	private static int[] RandomFriends(int random, int index) {
		// TODO Auto-generated method stub
		int[] friendarray;
    	friendarray = new int[random];
    	int rand;
    	
    	for(int x=1;x<=random; x++){
    		
    		rand = randBetween(1, random);
    		
    		for (int y=1; y<x; y++)
    		if ((rand!=index) && (rand!=friendarray[y])){
    			friendarray[x]=rand;
    		}
    		
    	}
		return friendarray;
    	
	}

}
