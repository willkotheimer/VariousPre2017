import java.util.Random;


public class Game {
	private String gameId;
	private String player1Name;
	private String play2Name;
	
	
	@Override
	public int hashCode() {
		final int prime = 31;
		int result = 1;
		result = prime * result + ((gameId == null) ? 0 : gameId.hashCode());
		return result;
		//return 0;
	}
	@Override
	public boolean equals(Object obj) {
		if (this == obj)
			return true;
		if (obj == null)
			return false;
		if (getClass() != obj.getClass())
			return false;
		Game other = (Game) obj;
		if (gameId == null) {
			if (other.gameId != null)
				return false;
		} else if (!gameId.equals(other.gameId))
			return false;
		return true;
	}
	public String getGameId() {
		return gameId;
	}
	public void setGameId(String gameId) {
		this.gameId = gameId;
	}
	public String getPlayer1Name() {
		return player1Name;
	}
	public void setPlayer1Name(String player1Name) {
		this.player1Name = player1Name;
	}
	public String getPlay2Name() {
		return play2Name;
	}
	public void setPlay2Name(String play2Name) {
		this.play2Name = play2Name;
	}
	
	
	
	
	public static Game gen() {
		Game game = new Game();
		game.setGameId(genRand());
		
		return game;
	}
	
	
	private static String genRand() {
		return genString(ALL_CHARS, rand, 10);
	}
	private static String ALL_CHARS = "abcdefghijklmnopqrstuvwxyz";
	private static Random rand = new Random();
	private static String genString(String alphabet, Random rand, int length) {
		char[] chars = new char[length];
		
		for(int i = 0; i < length; i++) {
			int loc = rand.nextInt(alphabet.length());
			
			chars[i] = alphabet.charAt(loc);
		}
		
		return new String(chars);
	}
	
}
