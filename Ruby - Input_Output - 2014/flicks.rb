require  'movie'
require  'playlist'
require 'Waldorf_and_Statler'
require 'files'

movie1 = Movie.new("goonies", 10)
movie2 = Movie.new("ghostbusters", 9)
movie3 = Movie.new("goldfinger", 15)

movies =[movie1, movie2, movie3]
                                        

playlist = Playlist.new("kermit")
#playlist.add_movie(movie1)
#playlist.add_movie(movie3)
#playlist.add_movie(movie2)
playlist.load(ARGV.shift || "movies.csv")

# playlist1.play(3)
# 
# 
# playlist1.print_stats

loop do 
  puts "\nHow many viewings? ('quit' to exit)"
  answer = gets.chomp.downcase
  case answer
  when /^\d+$/
    playlist.play(answer.to_i)
  when 'quit', 'exit'
    playlist.print_stats
    puts"I hope you enjoyed your viewings! Come back again..."
    break
  else
    puts "Please enter a number or 'quit'"
  end
end

playlist.save



  