<?php
include("config.php");
    $alreadyCrawled = array();
    $crawling = array();
    $alreadyFoundImages = array();

function linkExists($url)
{
    global $con;
    $query = $con->prepare("SELECT * FROM sites WHERE url = :url");
    
    $query ->bindParam(":url", $url);
    $query -> execute();
    
    return $query->rowCount()!=0;

}

function insertLink($url, $title, $description, $keywords)
{
    global $con;
    $query = $con->prepare("INSERT INTO sites (url, title, description, keywords) VALUES
    (:url, :title, :description, :keywords)");

    $query ->bindParam(":url", $url);
    $query ->bindParam(":title", $title);
    $query ->bindParam(":description", $description);
    $query ->bindParam(":keywords", $keywords);

    return $query -> execute();

}

function insertImages($url, $src, $alt, $title)
{
    global $con;
    $query = $con->prepare("INSERT INTO images (siteUrl, imageUrl, alt, title) VALUES
    (:siteUrl, :imageUrl, :alt, :title)");

    $query ->bindParam(":siteUrl", $url);
    $query ->bindParam(":imageUrl", $src);
    $query ->bindParam(":alt", $alt);
    $query ->bindParam(":title", $title);


    return $query -> execute();

}

include ("classes/DomDocumentParser.php");

    function createLink($src, $url)
    {
        $scheme = parse_url($url)["scheme"];    // http
        $host = parse_url($url)["host"];        // www.bbc.com
        if(substr($src, 0, 2) == "//")
        {   $src = $scheme . ":" . $src;    }

        else if(substr($src, 0, 1)=="/")
        {   $src=$scheme . ":" . $host . $src;    }
        
        else if(substr($src, 0, 2)=="./")
        {   $src=$scheme . ".//" . $host . dirname(parse_url($url)["path"]) . substr($src, 1);  }
        
        else if(substr($src, 0, 3)=="../")
        {   $src=$scheme . "://" . $host . "/" . $src;   }
        
        else if(substr($src,0,5)!="https" && substr($src,0,4)!="http")
        {   $src=$scheme . "://" . $host . "/" . $src;   }

        return $src;
    }

    function getDetails($url)
    {
        global $alreadyFoundImages;

        $parser = new DomDocumentParser($url);
        $titleArray = $parser ->getTitleTags();
        if(sizeof($titleArray)==0 || $titleArray->item(0)==NULL)
        {   return;   }
        $title=$titleArray->item(0)->nodeValue;
        $title=str_replace("\n", "", $title);
        if($title==""){return;}

        $description = "";
        $keywords = "";
        $metasArray = $parser->getMetaTags();
        foreach($metasArray as $meta)
        {
            if($meta->getAttribute("name")=="description")
                {   $description=$meta->getAttribute("content");    }
            if($meta->getAttribute("name")=="keywords")
                {   $keywords=$meta->getAttribute("content");    }
        }

        $description = str_replace("\n", "", $description);
        $keywords = str_replace("\n", "", $keywords);

        if(linkExists($url))
        {
            echo "$url already exixts<br>";
        }
        else if(insertLink($url, $title, $description, $keywords))
        {
            echo "Inserted URL: $url <br>";
        }
        else
        {
            echo "ERROR: Failed to insert $url <br>";
        }

        $imageArray = $parser->getImages();
        foreach($imageArray as $image)
        {
            $src = $image->getAttribute("src");
            $alt = $image->getAttribute("alt");
            $title = $image->getAttribute("title");
            if(!$title && !$alt)
            {   continue;   }
            
            $src = createLink($src, $url);

            if(!in_array($src, $alreadyFoundImages))
            {
                   $alreadyFoundImages[]=$src;

                   // Insert images

                   echo "Inserted Image: " . insertImages($url, $src, $alt, $title). "<br>";
            }


        }

        // echo "URL: $url, Title: $title, Des: $description, Key: $keywords <br>";
    }

    function followLinks($url)
    {   
        global $alreadyCrawled;
        global $crawling;

        $parser = new DomDocumentParser($url);    
        $linkList = $parser->getLinks();
        foreach($linkList as $link)
        {
            $href = $link->getAttribute("href");

            if(strpos($href, "#") !== false)
            {
                continue;
            }
            else if(substr($href, 0, 11)=="javascript:")
            {
                continue;
            }

            $href = createLink($href, $url);

            if(!in_array($href, $alreadyCrawled))
            {
                $alreadyCrawled[]=$href;
                $crawling[]=$href;

                getDetails($href);
            }
            // else 
            // {  return;   } if found dublicate stops no need now


            // echo $href . "<br>";
        }

        array_shift($crawling);
        foreach($crawling as $site)
        {   followLinks($site); }

    }

        //     $topdom_Array = array('https://www.mover.uz', 'https://www.kun.uz', 'https://www.uzonline.uz', 'https://www.mytube.uz', 'https://www.player.uz', 'https://www.daryo.uz', 'https://www.google.co.uz', 'https://www.allmovies.uz', 'https://www.gazeta.uz', 'https://www.topmusic.uz', 'https://www.mnogo.uz', 'https://www.cinerama.uz', 'https://www.tn.uz', 'https://www.gov.uz', 'https://www.itv.uz', 'https://www.podrobno.uz', 'https://www.uff.uz', 'https://www.rem.uz', 'https://www.lex.uz', 'https://www.mediabay.uz', 'https://www.zavq.uz', 'https://www.kinoman.uz', 'https://www.stadion.uz', 'https://www.soliq.uz', 'https://www.afisha.uz', 'https://www.filmi.uz', 'https://www.okay.uz', 'https://www.ucell.uz', 'https://www.goldenpages.uz', 'https://www.wiut.uz', 'https://www.tps.uz', 'https://www.beeline.uz', 'https://www.qalampir.uz', 'https://www.allplay.uz', 'https://www.sarkor.uz', 'https://www.norma.uz', 'https://www.uza.uz', 'https://www.megasoft.uz', 'https://www.uznews.uz', 'https://www.nuz.uz', 'https://www.ums.uz', 'https://www.ziyonet.uz', 'https://www.islom.uz', 'https://www.umail.uz', 'https://www.tasx.uz', 'https://www.mediabox.uz', 'https://www.payme.uz', 'https://www.kinopro.uz', 'https://www.esi.uz', 'https://www.vesti.uz', 'https://www.arxiv.uz', 'https://www.turontelecom.uz', 'https://www.nod32.uz', 'https://www.istv.uz', 'https://www.tribuna.uz', 'https://www.glotr.uz', 'https://www.galsmedia.uz', 'https://www.uz24.uz', 'https://www.darakchi.uz', 'https://www.rabota.uz', 'https://www.muz.uz', 'https://www.mfa.uz', 'https://www.yellowpages.uz', 'https://www.kinopokaz.uz', 'https://www.pogoda.uz', 'https://www.fmc.uz', 'https://www.inha.uz', 'https://www.uzmobile.uz', 'https://www.kommersant.uz', 'https://www.jetnet.uz', 'https://www.kinoplay.uz', 'https://www.uybor.uz', 'https://www.myjob.uz', 'https://www.arba.uz', 'https://www.xb.uz', 'https://www.uzex.uz', 'https://www.aim.uz', 'https://www.kinozal.uz', 'https://www.www.uz', 'https://www.hordiq.uz', 'https://www.mediapark.uz', 'https://www.nrm.uz', 'https://www.e-auksion.uz', 
        // 'https://www.sof.uz', 'https://www.pochta.uz', 'https://www.terabayt.uz', 'https://www.cbu.uz', 'https://www.yi.uz', 'https://www.onlineradio.uz', 'https://www.fibernet.uz', 'https://www.id.uz', 'https://www.gmuzbekistan.uz', 'https://www.tuit.uz', 'https://www.stat.uz', 'https://www.itube.uz', 'https://www.uzbekenergo.uz', 'https://www.serialy.uz', 'https://www.zor.uz', 'https://www.gals.uz', 'https://www.flagma.uz', 'https://www.borku.uz', 'https://www.4pda.uz', 'https://www.saytlar.uz', 'https://www.myday.uz', 'https://www.uforum.uz', 'https://www.uzjobs.uz', 'https://www.avtobor.uz', 'https://www.ttt.uz', 'https://www.repost.uz', 'https://www.tforum.uz', 'https://www.gigal.uz', 'https://www.mdis.uz', 'https://www.alibuy.uz', 'https://www.avi.uz', 'https://www.st.uz', 'https://www.edu.uz', 'https://www.hh.uz', 'https://www.ulugov.uz', 'https://www.evo.uz', 'https://www.anhor.uz', 'https://www.click.uz', 'https://www.mytashkent.uz', 'https://www.minzdrav.uz', 'https://www.mehnat.uz', 'https://www.potrebiteli.uz', 'https://www.superjob.uz', 'https://www.load.uz', 'https://www.railway.uz', 'https://www.apteka.uz', 'https://www.bimm.uz', 'https://www.inbox.uz', 'https://www.idum.uz', 'https://www.uzedu.uz', 'https://www.comnet.uz', 'https://www.soft.uz', 'https://www.uztelecom.uz', 'https://www.zamin.uz', 'https://www.asr.uz', 'https://www.gl.uz', 'https://www.ishkop.uz', 'https://www.omadlotto.uz', 'https://www.meteo.uz', 'https://www.migration.uz', 'https://www.mtrk.uz', 'https://www.kinomir.uz', 'https://www.muvi.uz', 'https://www.ut.uz', 'https://www.irp.uz', 'https://www.grantlar.uz', 'https://www.uzrailway.uz', 'https://www.ckt.uz', 'https://www.tdpu.uz', 'https://www.jahonnews.uz', 'https://www.uznavo.uz', 'https://www.mobinfo.uz', 'https://www.azon.uz', 'https://www.uzcard.uz', 'https://www.realblancos.uz', 'https://www.tma.uz', 'https://www.uztg.uz', 'https://www.eduportal.uz', 'https://www.kinotasix.uz', 'https://www.dtm.uz', 'https://www.mazmun.uz', 'https://www.uzreport.uz', 'https://www.flink.uz', 'https://www.itrack.uz', 'https://www.uzdaily.uz', 'https://www.upl.uz', 'https://www.mobilforum.uz', 'https://www.tfi.uz', 'https://www.img.uz', 'https://www.mset.uz', 'https://www.prom.uz', 'https://www.top.uz', 'https://www.igame.uz', 'https://www.fcb.uz', 'https://www.obhavo.uz', 'https://www.4free.uz', 'https://www.interfutbol.uz', 'https://www.sports.uz', 'https://www.mg.uz', 'https://www.standart.uz', 'https://www.ziyouz.uz', 'https://www.mp3.uz', 'https://www.bibi.uz', 'https://www.kh-davron.uz', 'https://www.bank.uz', 'https://www.ek.uz', 'https://www.fikr.uz', 'https://www.asaxiy.uz', 'https://www.poigraem.uz', 'https://www.infocom.uz', 'https://www.glamourtv.uz', 'https://www.muloqot.uz', 'https://www.ahost.uz', 'https://www.cctld.uz', 'https://www.abad.uz', 'https://www.bank24.uz', 'https://www.ipakyulibank.uz', 'https://www.netco.uz', 'https://www.sammi.uz', 'https://www.president.uz', 'https://www.ztv.uz', 'https://www.taqi.uz', 'https://www.hamkorbank.uz', 'https://www.avtech.uz', 'https://www.pixland.uz', 'https://www.natlib.uz', 'https://www.smst.uz', 'https://www.arhiv.uz', 'https://www.muslim.uz', 'https://www.agromarkaz.uz', 'https://www.imovies.uz', 'https://www.onlinetv.uz', 'https://www.windows.uz', 'https://www.topfilm.uz', 'https://www.avizinfo.uz', 'https://www.telegraf.uz', 'https://www.kursy.uz', 'https://www.xabardor.uz', 'https://www.akadmvd.uz', 'https://www.filmy.uz', 'https://www.xs.uz', 'https://www.minjust.uz', 'https://www.kapitalbank.uz', 'https://www.silkroad.uz', 'https://www.1news.uz', 'https://www.nuu.uz', 'https://www.iplay.uz', 'https://www.zver.uz', 'https://www.nbu.uz', 'https://www.clip.uz', 'https://www.guvd.uz', 'https://www.meest.uz', 'https://www.1401919.uz', 'https://www.karsu.uz', 'https://www.med.uz', 'https://www.txt.uz', 'https://www.yandex.uz', 'https://www.refer.uz', 'https://www.resume.uz', 'https://www.stroyka.uz', 'https://www.muslimaat.uz', 'https://www.mbc.uz', 'https://www.prokuratura.uz', 'https://www.meteoprog.uz', 'https://www.isoft.uz', 'https://www.creditasia.uz', 'https://www.uzdtv.uz', 'https://www.erkak.uz', 'https://www.zn.uz', 'https://www.perfectum.uz', 'https://www.iplayer.uz', 'https://www.asia-travel.uz', 'https://www.shosh.uz', 'https://www.webname.uz', 'https://www.bulavka.uz', 'https://www.markaz.uz', 'https://www.ipotekabank.uz', 'https://www.uzinfocom.uz', 'https://www.uzbekistan24.uz', 'https://www.polito.uz', 'https://www.britishcouncil.uz', 'https://www.pc.uz', 'https://www.ung.uz', 'https://www.otpusk.uz', 'https://www.paycom.uz', 'https://www.uwed.uz', 'https://www.chevrolet.uz', 'https://www.toptop.uz', 'https://www.e-imzo.uz', 'https://www.icraft.uz', 'https://www.technomart.uz', 'https://www.okey.uz', 'https://www.narodnoeslovo.uz', 'https://www.ijro.uz', 'https://www.oak.uz', 'https://www.zamonaviy.uz', 'https://www.itportal.uz', 'https://www.mov.uz', 'https://www.spy.uz', 'https://www.geografiya.uz', 'https://www.press-service.uz', 'https://www.korzinka.uz', 'https://www.uzavtosanoat.uz', 'https://www.e-edu.uz', 'https://www.biznes-daily.uz', 'https://www.micros.uz', 'https://www.driversvillage.uz', 'https://www.corp.uz', 'https://www.ikids.uz', 'https://www.search.uz', 'https://www.change.uz', 'https://www.bellashuv.uz', 'https://www.uzbaby.uz', 'https://www.ars.uz', 'https://www.azamat.uz', 'https://www.uzbektourism.uz', 'https://www.kinomega.uz', 'https://www.carzone.uz', 'https://www.uzbekistonovozi.uz', 'https://www.zortv.uz', 'https://www.autotorg.uz', 'https://www.quran.uz', 'https://www.russia.uz', 'https://www.freeads.uz', 'https://www.birdarcha.uz', 'https://www.tashtrans.uz', 'https://www.shahar.uz', 'https://www.dump.uz', 'https://www.lan.uz', 'https://www.kinobor.uz', 'https://www.bringo.uz', 'https://www.dba.uz', 'https://www.sonet.uz', 'https://www.kinohd.uz', 'https://www.uzlidep.uz', 'https://www.sensorika.uz', 'https://www.maslahatim.uz', 'https://www.mf.uz', 'https://www.zira.uz', 'https://www.distr.uz', 'https://www.artelmarket.uz', 'https://www.hotlinks.uz', 'https://www.davrbank.uz', 'https://www.vot.uz', 'https://www.home24.uz', 'https://www.playboys.uz', 'https://www.customs.uz', 'https://www.oqituvchi.uz', 'https://www.buxdu.uz', 'https://www.oliygoh.uz', 'https://www.uznew.uz', 'https://www.asakabank.uz', 'https://www.kruz.uz', 'https://www.uz-djti.uz', 'https://www.agrobank.uz', 'https://www.mitc.uz', 'https://www.gor.uz', 'https://www.gkk.uz', 'https://www.hilolnashr.uz', 'https://www.kasaba.uz', 'https://www.taqvim.uz', 'https://www.vsem.uz', 'https://www.nsfla.uz', 'https://www.prezident.uz', 'https://www.mineconomy.uz', 'https://www.islomkarimov.uz', 'https://www.asilmedia.uz', 'https://www.sadikov.uz', 'https://www.fut.uz', 'https://www.tshtt.uz', 'https://www.kamb.uz', 'https://www.tami.uz', 'https://www.spi.uz', 'https://www.pfl.uz', 'https://www.musictop.uz', 'https://www.uzchess.uz', 
        //     'https://www.voydoda.uz', 'https://www.kliniki.uz', 'https://www.texnomart.uz', 'https://www.iut.uz', 'https://www.muxlis.uz', 'https://www.utube.uz', 'https://www.gmuz.uz', 'https://www.blayzer.uz', 'https://www.qarshidu.uz', 'https://www.ded.uz', 'https://www.gsmserver.uz', 'https://www.mvd.uz', 'https://www.chamber.uz', 'https://www.ayol.uz', 'https://www.airways.uz', 'https://www.xushnudbek.uz', 'https://www.america.uz', 'https://www.camon.uz', 'https://www.medplaza.uz', 
        //     'https://www.kasmed.uz', 'https://www.uzscience.uz', 'https://www.simus.uz', 'https://www.tshtx.uz', 'https://www.samcity.uz', 'https://www.tdiu.uz', 'https://www.restoran.uz', 'https://www.uznature.uz', 'https://www.uskunalar.uz', 'https://www.turonbank.uz', 'https://www.11-maktab.uz', 'https://www.airguns.uz', 'https://www.agro.uz', 'https://www.topvideo.uz', 'https://www.l2play.uz', 'https://www.uzpsb.uz', 'https://www.review.uz', 'https://www.agriculture.uz', 'https://www.paxta.uz', 'https://www.track.uz', 'https://www.kultura.uz', 'https://www.megamall.uz', 'https://www.nbkstudio.uz', 'https://www.tomosha.uz', 'https://www.uzbekmarket.uz', 'https://www.tashkent.uz', 'https://www.uzfilm.uz', 'https://www.solver.uz', 'https://www.agmk.uz', 'https://www.7777.uz', 'https://www.naesmi.uz', 'https://www.autocenter.uz', 'https://www.titli.uz', 'https://www.rtm.uz', 'https://www.msu.uz', 'https://www.penalti.uz', 'https://www.antiqa.uz', 'https://www.gm.uz', 'https://www.fledu.uz', 'https://www.mnogonado.uz', 'https://www.fiqh.uz', 'https://www.kdb.uz', 'https://www.sdv.uz', 'https://www.anons.uz', 'https://www.tglab.uz', 'https://www.tdtu.uz', 'https://www.hasanboy.uz', 'https://www.newkino.uz', 'https://www.masterok.uz', 'https://www.paynet.uz', 'https://www.e-adabiyot.uz', 'https://www.aloqabank.uz', 'https://www.senat.uz', 'https://www.cabinet.uz', 'https://www.derbi.uz', 'https://www.aniline.uz', 'https://www.1-avgust.uz', 'https://www.uzcloud.uz', 'https://www.ofb.uz', 'https://www.pakhtakor.uz', 'https://www.samsunggalaxy.uz', 'https://www.xdp.uz', 'https://www.filmstar.uz', 'https://www.abt.uz', 'https://www.zarnews.uz', 'https://www.ygk.uz', 'https://www.ima.uz', 'https://www.salexy.uz', 'https://www.esavdo.uz', 'https://www.orbita.uz', 'https://www.udobno.uz', 'https://www.samdu.uz', 'https://www.alif.uz', 'https://www.tsul.uz', 'https://www.tashkec.uz', 'https://www.mufc.uz', 'https://www.maxtrack.uz', 'https://www.phpworld.uz', 'https://www.teploenergo.uz', 'https://www.xit.uz', 'https://www.uzmp3.uz', 'https://www.madaniyat.uz', 'https://www.pv.uz', 'https://www.texnostar.uz', 'https://www.tengsiz.uz', 'https://www.uztransgaz.uz', 'https://www.tom.uz', 'https://www.resto.uz', 'https://www.lada.uz', 'https://www.bestmusic.uz', 'https://www.fayzbog.uz', 'https://www.adolat.uz', 'https://www.mku.uz', 'https://www.uzexpocentre.uz', 'https://www.fm101.uz', 'https://www.samarkand.uz', 'https://www.ibazar.uz', 'https://www.arabic.uz', 'https://www.afto.uz', 'https://www.ujc.uz', 'https://www.askdoctor.uz', 'https://www.qishloqqurilishbank.uz', 'https://www.study.uz', 'https://www.real-madrid.uz', 'https://www.xitmp3.uz', 'https://www.opa.uz', 'https://www.monkeygames.uz', 'https://www.newmp3.uz', 'https://www.islamkarimov.uz', 'https://www.kassa.uz', 'https://www.uzgps.uz', 'https://www.dasturchi.uz', 'https://www.xiaomi.uz', 'https://www.lebazar.uz', 'https://www.alto.uz', 'https://www.webmax.uz', 'https://www.gubkin.uz', 'https://www.xbb.uz', 'https://www.tuitkf.uz', 'https://www.adliya.uz', 'https://www.supcourt.uz', 'https://www.professionals.uz', 'https://www.sitash.uz', 'https://www.apteka999.uz', 'https://www.sdelka.uz', 'https://www.dia.uz', 'https://www.insof.uz', 'https://www.atlantic.uz', 'https://www.extremal.uz', 'https://www.logistika.uz', 'https://www.constitution.uz', 'https://www.freshonline.uz', 'https://www.2play.uz', 'https://www.infosec.uz', 'https://www.hisobot.uz', 'https://www.jethost.uz', 'https://www.arb.uz', 'https://www.pharmi.uz', 'https://www.dasturim.uz', 'https://www.megabox.uz', 'https://www.lukoil-overseas.uz', 'https://www.finansist.uz', 'https://www.unicon.uz', 'https://www.oj.uz', 'https://www.e-tarix.uz', 'https://www.urdu.uz', 'https://www.mikrokreditbank.uz', 'https://www.futura.uz', 'https://www.publika.uz', 'https://www.universal.uz', 'https://www.kino24.uz', 'https://www.mt.uz', 'https://www.kafolat.uz', 'https://www.themag.uz', 'https://www.vinsanoat.uz', 'https://www.mebel.uz', 'https://www.proweb.uz', 'https://www.shok.uz', 'https://www.bir.uz', 'https://www.aquatropic.uz', 'https://www.texnoman.uz', 'https://www.uzavtoyul.uz', 'https://www.avtotest.uz', 'https://www.fondkarimov.uz', 'https://www.uzbekona.uz', 'https://www.toshkent.uz', 'https://www.tashgiv.uz', 'https://www.ubtuit.uz', 'https://www.fan-portal.uz', 'https://www.mytorrents.uz', 'https://www.mdm.uz', 'https://www.siyrat.uz', 'https://www.protoday.uz', 'https://www.canon.uz', 'https://www.eva.uz', 'https://www.ohotnik.uz', 'https://www.megakino.uz', 'https://www.prava.uz', 'https://www.tiiame.uz', 'https://www.ite-uzbekistan.uz', 'https://www.dermatology.uz', 'https://www.game-game.uz', 'https://www.dyhxx.uz', 'https://www.pfru.uz', 'https://www.aab.uz', 'https://www.yangilar.uz', 'https://www.doridarmon.uz', 'https://www.openinfo.uz', 'https://www.pravoslavie.uz', 'https://www.ceny.uz', 'https://www.icoder.uz', 'https://www.tashkenttimes.uz', 'https://www.soglom.uz', 'https://www.uzgeolcom.uz', 'https://www.e-hilolnashr.uz', 'https://www.evos.uz', 'https://www.islam.uz', 'https://www.brm.uz', 'https://www.trustbank.uz', 'https://www.qashqadaryogz.uz', 'https://www.gross.uz', 'https://www.websum.uz', 'https://www.infosystems.uz', 'https://www.tridantus.uz', 'https://www.arirang.uz', 'https://www.sayt.uz', 'https://www.estimed.uz', 'https://www.davarx.uz', 'https://www.yaponamama.uz', 'https://www.all-solar.uz', 'https://www.bforum.uz', 'https://www.davra.uz', 'https://www.nout.uz', 'https://www.chakchak.uz', 'https://www.megafilm.uz', 'https://www.jizzax.uz', 'https://www.strategy.uz', 'https://www.megadunyo.uz', 'https://www.academy.uz', 'https://www.24travel.uz', 'https://www.dastur.uz', 'https://www.e-maktab.uz', 'https://www.uztp.uz', 'https://www.vitech.uz', 'https://www.rev.uz', 'https://www.gis.uz', 'https://www.sites-uz.uz', 'https://www.genuzbekistan.uz', 'https://www.eman.uz', 'https://www.azu.uz', 'https://www.osmon.uz', 'https://www.bazm.uz', 'https://www.mulohaza.uz', 'https://www.tec.uz', 'https://www.uzse.uz', 'https://www.armedia.uz', 'https://www.pantera.uz', 'https://www.matematika.uz', 'https://www.qadriyat.uz', 'https://www.axe.uz', 'https://www.tafsilot.uz', 'https://www.archikultura.uz', 'https://www.wialon.uz', 'https://www.della.uz');
        
            
        //         foreach($topdom_Array as $startUrl)
        //         {
        //             followLinks($startUrl);
        //         }

    // if(isset($_GET["submit"]))
    // {
    //     $urlToCrawl = $_GET["urlToCrawl"];
    //     $startUrl = $urlToCrawl;
    //     followlinks($startUrl);
    // }

    array_shift($crawling);
    foreach($crawling as $site)
    {   followLinks($site); }



    if(isset($_GET["submit"]))
    {
        $urlToCrawl = $_GET["urlToCrawl"];
        $startUrl = $urlToCrawl;
        followlinks($startUrl);
    }




?>

<form action = "crawl.php" method="GET">
    
    <input type="text" name="urlToCrawl">
    <input type="submit" name ="submit" value="Submit">

</form>


