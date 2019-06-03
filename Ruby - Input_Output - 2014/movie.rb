class Movie
  attr_reader :rank
  attr_accessor :title
  
  def initialize(title, rank=0)
    @title = title.capitalize
    @rank = rank
  end
  
 def hit?
   @rank >=10
 end
 def status
   if hit?
     "Hit"
   else
     "Flop"
   end
 end
 
  def to_s
    " #{@title} has a rank of #{@rank} (#{status})"

  end  
  
  
  def self.from_csv(line)
      title, rank = line.split(',')
     Movie.new(title,Integer(rank))
    end
   
    def to_csv
     "#{@title}, #{@rank}"
   end

def <=>(other_movie)
  other_movie.rank <=> @rank
end



  def thumbs_up
    @rank += 1
  end
  def thumbs_down
     @rank -= 1
   end
end

if __FILE__ == $0
   movie = Movie.new("goonies", 10)
puts movie
 
 end
















