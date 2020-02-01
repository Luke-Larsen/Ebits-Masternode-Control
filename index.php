<?php
require_once ('easyEbits.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//WHEN INSTALLING THIS SCRIPT
//go into your masternode then type: nano ~/.EBITS/ebits.conf
//find where it says rpcallowip=127.0.0.1
//change that to 0.0.0.0/0 to allow any ip to connect to it or put in the ip of
//you webserver
//--OtherConfig--
$ExplorerAddr ='http://blockexplorer.ebitscrypto.com';

//--VPS Config--
//Make as many of these as you want just follow the format
$VPSMasterNodes = '1'; //How many masternodes you have
$MNName0 = 'mn1';//Just a nickname you want to see it by
$MNUser0 = 'user';//Username for the masternode
$MNPassword0 ='password';//password for the masternode
$MNHost0 = '';//ip were the masternode is at
$MNPort0 = '';//port for the rpc communication (IF you have problems try lowering the port number)
//copy this for how ever many nodes you have
// $MNName1 = 'mn2';//Just a nickname you want to see it by
// $MNUser1 = 'user';
// $MNPassword1 ='password';
// $MNHost1 = '';
// $MNPort1 = '';


//ACTUAL CODE leave this alone
//Getting Explorer Data
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $ExplorerAddr . "/api/getdifficulty",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
$responseDifficulty = json_decode($response, true);

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $ExplorerAddr . "/api/getconnectioncount",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
$responseConnections = json_decode($response, true);

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $ExplorerAddr . "/api/getnetworkhashps",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
$responseHashRate = json_decode($response, true);

$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => $ExplorerAddr . "/api/getblockcount",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
$response = json_decode($response, true);

for($x = 0; $x <= $VPSMasterNodes-1; $x++){
  ${"Ebits" . $x} = new Ebits("${"MNUser" . $x}","${"MNPassword" . $x}","${"MNHost" . $x}","${"MNPort" . $x}");
  ${"BlockCount" . $x} = ${"Ebits" . $x}->getblockcount();
  ${"UpTime" . $x}= ${"Ebits" . $x}->uptime();
}

if(isset($_POST['stop'])){
  for($x = 0; $x <= $VPSMasterNodes-1; $x++){
    ${"Ebits" . $x} = new Ebits("${"MNUser" . $x}","${"MNPassword" . $x}","${"MNHost" . $x}","${"MNPort" . $x}");
    ${"Ebits" . $x}->stop();
    echo ${"Ebits" . $x}->raw_response;
    echo "Stopping masternodes";
  }
}
?>
<html>
  <head>
    <title>Ebits</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <meta property='og:image' content='img/ebits.png'/>
    <link rel="image_src" href="img/ebits.png" />
  <head>
  <body>
    <nav id='navbarLinks' class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php"><img src="https://ebitscrypto.com/Images/ebits.png" alt="Ebits" style='height:100%; display:inline-block;'/> Ebits</a>
            </div>
            <div class="collapse navbar-collapse" id="myNavbar">
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="http://blockexplorer.ebitscrypto.com/" target='_blank'>Difficulty PoW: <?php echo $responseDifficulty['proof-of-work']?></a></li>
                    <li><a href="http://blockexplorer.ebitscrypto.com/" target='_blank'>Difficulty PoS: <?php echo $responseDifficulty['proof-of-stake']?></a></li>
                    <li><a href="http://blockexplorer.ebitscrypto.com/" target='_blank'>Connections: <?php echo $responseConnections?></a></li>
                    <li><a href="http://blockexplorer.ebitscrypto.com/" target='_blank'>Hashrate: <?php echo $responseHashRate?></a></li>
                    <li><a href="http://blockexplorer.ebitscrypto.com/" target='_blank'>Block: <?php echo $response?></a></li>
                    <li><a href="http://blockexplorer.ebitscrypto.com/" target='_blank'>EXPLORER</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <div style='margin-top:60px;'>
    <?php
      for($x = 0; $x <= $VPSMasterNodes-1; $x++){
        if(${"BlockCount" . $x} != NULL){
          $BlockCount = ${"BlockCount" . $x};
          $UpTime = ${"UpTime" . $x};
          if($BlockCount == $response){
            echo "<center> Masternode ${"MNName" . $x} at blockcount:$BlockCount upTime:$UpTime secounds</center>";
          }else{
            echo "<center style='color:red;'> Masternode ${"MNName" . $x} out of sync blockcount:$BlockCount upTime:$UpTime secounds</center>";
          }
        }else{
          echo "<center> Masternode $x is offline </center>";
        }
      }
     ?>
   </div>
    <div class='row bg-gray'>
      <div class='col-lg-6'>
        <center>
          <form>
            <input type="submit" class="btn btn-secondary"  style='display: inline;' name="refresh" value="refresh" onClick="window.location.reload();" placeholder="refresh Masternodes blockcount">
          </form>
      </center>
      </div>
      <div class='col-lg-6'>
        <center>
          <form action="" method="post">
            <input type="submit" class="btn btn-danger" style='display: inline;' name="stop" value="Stop All Masternodes" placeholder="Stop All Masternodes">
          </form>
        </center>
      </div>
    </div>
  </body>
</html>
