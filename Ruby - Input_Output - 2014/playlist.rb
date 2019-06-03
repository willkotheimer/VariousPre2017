require  'movie'
require 'waldorf_and_statler'
class Playlist
  def initialize(name)
    @name = name
    @movies =[]
  end
  def add_movie(movie)
    @movies << movie
  end
 
def print_stats
  puts "\n #{@name}'s Stats:"
  
  hits, flops =@movies.partition {|movie| movie.hit?}
  
  puts "\nHits:"
  puts hits.sort
  
  puts "\nFlops:"
  puts flops.sort
end
 
  def load(from_file)
    File.readlines(from_file).each do  |line|
     add_movie(Movie.from_csv(line))
    end
  end 
  
   def save(to_file="movie_rankings.csv")
     File.open(to_file, "w") do  |file|
      @movies.sort.each do |movie|
        file.puts movie.to_csv  
      end
   end
   end
   
 
  def play(viewings)
    puts "#{@name}'s playlist"
    puts @movies.sort
    puts @movies
    1.upto(viewings) do |count|
      puts "\nViewing #{count}"
    @movies.each do |movie|
      WaldorfAndStatler.review(movie)
      puts movie
    end
 end
end  
end