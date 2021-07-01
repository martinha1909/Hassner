<?php
  session_start();
?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Rateify - Rating Page</title>
    <meta name="description"
          content="Rateify is a music service that allows users to rate songs"/>

    <!--Inter UI font-->
    <link href="https://rsms.me/inter/inter-ui.css" rel="stylesheet">

    <!--vendors styles-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">

    <!-- Bootstrap CSS / Color Scheme -->
    <link rel="stylesheet" href="css/default.css" id="theme-color">
</head>
<body>


<!--navigation-->
<section class="smart-scroll">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-md navbar-dark">
            <a class="navbar-brand heading-black" href="index.php">
                Rateify
            </a>
            <button class="navbar-toggler navbar-toggler-right border-0" type="button" data-toggle="collapse"
                    data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span data-feather="grid"></span>
            </button>
            
        </nav>
    </div>
</section>

<!--Search Song-->
<section class="py-7 py-md-0 bg-hero" id="login">
    <div class="container">
        <div class="row vh-md-100">
            <div class="col-md-8 col-sm-10 col-12 mx-auto my-auto text-center">
                
              <!-- header -->
              <div style = "position:absolute; top: -200px;"class="col text-center">
                <h2>Card Information</h2>
              </div>
              <div style="position:absolute; right: 580px; bottom: 150px"class="col text-center">
                <a href="listener.php"> <- Your page</a>
              </div> 
              

                <form action="../APIs/PurchaseCoinsConnection.php" method="post">
                    <!-- Search field -->
                
                    <h6 style="position: absolute; top: -100px; right: 300px;">
                        Brand
                        <select name="brand">
                        <option value="0">MasterCard</option>
                        <option value="1">Visa</option>
                        <option value="2">American Express</option>
                        </select>
                    </h6>
                    <h6 style="position: absolute; top: -35px; right: 600px;">Card Number</h6>
                    <div style ="position:absolute; top: -40px; right: 250px;"class="form-group">
                      
                      <input name = "card_number" type="search" class="form-control" id="SongName" aria-describedby="SearchSongHelp" placeholder="Card number">
                    </div>

                    <h6 style="position: absolute; top: 20px; right: 610px;">Expiry Date</h6>
                    <div style ="position:absolute; top: 15px; right: 250px;"class="form-group">
                      
                      <input name = "expiry_date" type="search" class="form-control" id="SongName" aria-describedby="SearchSongHelp" placeholder="MM/YY">
                    </div>

                    <h6 style="position: absolute; top: 75px; right: 610px;">Card Holder</h6>
                    <div style ="position:absolute; top: 70px; right: 250px;"class="form-group">
                      
                      <input name = "Card_holder" type="search" class="form-control" id="SongName" aria-describedby="SearchSongHelp" placeholder="Card Holder">
                    </div>

                    <h6 style="position: absolute; top: 130px; right: 650px;">CVV</h6>
                    <div style ="position:absolute; top: 125px; right: 250px;"class="form-group">
                      
                      <input name = "cvv" type="search" class="form-control" id="SongName" aria-describedby="SearchSongHelp" placeholder="CVV">
                    </div>

                    <h6 style="position: absolute; top: 185px; right: 630px;">Zip code</h6>
                    <div style ="position:absolute; top: 180px; right: 250px;"class="form-group">
                      
                      <input name = "zip_code" type="search" class="form-control" id="SongName" aria-describedby="SearchSongHelp" placeholder="Zip Code">
                    </div>

                    <div style = "position: absolute; bottom: -300px; right: 325px"class = "positioning">
                        <input type = "submit" class="btn btn-primary" role="button" aria-pressed="true" name = "button" value = "Purchase!" onclick='window.location.reload();'>
                    </div>
                   
                </form>
                
                <!-- <div class="form-group">
                    Employee List :  
                        <select>  
                        <option value="Select">Select</option>}  
                        <option value="Vineet">Vineet Saini</option>  
                        <option value="Sumit">Sumit Sharma</option>  
                        <option value="Dorilal">Dorilal Agarwal</option>  
                        <option value="Omveer">Omveer Singh</option>  
                        <option value="Rohtash">Rohtash Kumar</option>  
                        <option value="Maneesh">Maneesh Tewatia</option>  
                        <option value="Priyanka">Priyanka Sachan</option>  
                        <option value="Neha">Neha Saini</option>  
                        </select>  
                </div> -->

                <!--GO Back Button [idk how to move it further down]-->
                
            </div>
        </div>
       
        
    </div>
    
   
</section>

<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.7.3/feather.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js"></script>
<script src="js/scripts.js"></script>
</body>
</html>