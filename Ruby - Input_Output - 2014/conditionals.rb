require 'movie'

movie = Movie.new("godfather", 9)



# if movie.rank >= 10
#   puts "Hit"
# else
#   puts "Flop"
# end

# statment modifier

 puts "Hits" if movie.rank >= 10
 
 
if movie.rank <10
   puts "Flop"
 end

# best solution

# if movie.rank >=10
#   puts "Hit"
# else
#   puts "Flop"
# end