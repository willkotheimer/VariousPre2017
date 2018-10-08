<?php get_header(); ?>




<div class="section s1">
    <div class="inner ">
        <div class="topborder"></div>
        <div class="top1"><img src="<?php echo get_bloginfo( 'template_directory' ).'/images/'; ?>logo.png" style="height:100px; width: auto; " />
        </div>
        <div class="top2"><span>San Jose Fiesta</span>
        </div>
        <section class="main">
            <div class="mainimage">

                <div class="twitter-roll">

                    <span class="news">
			
	
				<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		 <h1 class="twitter"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
<?php the_title(); ?></a></h1>
				<p class="twitter">
					<php the_excerpt(); ?>
				</p>  
			<?php endwhile; endif; ?>
			</span>


                    <div>
                        <!--transparency-->
                    </div>



                </div>
        </section>
        </div>
    </div>

    <div class="section s2">
        <div class="inner">



            <div class="barsspace menubar">MENU</div>


            <div class="menu">
                <div class="module module1">
                    <a style="color: #ac181f; font-family: " Khand ", Arial; font-size: 26px;" href="<?php echo get_bloginfo( 'template_directory' ).'/images/'; ?>Menu.pdf">Download Menu</a>
                    <br/>
                    <h1><img src="<?php echo get_bloginfo( 'template_directory' ).'/images/'; ?>boxinggloves-new_04.png" alt="leftglove" style="height:30px; width: auto; " />
PLATILLOS MEXICANOS / MEXICAN PLATES<img src="<?php echo get_bloginfo( 'template_directory' ).'/images/'; ?>boxinggloves-new_03.png" alt="rightglove" style="height:30px; width: auto; " />
</h1>
                    <br/>

                    <h2>Fajitas...$13.99</h2>
                    <h3>Beef or Chicken</h3>
                    <br/>
                    <h2>Fajitas...$15.99</h2>
                    <h3>Beef, Chicken, and Shrimp</h3>
                    <br/>
                    <h2>Alambre Chorizo...16.90</h2>
                    <h3>Beef, shrimp, beef with cheese</h3>
                    <br/>
                    <h2>Fajitas for Two...$20.99</h2>
                    <h3>Beef or Chicken and Shrimp</h3>
                    <br/>
                    <h2>Pechuga Empanizga...$12.99</h2>
                    <h3>Breaded Chicken Breast</h3>
                    <br/>
                    <h2>Pechuga a la Plancha...$12.99</h2>
                    <h3>Grilled Chicken Breast</h3>
                    <br/>
                    <h2>Mole de Pollo...$12.99</h2>
                    <br/>
                    <h2>Red Chicken Mole.
Menudo...$12.99</h2>
                    <br/>
                    <h2>Higado Encebollado...$12.99</h2>
                    <h3>Beef liver cooked with grilled onions.</h3>
                    <br/>
                    <br/>
                    <br/>


                </div>

                <!--module module2 c1r1 -->
                <div class="module module2">
                    <h2>Chile Colorado a Verde...$12.99</h2>
                    <h3>Sliced Beef or Pork Tips, grilled together with 
sliced onions, tomato and our famous sauce.</h3>
                    <br/>
                    <h2>Grilled Chicken Salad...$10.99</h2>
                    <br/>
                    <h2>Grilled Beef Salad... $10.99</h2>
                    <br/>
                    <img src="<?php echo get_bloginfo( 'template_directory' ).'/images/'; ?>4Z6A3600.JPG" alt="pollo loco" style="height:200px; width: auto; " />
                    <h2>Pollo Loco. . .12.99</h2>
                    <h3>Rice with Grilled Chicken, topped with melted 
cheese</h3>
                    <br/>
                    <h2>Bistec a la Mexicana... $12.99
<h3>Steak Mexican style, sliced sirloin, 
onion, tomatoes, jalapeño peppers, 
grilled to perfection.</h3><br/>
<h2>Carne Asada...$12.99</h2>
                    <h3>Sliced charbroiled sirloin, topped with 
grilled onions, salad and sliced 
avocado.</h3>
                    <br/>
                    <h2>Milanesa...$12.99</h2>
                    <h3>Mexican style country fried steak, 
cooked to golden brown.</h3>
                    <br/>
                    <h2>Chuletá de Puerco...$12.99</h2>
                    <h3>Two grilled pork chop.</h3>
                    <br/>
                    <h2>Shrimp salad $12.99</h2>
                    <br/>
                    <h2>Fish Salad $12.99 <br/></h2>

                </div>

                <!--C1- R2-->

                <div class="module module1R2">

                    <!--
     
<h1>Seafood</h1><br/>
<h2>Filete de Pescado...$14.99</h2>
<h3>Fish fillet with chipotle Sauce Grilled.</h3><br/>
<h2>Mojarra Frita al Ajillo
y Chipotle...$13.99</h2>
<h3>Fried Red snapper with garlic and 
chipotle sauce.</h3>
<h2>Oysters...half $11.99</h2><br/>
<h2>Full...23.99
Agua Chiles...16.99</h2><br/>
<h2>Shrimp Salad. . .  $11.99 </h2>  <br/> 
<h2>Fish Salad . . .$11.99</h2><br/> -->
                    <br/>
                    <br/>
                    <h1><h1><img src="<?php echo get_bloginfo( 'template_directory' ).'/images/'; ?>boxinggloves-new_04.png" alt="leftglove" style="height:30px; width: auto; " />
KIDS MENU<img src="<?php echo get_bloginfo( 'template_directory' ).'/images/'; ?>boxinggloves-new_03.png" alt="rightglove" style="height:30px; width: auto; " />
</h1></h1>
                    <br/>
                    <h2>Taco Frijol y Arroz... $4.99</h2>
                    <h3>Rice and beans taco. Choice of meat.</h3>
                    <br/>
                    <h2>1 Burrito de Frijol y Arroz... $4.99</h2>
                    <h3>Rice and beans burrito. Choice of 
meat.</h3>
                    <br/>
                    <h2>6 Chicken Nuggets con 
Papas...$4.99</h2>
                    <br/>
                    <h2>1 Quesadilla de Frijoles y 
Arroz...$4.99</h2>
                    <br/>
                    <h3>Choice of meat.</h3>
                    <br/>
                    <h1><img src="<?php echo get_bloginfo( 'template_directory' ).'/images/'; ?>boxinggloves-new_04.png" alt="leftglove" style="height:30px; width: auto; " />
DRINKS<img src="<?php echo get_bloginfo( 'template_directory' ).'/images/'; ?>boxinggloves-new_03.png" alt="rightglove" style="height:30px; width: auto; " />
</h1>
                    <br/>
                    <h2>Fountain drinks $3.00</h2>
                    <h3>Free refill</h3>
                    <br/>
                    <h2>Bebidas Mexicanas $4.00</h2>
                    <br/>
                    <h2>Beer $4.00</h2>
                     <br/>
                    <br/>
                    <img src="<?php echo get_bloginfo( 'template_directory' ).'/images/'; ?>4Z6A3530.JPG" alt="Botana Fiesta" style="height:200px; width: auto; " />
                    <br/>
                    <h1>Botana Fiesta</h1>
                    <h3>Servido con carne asada, 
chorizo, lengua, pollo, carnitas, cabeza y pastor, cebolla y 
chiles toreados.</h3>
                    <br/>
                    <h2>1 Personas...$18.99</h2>
                    <h2>2 Personas...$29.99</h2>
                    <h2>Grande 4 Personas...$44.99</h2>

                    <h2>Grande 8 Personas...$74.99</h2>
                    <br/>
                </div>

                <!--module module2 r2 c2 
      <div class="module module2"> 
  <h2>Camarones a la 
Mexicana...$14.99</h2>
<h3>Shrimp grilled with onion, tomato, 
jalapeños, rice and tortilla.</h3><br/>
<h2>Camarones al Mojo de Ajo... 
$14.99</h2>
<h3>Shrimp sauted with our special garlic 
breaded served with rice, tortillas and 
tostidas.</h3><br/>
<h2>Camarones a la Plancha... $14.99</h2>
<h3>Grilled shrimp served with rice, 
beans, salad and tortillas.</h3><br/>
<h2>Camarones Jaliso...$17.99</h2>
<h3>8 jumbo shrimp wrapped with bacon, 
cooked to a golden brown, served with 
rice, tortillas, and tostadas.</h3><br/>
<h2>Tostadas de Cerviche
$5.99</h2><br/>
<h2>Tostadas de Camaron
$5.99</h2><br/>
<h2>Caldo de Camaron
Shrimp Soup. . . 14.99</h2>
<h3>Shrimp boiled with a mix of vegetables and our special soup.</h3><br/>
<h2>Caldo de Mariscos
Sea food soup. . . $15.99</h2>
<h3>A combo of shrimp, fish, octopus 
and oysters boiled with a mix of 
vegetables and our special soup.</h3><br/>
<h2>Coctail de Camaron
Shrimp Coctail. . . $14.99</h2>
<h3>12 large shrimp served with our 
special tomato sauce, chopped 
avocados and pico de gallo.</h3><br/>
<h2>Campechana. . . $14.99</h2>
<h3>Shrimp combo with sea food mix.
Avocado, pico de gallo and our 
special tomato sauce.</h3><br/>

     </div>

     <div class="module module1"> 
<h2>Chilaquiles...12.99</h2>
<h3>Green or Red. Friend cut tortillas with onions and tomatoes, choice of meat or eggs. Choice of tortillas or bolillo.</h3>
<br/><h2>Blanquillos (Eggs)...$7.99</h2>
<h3>Served with rice, beans, and tortillas.</h3>
<br/><h2>Rancheros</h2>
<h3>Eggs over easy and topped with spicy red sauce.</h3>
<br/><h2>Con Chorizo</h2>
<h3>Scramble eggs with mexican sausage.</h3>
<br/><h2>A la Mexicana</h2>
<h3>Scrambled eggs with chopped onions, tomatoes and bell peppers.</h3>
<br/><h2>Menudo...$12.99 </h2>
<br/><h2>Mole de Pollo...12.99</h2>
<h3>Red chicken mole.</h3>
<br/><h2>Higado Encebollado...$12.99</h2>
<br/><h2>Chile Colorado o Verde...12.99</h2>
<h3>Sliced beef or pork tips, grilled together with sliced onions, tomato and our famous sauce.</h3>
<br/><h2>RICOS TAQUITOS...$2.50</h2>
<br/><h2>Came al gusta (choice meat)</h2>
<h3>Asada (Steak), Cachate (Cheek meat), Chorizo (Mexican Sausage), Tripa (Tripe boiled and toasted), Carnitas
y Buche (Fried Pork), Pollo (Chicken with Green Sauce), Chicharron (Por with Green Sauce), Pastor (Marinated Pork with red Sauce and onions), Lengua (Beef Tong)</h3>
<br/><h2>Tacos de Camaron. . . $4.99</h2> 
<br/><h1>Antojitoa (Appetizer)</h1>
<br/><h2>Bean Dip...$4.99</h2>
<br/><h2>Guacamole Dip...$4.99</h2>
<br/><h2>Cheese Dip...4.99</h2>
<h2>Cheese Dip with Chorizo...$4.99 Wings half ... $7.99 Full ...$16.00</h2>
     </div> -->

                <div class="module module2">
                    <br/>
                    <br/>
                    <h1><img src="<?php echo get_bloginfo( 'template_directory' ).'/images/'; ?>boxinggloves-new_04.png" alt="leftglove" style="height:30px; width: auto; " />
KNOCKOUT FAVORITES<img src="<?php echo get_bloginfo( 'template_directory' ).'/images/'; ?>boxinggloves-new_03.png" alt="rightglove" style="height:30px; width: auto; " />
</h1>
                    <br/>
                    <h2>Sopes… $4.99</h2>
                    <br/>
                    <h3>Served with your choice of meat, sour cream, cheese and lettuce. Servidos con came al gusto, frijoles, crema, queso y lechuga.</h3>
                    <br/>
                    <h2>Huarache… $6.99</h2>
                    <br/>
                    <h3>Served with your choice of meat, sour cream, cheese and lettuce. Servidos con carne al gusto.</h3>
                    <br/>
                    <h2>Tortas… $9.99 </h2>
                    <br/>
                    <h3>Mexican Sandwich, toasted bread, lettuce, tomato, sour cream, beans and meat. Jamon o carne al gusto.</h3>
                    <br/>
                    <img src="<?php echo get_bloginfo( 'template_directory' ).'/images/'; ?>4Z6A3648.JPG" alt="torta" style="height:200px; width: auto; " />

                    <h2>Torta Cubana $13.99 	</h2>
                    <br/>

                    



                    <h2>Nachos… $9.99 </h2>
                    <br/>
                    <h3>	Served with your choice of meat. Servidos con carne al gusto.</h3>
                    <br/>

                    <h2>Flautas… $9.99 </h2>
                    <br/>
                    <h3>	3 fried corn tortillas wrapped around your favorite filling. 3 fautas rellenas con carne al gusto.</h3>
                    <br/>
                    <h2>Burrito… $9.99 </h2>
                    <br/>
                    <h3>Served with your choice of meat. Servidos con carne al gusto.</h3>
                    <br/>
                    <h2>Quesadilla… $9.99 </h2>
                    <br/>
                    <h3>Served with rice and beans, your choice meat (with Shrimp $10.99) Servidos con carne al gusto.</h3>
                    <br/>
                    <h2>Taco Salad… $9.99 </h2>
                    <br/>
                    <h3>Served with rice and beans, your choice meat. Servidos con carne al gusto.</h3>
                    <br/>
                    <h2>Enchilada… $9.99 </h2>
                    <br/>
                    <h3>3 corn tortillas stufed with your choice meat covered with green or red sauce. 3 enchiladas con carne al gusto.</h3>
                    <br/>
                    <h2>Chimichanga… $9.99 </h2>
                    <br/>
                    <h3>Served with rice and beans on the side your choice meat. Servidos con carne al gusto.</h3>
                    <br/>
                    <img src="<?php echo get_bloginfo( 'template_directory' ).'/images/'; ?>4Z6A3566.JPG" alt="nachos" style="height:200px; width: auto; " />

                    <h2>Nachos Mix... $14.99</h2>
                    <br/> </h2>
                    <br/>
                </div>

               

            </div>
        </div>
    </div>



    <div class="section s3">
        <div class="inner">
            <div class="barsspace aboutus">ABOUT US</div>
            <section>
                <div class="woodbackground">
                    <div class="contain-celebration">
                        <ul>
                            <!--some flair-->
                            <li>

                                <ul>
                                    <li class="celebrate1">San Jose Fiesta</li>
                                    <li class="celebrate2">Ramon Arellano</li>
                                    <li class="celebrate3">Real Boxing</li>
                                    <li class="celebrate4">Authentic Mexican</li>
                                </ul>


                            </li>

                        </ul>
                        <div class="celebratesecondimg"><img src="<?php echo get_bloginfo( 'template_directory' ).'/images/'; ?>RamonandSon.png" />
                        </div>
                        <div class="celebratethirdimg"><img src="<?php echo get_bloginfo( 'template_directory' ).'/images/'; ?>Arellano.png" />
                        </div>

                    </div>
                    <div class="celebrate5">
                        <?php echo do_shortcode( '[widget id="spot-2"]'); ?>
                        <!-- Located in the heart of Nashville's Mexican restaurant district, the San Jose Fiesta is a great place to experience authentic Mexican food.  San Jose Fiesta is known for its delicious food and drinks, great lunch atmosphere, culture, sports, music and live boxing.-->
                    </div>
                </div>
                <div style="display:none" class="woodbackground show"></div>
            </section>

        </div>
    </div>

    <!--EVENTS <div class="section s4">
		<div class="inner">
			<h1>Events</h1>
		</div>
	</div>-->

    <div class="section s5">
        <div class="inner">
            <div class="barsspace contact">CONTACT INFO</div>
            <div class="module Contactmodule1">
                <section class="importantinfo">
                    <div class="infosections">
                        <ul>
                            <li class="section1">
                                <script src='https://maps.googleapis.com/maps/api/js?v=3.exp'></script>
                                <div class="googlemap" style='overflow:hidden;height:300px;width:400px;'>
                                    <div id='gmap_canvas' style='height:300px;width:300px;'></div>
                                    <div><small><a href="http://embedgooglemaps.com">									embed google maps							</a></small>
                                    </div>
                                    <div><small><a href="http://www.autohuren.world/">auto huren</a></small>
                                    </div>
                                    <style>
                                        #gmap_canvas img {
                                            max-width: none!important;
                                            background: none!important
                                        }
                                    </style>
                                </div>
                                <script type='text/javascript'>
                                    function init_map() {
                                        var myOptions = {
                                            zoom: 10,
                                            center: new google.maps.LatLng(36.0836969, -86.70163939999998),
                                            mapTypeId: google.maps.MapTypeId.ROADMAP
                                        };
                                        map = new google.maps.Map(document.getElementById('gmap_canvas'), myOptions);
                                        marker = new google.maps.Marker({
                                            map: map,
                                            position: new google.maps.LatLng(36.0836969, -86.70163939999998)
                                        });
                                        infowindow = new google.maps.InfoWindow({
                                            content: '<strong>San Jose Fiesta</strong><br>99 Wallace Rd. Nashville, TN 37211<br>'
                                        });
                                        google.maps.event.addListener(marker, 'click', function() {
                                            infowindow.open(map, marker);
                                        });
                                        infowindow.open(map, marker);
                                    }
                                    google.maps.event.addDomListener(window, 'load', init_map);
                                </script>
                            </li>
                            <li class="white section3">
                                <div class="creditcards">
                                    <div class="info">
                                        <h2>Location and <br/>
			Contact information:</h2>
                                        <h3>99 Wallace Drive, <br/>
			Nashville, TN 37211<br/>
			(615) 891-3007<br></h3>
                                        <h2>Open 4pm-3am</h2>
                                        <br/>
                                    </div>
                                    <ul>
                                        <li><img src="<?php echo get_bloginfo( 'template_directory' ).'/images/'; ?>visa.png" width="50" height="auto" />
                                        </li>
                                        <li><img src="<?php echo get_bloginfo( 'template_directory' ).'/images/'; ?>mastercard.png" width="50" height="auto" />
                                        </li>
                                        <li><img src="<?php echo get_bloginfo( 'template_directory' ).'/images/'; ?>discover.png" width="50" height="auto" />
                                        </li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </section>
            </div>
            <div class="module Contactmodule2">
                <div class="fb-page facebook" data-href="https://www.facebook.com/sanjosefiesta/?rf=296957700316224" data-tabs="timeline" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
                    <div class="fb-xfbml-parse-ignore">
                        <blockquote cite="https://www.facebook.com/sanjosefiesta/?rf=296957700316224"><a href="https://www.facebook.com/sanjosefiesta/?rf=296957700316224">San Jose Fiesta</a>
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>
    </div>




    <div class="section s6">
        <div class="inner">
            <div class="barsspace events">EVENTS</div>


            <section class="Eventmodule1">

                <?php echo do_shortcode( '[widget id="tribe-events-list-widget-2"]'); ?>
        </div>


        <img src="<?php echo get_bloginfo( 'template_directory' ).'/images/'; ?>MARQUEE-2.png" style="max-width:100%; height: auto; " />


    </div>

    </section>

</div>
</div>



</div>


<?php get_footer(); ?>