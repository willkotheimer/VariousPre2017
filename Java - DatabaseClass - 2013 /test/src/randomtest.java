

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
		for(int x=0; x<=299; x++){
		System.out.println(a+" "+friendarray[x]);
		}
	}
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

}
