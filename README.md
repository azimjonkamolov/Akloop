<img src="https://github.com/mynameisone/Ecom/blob/master/images/Phoenix.png?raw=true" align="right" height="150"/>

<p float="left">
<img src="img/logoword1.png" width = "500" height="150" alt="Akloop" />

<!-- [![Awesome](https://cdn.rawgit.com/sindresorhus/awesome/d7305f38d29fed78fa85652e3a63e154dd8e8829/media/badge.svg)](https://github.com/sindresorhus/awesome#readme) -->

</p>

**This is a search engine powered by PHP**

---
<p float="left">
<img src="https://github.com/mynameisone/Main/blob/master/img/p2.PNG?raw=true" width = "400" height="250" alt="First pirture" /> 
<img src="https://github.com/mynameisone/Main/blob/master/img/P21.PNG?raw=true" width = "400" height="250" alt="First pirture" /> 
</p>

---

## Installation
To run this project, `XAMPP aplication` must be installed and then all the files must be put inside of `htdocs` which can be found where XAMPP is installed and after setting all the files inside of htdocs, XAMPP must be run and Apache and MySQL must be started then the project can be seen after running `localhost` if there are other files to then specific location must be showen such as `localhost/foulder_name/file_name.data_type` in browser however, some files are not shared here.
For further help please contact: `azimjon.6561@gmail.com`

## Used Languages and Frameworkes: ##

<p float="left">
<img src="https://github.com/mynameisone/Main/blob/master/img/PHP.png?raw=true" width = "125" height="150" alt="PHP" />
<img src="https://github.com/mynameisone/Main/blob/master/img/js1.jpg?raw=true" width = "125" height="150" alt="JS" />
<!-- <img src="https://github.com/mynameisone/Main/blob/master/img/HACK.png?raw=true" width = "125" height="150" alt="Hack" /> -->
<!-- <img src="https://github.com/mynameisone/Main/blob/master/img/SASS.png?raw=true" width = "125" height="150" alt="SASS" /> -->
<img src="https://github.com/mynameisone/Main/blob/master/img/SQL.png?raw=true" width = "125" height="150" alt="MYSQL" />
<img src="https://github.com/mynameisone/Main/blob/master/img/M.png?raw=true" width = "125" height="150" alt="SQL" />
</p>

## To get a request from a user
```
// Get method is used to get a data from a user
<form class="index__form" action="search.php" methode="GET">
    <div>
        <input name="term" type="search" class="index__query" 
        maxlength="512" autocomplete="off" title="Search"
        aria-label="Search" dir="ltr" spellcheck="false"
        autofocus="autofocus">
    </div>
    <button class="index__button" aria-label="Search" type="submit"></button>
</form>
```

## To connect data base

akloop.sql file is provided, this file must be imported into a database and then some vatiable names must be given in config.php file.
```
<?php
    ob_start();
    try
    {
	$con = new PDO("mysql:dbname=database_name; host=host_name", "user_name", "password");
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }
    catch(PODExeption $e)
    {
        echo "Connection failed: " . $e->getMessage();
    }
?>


```

## Functions that this website can handle so far ##

    * Admin can select a website to crawl
    * User can search by data type
    * Website filters data automatically
    * Broken links are not used
    * Search results are sorted by popularity
    * Images can be in a bigger view


## 📝 License

Copyright © 2019 [Azimjon Kamolov](https://github.com/mynameisone).<br />
This project is [MIT](https://github.com/kefranabg/readme-md-generator/blob/master/LICENSE) licensed.
