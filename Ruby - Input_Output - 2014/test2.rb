# greeting = "hello"
# puts greeting








#
#  greeting = String.new("Hello")
# puts greeting
# greeting.upcase
# # 
# puts greeting.reverse
# # 
# farewell = String.new("Goodbye")
#  puts farewell
# 
# # 
#  puts greeting.object_id
# # 
#  puts farewell.object_id
# 
#  t1 = Time.new
#  puts t1.sec
# t2 = Time.new
#  puts t2.sec
#  puts t1.object_id
#  puts t2.object_id


class Movie
 end
# 
# movie1 = Movie.new 
#  puts movie1.object_id
#  movie2 = Movie.new 
#  puts movie2.object_id
#  movie1 = Movie.new("goonies", 10)
#  movie2 = Movie.new("ghostbusters", 9) 
# 


# class Movie
# 
#  def initialize(title, rank=0)
#    puts "created a movie object with title #{title} and a rank of #{rank}"
#  end
#  
#  
#  end
# 
#  movie1 = Movie.new("goonies", 10)
#  movie2 = Movie.new("ghostbusters")




# class Movie
# #  
#   def initialize(title, rank)
#     puts "created a movie object with title #{title} and a rank of #{rank}"
#   end
# # 
#  def listing
#  "#{title} has a rank of #{rank}"
#  end
#  
#  end
#  
#  
#  
#  movie1 = Movie.new("goonies", 10)
#  puts movie1.listing


# class Movie
#    def initialize(title, rank)
#      @title = title
#      @rank = rank
#     puts "created a movie object with title #{title} and a rank of #{rank}"
#    end
#   def listing
#    "#{@title} has a rank of #{@rank}"
#   end
#  end
#  
 
 # movie1 = Movie.new("goonies", 10)
 # puts movie1.listing


# 
class Movie
   def initialize(title, rank=0)
     @title = title
     @rank = rank
 end
  def listing
 "#{@title} has a rank of #{@rank}"
 end
 end
# # 
#  movie1 = Movie.new("goonies", 10)
#  puts movie1.listing
# # 
#  movie2 = Movie.new("gohstbusters", 9)
#  puts movie2.listing
# # 
#  movie3 = Movie.new("goldfinger")
#  puts movie3.listing
# # 
# 
# 
# 
# 
# 
# 
# puts movie1
#  puts movie2
#  puts movie3





# class Movie
#    def initialize(title, rank=0)
#      @title = title
# #     @rank = rank
#  end
#   def to_s
#  "#{@title} has a rank of #{@rank}"
#  end
#  end
#  movie1 = Movie.new("goonies", 10)
#  puts movie1
#  movie2 = Movie.new("ghostbusters", 9)
#  puts movie2 
#  movie3 = Movie.new("goldfinger")
#  puts movie3



class Movie
   def initialize(title, rank=0)
     @title = title
     @rank = rank
    
   end
 def to_s
 "#{@title} has a rank of #{@rank}"
   end
  
   def thumbs_up
     @rank = @rank +1
  end
   def thumbs_down
     @rank = @rank -1
 end
 end
# 
 movie1 = Movie.new("goonies", 10)
 puts movie1
 movie1.thumbs_up
  puts movie1
# # 
 movie2 = Movie.new("ghostbusters", 9)
  puts movie2
  movie2.thumbs_down
 puts movie2
# # 
 movie3 = Movie.new("goldfinger")
  puts movie3














