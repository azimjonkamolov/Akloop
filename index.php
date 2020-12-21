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
      <div class="index__search">
        <form class="index__form" action="search.php" methode="GET">
          <div>
            <input name="term" type="search" class="index__query" 
                   maxlength="512" autocomplete="off" title="Search"
                   aria-label="Search" dir="ltr" spellcheck="false"
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
          <li><a href="https://github.com/azimjonkamolov/Akloop">Github</a></li>
          <li><a href="https://azimjon.netlify.app/">Creator</a></li>
          <li><a href="sup/info.html">About</a></li>
        </ul>
      </div>
    </footer>
  </div>
</body>
</html>
