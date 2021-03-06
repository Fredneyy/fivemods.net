<div class="leftBasedAds" style="left: 0px; position: fixed; text-align: center; top: 20%;margin-left:3%;">


    <!-- Vertical Test -->
    <ins class="adsbygoogle leftBasedAds" style="display:inline-block;width:160px;height:600px" data-ad-client="ca-pub-9727102575141971" data-ad-slot="2716933531" data-ad-format="auto" data-full-width-responsive="true"></ins> <!-- data-ad-format="auto" data-full-width-responsive="true" -->
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
</div>
<div class="rightBasedAds" style="right: 0px; position: fixed; text-align: center; top: 20%;margin-right:3%;">

    <!-- Vertical Test -->
    <ins class="adsbygoogle rightBasedAds" style="display:inline-block;width:160px;height:600px" data-ad-client="ca-pub-9727102575141971" data-ad-slot="2716933531" data-ad-format="auto" data-full-width-responsive="true"></ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
</div>
<?php

include('./include/header-banner.php');
require_once('./config.php');
$ratedid = htmlspecialchars($_GET['r']);
$rating = htmlspecialchars($_GET['v']);


$servername = $mysql['servername'];
$username = $mysql['username'];
$password = $mysql['password'];
$dbname = $mysql['dbname'];


if (!empty(htmlspecialchars($_GET['q']))) {
   $query = htmlspecialchars($_GET['q']);


   $pdo = new PDO('mysql:dbname='.$mysql['dbname'].';host='.$mysql['servername'].'', ''.$mysql['username'].'', ''.$mysql['password'].'');

   // Query
   $questions = $pdo->prepare("
      SELECT SQL_CALC_FOUND_ROWS *
      FROM faq
      WHERE question LIKE '%?%' OR answer LIKE '%?%'
      ORDER BY f_id DESC;
   ");

   // Fetiching questions
   $questions->execute(array($query, $query));
   $questions = $questions->fetchAll(PDO::FETCH_ASSOC);

}

$pdo = new PDO('mysql:dbname='.$mysql['dbname'].';host='.$mysql['servername'].'', ''.$mysql['username'].'', ''.$mysql['password'].'');

$result = $pdo->prepare("SELECT * FROM faq WHERE f_id = ?");
$result->execute(array($ratedid));

if ($result->rowCount() > 0) {
  // output data of each row
  while($row = $result->fetch()) {
   $helpful = $row['good'];
   $nothelpful = $row['bad'];
  }
}

if (!empty(htmlspecialchars($_GET['r'])) && !empty(htmlspecialchars($_GET['v']))) {

if ($rating == 1) {

   // Was helpful -> good
   $helpfulrate = $helpful+1;

   $stmt = $pdo->prepare("UPDATE faq SET good = ? WHERE f_id = ?");
   $stmt->execute(array($helpfulrate, $ratedid));

   header('location: /help-center/#'.$ratedid);

   $_SESSION['control'] = 1;

} elseif ($rating == 0) {

   // Was not helpful -> bad
   $nothelpfulrate = $nothelpful+1;

   $stmt = $pdo->prepare("UPDATE faq SET bad = ? WHERE f_id = ?");
   $stmt->execute(array($nothelpfulrate, $ratedid));
   header('location: /help-center/#'.$ratedid);

   $_SESSION['control'] = 1;

}
}
?>
<section class="bg-light pb-5">
   <div class="container">
      <div class="row justify-content-between align-items-center">
      <div class="col-lg-8 col-md-9">
         <h1 class="display-3 font-alt-1"><?php echo $lang['fivem-support-yes']; ?></h1>
         <p class="lead"><?php echo $lang['fivem-support-subline']; ?></p>
         <form class="d-flex" method="get">
            <div class="input-group input-group-lg mr-2">
               <div class="input-group-prepend bg-transparent">
                  <span class="input-group-text bg-transparent border-right-0"> <i class="fas fa-search"></i></span>
               </div>
               <input type="text" class="form-control" name="q" placeholder="<?php echo $lang['search']; ?>" aria-label="Search">
            </div>
            <button class="btn btn-primary btn-lg" type="submit"><?php echo $lang['go']; ?></button>
         </form>
         <ul class="list-unstyled d-flex flex-wrap mt-2 mb-0">
            <li class="mr-2 mb-2 mb-lg-0">
            <a href="?q=vehicles" class="btn btn-sm btn-link shadow-sm">How to add vehicles</a>
            </li>
            <li class="mr-2 mb-2 mb-lg-0">
            <a href="?q=create+a+FiveM+server" class="btn btn-sm btn-link shadow-sm">How to create a FiveM server</a>
            </li>
            <li class="mr-2 mb-2 mb-lg-0">
            <a href="?q=create+map" class="btn btn-sm btn-link shadow-sm ">Create custom map</a>
            </li>
            <li class="mr-2 mb-2 mb-lg-0">
            <a href="?q=codewalker" class="btn btn-sm btn-link shadow-sm">Codewalker</a>
            </li>
         </ul>
      </div>
      <div class="col-md-3" style="margin-top:15%;margin-bottom:13%;margin-right:5%;">
         <img src="/static-assets/img/help-sign.svg" alt="Girl-SVG" class="img-fluid">
      </div>
      </div>
   </div>
   <!-- Search result -->
   <section>
      <div class="container">
         <div class="row justify-content-center">
            <div class="col-12 col-md-12 text-center">
            <?php foreach($questions as $questionx): ?>
               <div class="card shadow-sm text text-left" id="<?php echo $questionx['f_id']; ?>">
                     <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                           <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse<?php echo $questionx['f_id']; ?>X" aria-expanded="false" aria-controls="collapse<?php echo $questionx['f_id']; ?>X">
                           <b>Q: <?php echo $questionx['question']; ?></b>
                           </button>
                        </h5>
                     </div>
                     <div id="collapse<?php echo $questionx['f_id']; ?>X" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                           Answer:<br><hr><?php echo $questionx['answer']; ?>
                        </div>
                     </div>
                  </div>
               <?php endforeach; ?>
            </div>
         </div>
      </div>
   </section>
   <!-- End Search result -->
</section>
<section class="pt-5 pb-5">
   <div class="container">
      <div class="row justify-content-center">
      <div class="col-12 col-md-12 text-center">
         <h2 class="mb-5"><?php echo $lang['faq']; ?></h2>
         <div class="accordion" id="accordionExample">
            <?php
               $result = $pdo->prepare("SELECT * FROM faq f INNER JOIN user u ON f.userid = u.id");
               $result->execute();
               if($result->rowCount() > 0) {
                  while($row = $result->fetch()) {
                     $id = $row['f_id'];
                     $question = $row['question'];
                     $answer = $row['answer'];
                     $by = $row['name'];
                     $helpful2 = $row['good'];
                     $nothelpful2 = $row['bad'];
                     $feedback = 'Was this article helpful? <a href="?r='.$id.'&v=1"><i class="far fa-thumbs-up"></i></a> ('.$helpful2.')';
                     if ($_SESSION['control'] == 1) {
                        $feedback = 'Thank you for your feedback!';
                     }
                     echo '<div class="card shadow-sm p-2 text text-left" id="'.$id.'">
                     <div class="card-header" id="headingOne">
                        <h5 class="mb-0">
                           <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse' . $id . '" aria-expanded="false" aria-controls="collapse' . $id . '">
                           <b>Q: ' . $question . '</b>
                           </button>
                        </h5>
                     </div>
                     <div id="collapse' . $id . '" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                        <div class="card-body">
                           Answered by: <i><a href="/user/'.$by.'/">'.$by.'</a></i> | '.$feedback.'<br><hr>' . $answer . '
                        </div>
                     </div>
                  </div>';
                  }
               }
            ?>
         </div>
      </div>
      </div>
   </div>
</section>

<div class="centerBasedFooterAd" style="text-align: center; bottom: 35%;">
    <!-- Footer-Block-Ads -->
    <ins class="adsbygoogle" style="display:inline-block;width:820px;height:200px" data-ad-client="ca-pub-9727102575141971" data-ad-slot="1867802594"></ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
</div>
<?php
   $pdo = null;
?>
