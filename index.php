<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Akloop</title>
  <meta name="description" value="The search engine that doesn't track you. Learn More.">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" type="text/css" href="./css/colors.css">
  <link rel="stylesheet" type="text/css" href="./css/modal.css">
  <link rel="stylesheet" type="text/css" href="./css/index.css">
  <link rel="icon" type="image/png" href="./img/logo.png">
  <meta name="author" value="vanGato">
</head>
<body>
  <div class="index">
    <div class="logo__header">
      <!-- <img src="img/logo.png" alt="logo" class="logo"> -->

      <li class="dropdown">
          <a href="javascript:void(0)" class="dropbtn">
            
              <div class="logo"></div>
              <div class="logo"></div>
              <div class="logo"></div>

          </a>
          <div class="dropdown-content">
            <a href="#">Text Qidir</a>
            <a href="#">Rasm Qidir</a>
            <a href="#">Blog Qidir</a>
          </div>
        </li>

    </div>



    <section class="index__header">
      <div class="index__logo"></div>
      <!-- <ul class="index__nav">
        <li class="index__active"><a></a></li>
        <li><a href="serp.htm"></a></li>
        <li><a href="serp.htm"></a></li>
        <li><a href="serp.htm"></a></li>
      </ul> -->
      <div class="index__search">
        <form class="index__form" action="search.php" methode="GET">
          <div>
            <input name="term" type="search"
                   class="index__query" 
                   maxlength="512"
                   autocomplete="off"
                   title="Search"
                   aria-label="Search"
                   dir="ltr"
                   spellcheck="false"
                   autofocus="autofocus"
            >
          </div>
          <button class="index__button" aria-label="Search" type="submit">
            <div class="index__ico"></div>
          </button>
        </form>
      </div>
    </section>
    <footer class="index__footer">
      <div class="index__bottom">
        <ul class="index__links">        
          <li><a href="#about">Technologies</a></li>
          <li><a href="#about">About</a></li>
          <li><a href="#about">Privacy</a></li>
          <!-- <li><a href="#about">Terms</a></li>
          <li><a href="#about">Settings</a></li> -->
        </ul>
        <!-- <p class="index__copyright">&copy; Azimjon Kamolov</p> -->
      </div>
    </footer>
  </div>
  <!-- Modal -->
  <!-- <div class="modal" id="about" aria-hidden="true">
    <div class="modal__dialog">
      <div class="modal__header">
        <h2>About</h2>
        <a href="#" class="modal__close" aria-hidden="true">×</a>
      </div>
      <div class="modal__body">
        <strong>Lorem Ipsum</strong><br>
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
      </div>
      <div class="modal__footer">
        <p>Last Update 01.01.2019</p>
      </div>
    </div>
  </div> -->
  <!-- /Modal -->
</body>
</html>
