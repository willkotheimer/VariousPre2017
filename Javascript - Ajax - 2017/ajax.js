
<script>
         
	function change_view (id) {
		if (id == 0) {
			/* Show the grid view */
			$('.button_grid').attr("src", "../../includes/images/view-active-grid.png");
			$('.button_grid').addClass("active");
			$('.tablet_reg_view').show();
			$('.desktop_reg_view').show();
            $('.add_button').show();
			/* Hide the list view*/
			$('.button_list').attr("src", "../../includes/images/view-list.png");
			$('.button_list').removeClass("active");
			$('.tablet_ajax_view').hide();
			$('.desktop_ajax_view').hide();
            
            
            
		} else {
			/* Hide the grid view*/
			$('.button_grid').attr("src", "../../includes/images/view-grid.png");
			$('.button_grid').removeClass("active");
			$('.tablet_reg_view').hide();
			$('.desktop_reg_view').hide();
            $('.add_button').hide();
			/* Show the list view */
			$('.button_list').attr("src", "../../includes/images/view-active.png");
			$('.button_list').addClass("active");
			$('.tablet_ajax_view').show();
			$('.desktop_ajax_view').show();       
		}
	}
</script>
                
  
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  
<script type="text/javascript">
    
    var Date = '2017-07-29';
    var empID = '793373';
    var legendArray= '{"550519":"color1"}';
 
    
$(document).ready(function(){

        $('#validationerror1').show();
        var pos = localStorage.getItem("pos");
        console.log(pos);
        $(pos).focus();
           
        var day1 = 1;
        var day2 = 2;
        var day3 = 3;
        var day4 = 4;
        var day5 = 5;
        var day6 = 6;
        var day7 = 7;
        
        $("#result1").empty();
        $("#result2").empty();
        $("#result3").empty();
        $("#result4").empty();
        $("#result5").empty();
        $("#result6").empty();
        $("#result7").empty();
        
       
        callAjax(Date,empID,day1,legendArray);
        callAjax(Date,empID,day2,legendArray);
        callAjax(Date,empID,day3,legendArray);
        callAjax(Date,empID,day4,legendArray);
        callAjax(Date,empID,day5,legendArray);
        callAjax(Date,empID,day6,legendArray);
        callAjax(Date,empID,day7,legendArray);  
        
         $("input").focus(function(){
         var id = this.className;
             console.log(id);
          
             var myarr = id.split("-");
             
             var idnum = id.match(/[0-9]+/g);
         
            console.log(idnum+" "+id);
             
        if((parseInt(idnum)+1)==15) { 
            
            idnum=1; 
             
             } else {
                 
             
           idnum=parseInt(idnum)+1;
             
             }
          
          var newid =  ".field"+idnum+"-"+"hours";
          
              
       
             
             
         localStorage.setItem("pos", newid ); 
         var pos = localStorage.getItem("pos");
             
          console.log("pos=="+pos);
        
     
});
    
         
  
    for(var forms=1; forms<=7; forms++) {
        
        var idtablet = 'form'+forms+'tablet';
        
      //  console.log("form"+forms);
        
       
            
            
        $('#'+idtablet).submit(function(){
    
       $.ajax({
        type: "GET",
        url: "postinghours.php",
        data: $(this).serialize(),
        cache: false,
        success: function(response){


        },
        error: function(response) {

            }
        });

        });

        var iddesktop = 'form'+forms+'desktop';

       $('#'+iddesktop).submit(function(){
           console.log("submitted");
       $.ajax({
        type: "POST",
        url: "postTimes.php",
        data: $(this).serialize(),
        cache: false,
        success: function(response){


        },
        error: function(response) {

            }
        });

        });

        
    }
    


    localStorage.setItem("Date", Date);
    localStorage.setItem("empID", empID);
                   
   
    
    function callAjax(Date,empID,day,legendArray) {
        
          $.ajax({
          url: "FetchTimes.php",
          type: "GET",
          data: {Date: Date,
                empID: empID,
                day: day,
                legendArray: legendArray,
                
                },
          cache: false,
          success: function(html){
                $("#result"+day+"tablet").append(html);
                $("#result"+day+"desktop").append(html);
          
            }
          });
    }
            
     
      /* Validate form fields */
            
  
            
  var hoursfields=[
                        '.field1-hours',
                        '.field2-hours',
                        '.field3-hours',
                        '.field4-hours',
                        '.field5-hours',
                        '.field6-hours',
                        '.field7-hours',
                        '.field8-hours',
                        '.field9-hours',
                        '.field10-hours',
                        '.field11-hours',
                        '.field12-hours',
                        '.field13-hours',
                        '.field14-hours'];
            
    var minfields=[
                        '.field1-min',
                        '.field2-min',
                        '.field3-min',
                        '.field4-min',
                        '.field5-min',
                        '.field6-min',
                        '.field7-min',
                        '.field8-min',
                        '.field9-min',
                        '.field10-min',
                        '.field11-min',
                        '.field12-min',
                        '.field13-min',
                        '.field14-min']; 
            
            
           
      
             for(i=0; i<minfields.length; i++) {
                
                $("input"+minfields[i]).on("keyup", function(e) {
	
                    var fullid = this.className;
                    var idnum2 = fullid.match(/[0-9]+/g);
                    idnum2=parseInt(idnum2)-1;
                    console.log("num"+idnum2);
                    var hoursfields=[
                        '.field1-hours',
                        '.field2-hours',
                        '.field3-hours',
                        '.field4-hours',
                        '.field5-hours',
                        '.field6-hours',
                        '.field7-hours',
                        '.field8-hours',
                        '.field9-hours',
                        '.field10-hours',
                        '.field11-hours',
                        '.field12-hours',
                        '.field13-hours',
                        '.field14-hours'];

                      var hourString = hoursfields[idnum2];
                    
                 
                    var hour = $("input"+hourString);
                    
                  
                    
                     var min_string = $(this).val();
                        
                       hourString = hour.attr('value');
                    
                       
                    
                       
                    
                        var stringlength = min_string.length;

                       if(stringlength==1 && (min_string>=0 && min_string<=9)) {
                           var flag=true;

                       } else {

                          var flag=false;
                       }
                      
                       hourString =  localStorage.getItem("hour");
                   
                    
                    if(min_string=="" ) {console.log(1);}
                    if(flag ) {console.log(2);}
                    if((parseInt(min_string)<=59) ) {console.log(3);}
                    if((min_string.match(/^[0-5][0-9]$/)) ) {console.log(4);}
                    if( (parseInt(hourString)<=12)) {console.log(5);}
                     if((hourString.match(/^[1-9][\040]|[0][1-9]|[1][1-2]$/)) ) {console.log(6);}
                    
                    
                    
                    
                       if(((min_string=="" || flag || ((parseInt(min_string)<=59) && (min_string.match(/^[0-5][0-9]$/))))) &&
                    (((parseInt(hourString)<=12) && (hourString.match(/^[1-9]|[1-9][\040]|[0][1-9]|[1][1-2]$/))))) {
                     

                          
                        
                           var fullid = this.className;
                           var idnum = fullid.match(/[0-9]+/g);
                           var classnum = parseInt(idnum);
                           var classid = '.validationerror'+classnum;
                           $(classid).hide(100);
                         
                       } else {

                          
                           var fullid = this.className;
                           var idnum = fullid.match(/[0-5]|[0-9]/g);
                           var classnum = parseInt(idnum);
                           var classid = '.validationerror'+classnum;
                           $(classid).show(100);
                           
                       }


                    });
    
             }
            
          
            
            
            for(i=0; i<hoursfields.length; i++) {
                
                $("input"+hoursfields[i]).on("keyup", function(e) {
	
                        // Check for valid number
                        var hour_string = $(this).val();
                    
                    console.log(hour_string);
                    
                        var newlength = hour_string.length;

                       if(newlength==1 && (hour_string>=0 && hour_string<=9)) {
                           var flag=true;

                       } else {

                          var flag=false;
                       }

                        localStorage.setItem("hour", hour_string);
                    
                        min_string =  localStorage.getItem("min");
                    
                    if(min_string=="" ) {console.log(1);}
                    if(flag ) {console.log(2);}
                    if((parseInt(min_string)<=59) ) {console.log(3);}
                    if((min_string.match(/^[0-5][0-9]$/)) ) {console.log(4);}
                    if( (parseInt(hour_string)<=12)) {console.log(5);}
                     if((hour_string.match(/^[1-9][\040]|[0][1-9]|[1][1-2]$/)) ) {console.log(6);}
                    
                    
                        if(((min_string=="" || flag || ((parseInt(min_string)<=59) && (min_string.match(/^[0-5][0-9]$/))))) &&
                    (hour_string=="" || ((parseInt(hour_string)<=12) && (hour_string.match(/^[1-9]|[\040][1-9]|[1-9][\040]|[0][1-9]|[1][1-2]$/))))) {

                        
                        
                           
                         
                           console.log("matches");
                           var fullid = this.className;
                           var idnum = fullid.match(/[0-9]+/g);
                           var classnum = parseInt(idnum);
                           var classid = '.validationerror'+classnum;
                           console.log(classid);
                          $(classid).hide(100);
                         
                       } else {

                           console.log("doesn't match"+hour_string);
                           var fullid = this.className;
                           var idnum = fullid.match(/\d{1,}/g);
                           var classnum = parseInt(idnum);
                           var classid = '.validationerror'+classnum;
                           console.log(classid);
                           $(classid).show(100);
                           
                       }


                    });
    
             }
            
            
    /* not used, for reference only:
    setInterval(function(){ 
        
    Date = localStorage.Date;
    empID = localStorage.empID;
    $("#result1desktop").empty();
    $("#result2desktop").empty();
    $("#result3desktop").empty();
    $("#result4desktop").empty();
    $("#result5desktop").empty();
    $("#result6desktop").empty();
    $("#result7desktop").empty();
        
    $("#result1tablet").empty();
    $("#result2tablet").empty();
    $("#result3tablet").empty();
    $("#result4tablet").empty();
    $("#result5tablet").empty();
    $("#result6tablet").empty();
    $("#result7tablet").empty();
        
    var day1 = 1;
    var day2 = 2;
    var day3 = 3;
    var day4 = 4;
    var day5 = 5;
    var day6 = 6;
    var day7 = 7;
    callAjax(Date,empID,day1,legendArray);
    callAjax(Date,empID,day2,legendArray);
    callAjax(Date,empID,day3,legendArray);
    callAjax(Date,empID,day4,legendArray);
    callAjax(Date,empID,day5,legendArray);
    callAjax(Date,empID,day6,legendArray);
    callAjax(Date,empID,day7,legendArray); 
        }, 60000);
    */
});
    
   