<!-- 
Author: Azimjon Kamolov
Project's name: Boogle (search engine)
Purpose: to collect data and learn from it
 -->

<?php

include ("config.php");
include ("classes/SiteResultsProvider.php");
include ("classes/ImageResultsProvider.php");

    if(isset($_GET["term"]))
    {
        $term = $_GET["term"];
    }
    else
    {
        exit("You must enter a search term");
    }

    $type = isset($_GET["type"]) ? $_GET["type"] : "sites";
    $page = isset($_GET["page"]) ? $_GET["page"] : 1;

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>akloop</title>
    <link rel="shortcut icon" href="img/logo.png"/>
   

        <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    <link rel="stylesheet" type="text/css" href="style/style.css">
    <link rel="stylesheet" type="text/css" href="style/compo.css">
    <link rel="stylesheet" type="text/css" href="css/serp.css">
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="  crossorigin="anonymous"></script>

</head>
<body>

<div class="wrapper">

<!-- Header part is here -->

<div class="serp__header">
        <div class="serp__search">
          <form class="serp__form" action="search.php" methode="GET">
            <div>
              <input name="term" type="search"
                    value=" <?php echo $term; ?> "
                     class="serp__query" 
                     maxlength="512"
                     autocomplete="off"
                     title="Search"
                     aria-label="Search"
                     dir="ltr"
                     spellcheck="false"
                     autofocus="autofocus"
              >
            </div>
            <button class="serp__button" aria-label="Search" type="submit">
              <div class="serp__ico"></div>
            </button>
          </form>
        </div>
        <a class="serp__logo" href="index.php"></a>
        <ul class="serp__nav">
        <li class="<?php echo $type=='sites' ? 'serp__active' : '' ?>">
            <?php   $sterm =  "search.php?term=" . $term . "&type=sites";   ?>
            <a href="<?php echo $sterm; ?>" >
                Saytlar
            </a>
        </li>

        <li class="<?php echo $type=='images' ? 'serp__active' : '' ?>">

                    <?php   $sterm =  "search.php?term=" . $term . "&type=images";   ?>

                    <a href="<?php echo $sterm; ?>" >
                        Rasmlar
                    </a>
                </li>

                        <li class="<?php echo $type=='videos' ? 'serp__active' : '' ?>">

                    <?php   $iterm =  "search.php?term=" . $term . "&type=videos";   ?>

                    <a href="<?php echo $iterm; ?>" >
                        Videolar
                    </a>
                </li>
                <li class="<?php echo $type=='blogs' ? 'serp__active' : '' ?>">

                <?php   $iterm =  "search.php?term=" . $term . "&type=blogs";   ?>

                <a href="<?php echo $iterm; ?>" >
                    Bloglar
                </a>
                </li>
                        </ul>
                    </div>

<!-- Header part is over here -->

<hr>

    <div class="mainResultsSection">
        <?php

            $start = explode(' ', microtime())[0] + explode(' ', microtime())[1];

            if($type == "sites")
            {
                $resultsProvider = new SiteResultProvider($con);
                $pageSize = 20;
            }
            elseif($type=="images")
            {
                $resultsProvider = new ImageResultProvider($con);
                $pageSize = 80; // how many pics are on one page
            }
            elseif($type=="blogs")
            {
                $resultsProvider = new BlogsResultsProvider($con);
                $pageSize = 20; // how many pics are on one page
            }
            else
            {
                echo "Under construction";
            }

            $numResults = $resultsProvider -> getNumResults($term);

            $resultsProvider -> track($term);

            $vaqt = (''.round((explode(' ', microtime())[0] + explode(' ', microtime())[1]) - $start, 4).'');
            echo "<p class='resultsCount'> $numResults ma'lumotlar topildi ($vaqt ichida) </p>";
            
            echo $resultsProvider->getResultsHtml($page, $pageSize, $term);


            // <div class="row">
            //     <div class="col-sm-4">.col-sm-4</div>
            //     <div class="col-sm-4">.col-sm-4</div>
            //     <div class="col-sm-4">.col-sm-4</div>
            // </div>

        ?>
    </div>

    <div class="paginationContainer">

        <div class="pageButtons">
    
            <div class="pageNumberContainer">
                <img src="img/1.png">
            </div>

            <?php

                // $pagesLeft = 10;
                // $currentPage = 1;

                // while($pagesLeft != 0)
                // {
                //     if($currentPage==$page)
                //     {
                //         echo "<div class='pageNumberContainer'>
                //         <img src='img/pageSelected.png'>
                //         <span class='pageNumber'> $currentPage </span>
                //         </div>";
                //     }
                //     else
                //     {
                //         echo "<div class='pageNumberContainer'>
                //         <a href = 'search.php?term=$term&type=$type&page=$currentPage' >
                //         <img src='img/page.png'>
                //         <span class='pageNumber'> $currentPage </span>
                //         </a>
                //         </div>";                        
                //     }

                //     $currentPage++;
                //     $pagesLeft--;
                    
                // }

                $pagesToShow = 10;
                $numPages = ceil($numResults/$pageSize);
                $pagesLeft = min ($pagesToShow, $numPages);
                $currentPage = $page - ($pagesToShow / 2);

                if($currentPage < 1)
                {
                    $currentPage = 1;
                }

                if($currentPage + $pagesLeft > $numPages+1)
                {
                    $currentPage + $numPages+1 - $pagesLeft;
                }

                while($pagesLeft != 0 && $currentPage <= $numPages)
                {

                    if($currentPage==$page)
                    {
                        echo "<div class='pageNumberContainer'>
                        <img src='img/3.png'>
                        <span class='pageNumber'> $currentPage </span>
                        </div>";
                    }
                    else
                    {
                        echo "<div class='pageNumberContainer'>
                        <a href='search.php?term=$term&type=$type&page=$currentPage'>
                            <img src='img/2.png'>
                            <span class='pageNumber any'> $currentPage </span>
                        </a>
                        </div>";
                    }


                    $currentPage++;
                    $pagesLeft--;
                    
                }

            ?>

            <div class="pageNumberContainer">
                <img src="img/4.png">
            </div>
    
        </div>

</div>

        <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
        <script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
        <script type="text/javascript" src="script.js"></script>

</body>
</html>