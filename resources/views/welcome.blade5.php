<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>
    </head><body>
<div id="container">
  <!-- BEGAIN PRELOADER -->
  <div id="preloader" style="display: none;">
    <div id="status" style="display: none;">&nbsp;</div>
  </div>
  <!-- END PRELOADER -->


  <!-- Start menu area -->
<div class="header">
  <div class="nav-area">
    <a id="menu-bt" href="http://teqneia.com"><i class="fa fa-home"></i></a>
    <!--<nav class="main-nav" id="main-menu">
      <span class="fa fa-remove" id="close"></span>
      <ul>
        <li> <a href="#header"><i class="fa fa-home"></i><span>Home</span></a></li>
        <li><a href="#Aboutus"><i class="fa fa-server"></i><span>About Us</span></a></li>

        <li><a href="#services"><i class="fa fa-usd"></i><span>Services</span></a></li>
        <li><a href="#News"><i class="fa fa-image"></i><span>News</span></a></li>
        <li><a href="#joinus"><i class="fa fa-download"></i><span>Join us</span></a></li>
        <li><a href="#CaP"><i class="fa fa-certificate"></i><span>Clients And Projects</span></a></li>
        <li><a href="#contact"><i class="fa fa-envelope-o"></i><span>Contact Us</span></a></li>
      </ul>
    </nav>-->
  </div>
  <!-- End menu area -->
</div>
  <div id="body">
  <!-- Start download app -->
  <section id="joinus">
    <div class="">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="title-area wow fadeInUp" style="visibility: visible; animation-name: fadeInUp;">
              <h2 class="main-h2">apply for our<span> jobs now</span></h2>
              <p style="margin-top: 60px;">Here in TEQNEIA we are always happy to meet new life filled passionate individuals, feel free to leave your cv or apply to one of our open jobs!</p>
            </div>
          </div>
          <!-- Start download app content -->
          <div class="download-app-content training-form">
        <form class="footerform" id="form1" name="form1" method="POST" enctype="multipart/form-data" onsubmit="submitForm()">
            @csrf
        <div class="form-group  col-md-12">
        <label for="" class="form-title">Job Type  </label>
        <select class="form-control num green" onblur="return check(this,'number');" name="JobType" id="JobType" required="">
            <option value="8">React native Developer</option>
        </select>
    </div>
  <div class="form-group col-md-8">
    <label for="" class="form-title"> Name</label>
    <input type="text" class="form-control green" onblur="return check(this,'Username');" id="Name" name="Name" placeholder="Name" required="" value="Zeyad">
  </div>
  <div class="form-group  col-md-4">    
    <label for="" class="form-title">Gender</label>
    <div class="">
  <select class="form-control num green" onblur="return check(this,'number');" name="Gender" required="">
      

   <option value="" disabled="" selected="">Select Gander</option><option selected="" value="1">Male</option><option value="2">Female</option>  </select>
  </div>
  </div>
  <div class="form-group  col-md-8">
    <label for="" class="form-title"> Email </label>
   <input type="email" class="form-control green" onblur="return check(this,'mail');" name="Email" placeholder="Your.Email@host.com" required="" value="test@gmail.com">
  </div>
  <div class="form-group col-md-4">
    <label for="" class="form-title"> Location </label>
     <select class="form-control num green" onblur="return check(this,'number');" name="Location" id="Location" required="">
         
     <option value="" disabled="" selected="">Select Location</option><option value="1">Giza (Zayed)</option><option selected="" value="2">Giza (October)</option><option value="3">Giza (Haram)</option><option value="4">Giza (Faisal)</option><option value="5">Giza (Mohandeseen)</option><option value="6">Giza</option><option value="7">Cairo (down Town)</option><option value="8">Cairo (Maadi)</option><option value="9">Cairo (Helwan)</option><option value="10">Cairo (Zamalek)</option><option value="11">Cairo (Nasr City/Heliopolice)</option><option value="12">Cairo (5th settlement)</option><option value="13">Cairo</option><option value="14">Alexandria</option><option value="15">Aswan</option><option value="16">Asyut</option><option value="17">Beheira</option><option value="18">Beni Suef</option><option value="19">Dakahlia</option><option value="20">Damietta</option><option value="21">Faiyum</option><option value="22">Gharbia</option><option value="23">Ismailia</option><option value="24">Kafr El Sheikh</option><option value="25">Luxor</option><option value="26">Matruh</option><option value="27">Minya</option><option value="28">Monufia</option><option value="29">New Valley</option><option value="30">North Sinai</option><option value="31">Port Said</option><option value="32">Qalyubia</option><option value="33">Qena</option><option value="34">Red Sea</option><option value="35">Sharqia</option><option value="36">Sohag</option><option value="37">South Sinai</option><option value="38">Suez</option>   </select>
  </div>
  <div class="form-group col-md-8">
    <label for="" class="form-title"> Online CV</label>
     <input type="url" name="Onlinecv" id="Onlinecv" onblur="return check(this,'url');" class="form-control green" placeholder="Online CV">
  </div>
  <div class="form-group  col-md-4">
    <label for="" class="form-title">Date of birth</label>
    <input type="date" name="Dateofbirth" onblur="return check(this,'date');" class="form-control green" id="Dateofbirth" max="2019-12-31" min="1979-12-31" placeholder="yyyy-mm-dd" required="" value="1998-08-22">
  </div>
  <div class="form-group  col-md-8">
    <label for="" class="form-title">Upload CV</label>
        <input type="file" onblur="return check(this,'cv');" name="ff[]" id="ff[]" class="form-control">
  </div>
  <div class="form-group col-md-4">

    <label for="" class="form-title">Mobile </label>
    <input type="tel" onblur="return check(this,'number');" class="form-control num green" name="Mobile" placeholder="Mobile" required="" value="999999999">

</div>
  <div class="form-group  col-md-8">
    <label for="" class="form-title ">LinkedIn Profile</label>
    <input type="text" class="form-control green" onblur="return check(this,'url');" name="LinkedIn" placeholder="your link profile" value="https://www.linkedin.com/in/zeyad-khalid-641958185/">
  </div>
  <div class="form-group col-md-4">
    <label for="salary" class="form-title"> Expected salary</label>
    <input type="number" onblur="return check(this,'number');" class="currency form-control num green" min="100" max="20000" value="111" id="salary" name="salary" required="">
  </div>
  <div class="form-group col-md-12 text-center">
  <button style="color: #fff;  font-weight:bold; border:hidden; background-color:#1b2851;border-radius: 16px; padding: 4px;" type="submit" name="submit" id="submit" class="formbtn col-md-4 col-md-offset-4  ">  Submit </button>
  
  </div>
</form>
</div>

 
 
</div></div>
  </div>
        </section></div>
      </div>
  </div>
        </section></div>
      </div>
    </div>
  </section>
  <!-- End download app -->
</div>

<div id="footer">
  <!-- Start footer -->
  <footer class="style-1">
            <div class="container">
                <div class="row">
                    <div class="col-md-4 col-xs-12" style="margin-top: 15px;">
                        <span class="copyright">Copyright © <a href="www.teqneia.com">TEQNEIA</a> 2018-19</span>
                    </div>
                    <div class="col-md-4 col-xs-12">
                        <div class="footer-social text-center">
                            <ul>
                                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                                <li><a href="https://www.facebook.com/TEQNEIA/"><i class="fa fa-facebook"></i></a></li>
                                <li><a href="https://www.linkedin.com/company/teqneia/"><i class="fa fa-linkedin"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-md-4 col-xs-12" style="margin-top: 15px;">
                        <div class="footer-link">
                            <ul class="pull-right">
                                <li><a href="#">Privacy Policy</a>
                                </li>
                                <li><a href="#">Terms of Use</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
  <!-- End -->
  </div>
</div>

  <!-- jQuery library -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <!-- Include all compiled plugins (below), or include individual files as needed -->
  <!-- Bootstrap -->
  <script src="assets/js/bootstrap.js"></script>

  <!-- Slick Slider -->
  <script type="text/javascript" src="assets/js/slick.js"></script>
  <!-- Add fancyBox -->
  <script type="text/javascript" src="assets/js/jquery.fancybox.pack.js"></script>
  <!-- Wow animation -->
  <script type="text/javascript" src="assets/js/wow.js"></script>
  <!-- Off-canvas Menu -->
  <script src="assets/js/classie.js"></script>

  <!-- Custom js -->
  <script type="text/javascript" src="assets/js/custom.js"></script>
  <script type="text/javascript" src="assets/js/custom-development.js"></script>
    <script>
        function submitForm() {
            event.preventDefault();
            event.stopPropagation();
            let form = document.getElementById("form1");
            let url = "/jobs/store";
            fetch(url, {
                method:"POST",
                body: new FormData(form)
            }).then(res=>{
                if(res.ok){
                    return res.json();
                }
            }).then(resJson=>{
                console.log(resJson);
            })
        }
    </script>
</body>
</html>
