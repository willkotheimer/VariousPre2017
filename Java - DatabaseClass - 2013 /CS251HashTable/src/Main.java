import java.util.HashMap;
import java.util.LinkedHashMap;


public class Main {

	/**
	 * @param args
	 */
	public static void main(String[] args) {
		HashMap<Game, Game> map = new LinkedHashMap<Game, Game>();
		
		long timeAccumulated = 0;
		
		for(int i = 0; i < 1000000; i++) {
			Game game = Game.gen();
			
			long start = System.nanoTime();
			boolean exists = map.containsKey(game);
			long end = System.nanoTime();
			timeAccumulated += (end-start);
			if(!exists)
				map.put(game, game);
		}
		
		System.out.println("Entries:" + map.size());
		System.out.println("Nano seconds:" + timeAccumulated);

	}

}
