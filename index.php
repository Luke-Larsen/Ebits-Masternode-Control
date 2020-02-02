<?php
session_start();
require_once ('Php/easyEbits.php');
require_once ('Php/config.php');
require_once ('Php/API.php');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if(isset($_SESSION['MasternodeControl'])&& $_SESSION['MasternodeControl'] == $ControlPassword){
  for($x = 0; $x <= $VPSMasterNodes-1; $x++){
    ${"Ebits" . $x} = new Ebits("${"MNUser" . $x}","${"MNPassword" . $x}","${"MNHost" . $x}","${"MNPort" . $x}");
    ${"BlockCount" . $x} = ${"Ebits" . $x}->getblockcount();
    ${"UpTime" . $x}= ${"Ebits" . $x}->uptime();
    ${"ConnectedEnabledMasternodes" . $x}= ${"Ebits" . $x}->masternode('count', 'enabled');
  }
}

if(isset($_POST['stop'])){
  if(isset($_SESSION['MasternodeControl'])&& $_SESSION['MasternodeControl'] == $ControlPassword){
    for($x = 0; $x <= $VPSMasterNodes-1; $x++){
      ${"Ebits" . $x}->stop();
    }
    echo("<script>location.href = 'index.php';</script>");
  }
}
if(isset($_POST['Logout'])){
  session_regenerate_id();
  session_destroy();
  echo("<script>location.href = 'index.php';</script>");
}
if(isset($_POST['enter'])){
  if($_POST["password"] == $ControlPassword){
    session_regenerate_id();
    $_SESSION['MasternodeControl'] = $ControlPassword;
    echo("<script>location.href = 'index.php';</script>");
  }else{
    header('location:404.php');
  }
}
if(isset($_POST['AddPeer'])){
  $peer = $_POST['peer'];
  if(isset($_SESSION['MasternodeControl'])&& $_SESSION['MasternodeControl'] == $ControlPassword){
    for($x = 0; $x <= $VPSMasterNodes-1; $x++){
      ${"Ebits" . $x}->setban($peer . '/32','remove');
    }
    echo("<script>location.href = 'index.php';</script>");
  }
}
if(isset($_POST['Addnode'])){
  $peer = $_POST['peer'];
  if(isset($_SESSION['MasternodeControl'])&& $_SESSION['MasternodeControl'] == $ControlPassword){
    for($x = 0; $x <= $VPSMasterNodes-1; $x++){
      ${"Ebits" . $x}->addnode($peer,'add');
    }
    echo("<script>location.href = 'index.php';</script>");
  }
}
if(isset($_POST['RemoveNode'])){
  $peer = $_POST['peer'];
  if(isset($_SESSION['MasternodeControl'])&& $_SESSION['MasternodeControl'] == $ControlPassword){
    for($x = 0; $x <= $VPSMasterNodes-1; $x++){
      ${"Ebits" . $x}->addnode($peer,'remove');
    }
    echo("<script>location.href = 'index.php';</script>");
  }
}
if(isset($_POST['DelPeer'])){
  $peer = $_POST['peer'];
  if(isset($_SESSION['MasternodeControl'])&& $_SESSION['MasternodeControl'] == $ControlPassword){
    for($x = 0; $x <= $VPSMasterNodes-1; $x++){
      ${"Ebits" . $x}->setban($peer . '/32','add');
    }
    echo("<script>location.href = 'index.php';</script>");
  }
}
if(isset($_POST['unBanAll'])){
  if(isset($_SESSION['MasternodeControl'])&& $_SESSION['MasternodeControl'] == $ControlPassword){
    for($x = 0; $x <= $VPSMasterNodes-1; $x++){
      ${"Ebits" . $x}->clearbanned();
    }
    echo("<script>location.href = 'index.php';</script>");
  }
}
if(isset($_SESSION['MasternodeControl'])&& $_SESSION['MasternodeControl'] == $ControlPassword){
?>
<html>
  <head>
    <title>Ebits Masternode Control</title>
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
          $MNName = ${"MNName" . $x};
          $MNCP = ${"ConnectedEnabledMasternodes" . $x};
          if($BlockCount >= $response){
            echo "<center> Masternode $MNName at blockcount:$BlockCount upTime:$UpTime secounds ConnectedToEnabled: $MNCP Peers</center>";
          }else{
            echo "<center style='color:red;'> Masternode $MNName out of sync blockcount:$BlockCount upTime:$UpTime secounds ConnectedToEnabled: $MNCP Peers</center>";
          }
        }else{
          $MNName = ${"MNName" . $x};
          echo "<center> Masternode $MNName is offline </center>";
        }
      }
     ?>
   </div>
    <div class='row'>
      <div class='col-lg-4'>
        <center>
          <form>
            <input type="submit" class="btn btn-secondary"  style='display: inline;' name="refresh" value="refresh" onClick="window.location.reload();" placeholder="refresh Masternodes blockcount">
          </form>
      </center>
      </div>
      <div class='col-lg-4'>
        <center>
          <form action="" method="post">
            <input type="submit" class="btn btn-warning" style='display: inline;' name="Logout" value="Logout" placeholder="Logout">
          </form>
        </center>
      </div>
      <div class='col-lg-4'>
        <center>
          <form action="" method="post">
            <input type="submit" class="btn btn-danger" style='display: inline;' name="stop" value="Stop All Masternodes" placeholder="Stop All Masternodes">
          </form>
        </center>
      </div>
    </div>
    <div class='row'>
      <div class='col-lg-4'>
        <center>
          <form action="" method="post">
            <input type="submit" class="btn btn-danger" style='display: inline;' name="unBanAll" value="unBanAll" placeholder="UnBan All Peers">
          </form>
        </center>
      </div>
      <div class='col-lg-4'>
        <center>
          <form action="" method="post">
            <input type="text" name='peer' class="form-control" size="50" placeholder="peer" required>
            <input type="submit" class="btn btn-warning" style='display: inline;' name="AddPeer" value="UNBAN Peer" placeholder="UNBAN Peer">
          </form>
        </center>
      </div>
      <div class='col-lg-4'>
        <center>
          <form action="" method="post">
            <input type="text" name='peer' class="form-control" size="50" placeholder="peer" required>
            <input type="submit" class="btn btn-danger" style='display: inline;' name="DelPeer" value="BAN Peer" placeholder="BAN Peer">
          </form>
        </center>
      </div>
    </div>
    <div class='row'>
      <div class='col-lg-4'>
        <center>
          <form action="" method="post">
            <input type="submit" class="btn btn-danger" style='display: inline;' name="unBanAll" value="unBanAll" placeholder="UnBan All Peers">
          </form>
        </center>
      </div>
      <div class='col-lg-4'>
        <center>
          <form action="" method="post">
            <input type="text" name='peer' class="form-control" size="50" placeholder="peer" required>
            <input type="submit" class="btn btn-warning" style='display: inline;' name="Addnode" value="Add Node" placeholder="Add Node">
          </form>
        </center>
      </div>
      <div class='col-lg-4'>
        <center>
          <form action="" method="post">
            <input type="text" name='peer' class="form-control" size="50" placeholder="peer" required>
            <input type="submit" class="btn btn-danger" style='display: inline;' name="RemoveNode" value="Remove Node" placeholder="Remove Node">
          </form>
        </center>
      </div>
    </div>
  </body>
</html>
<?php }else{?>
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
    <div class='row bg-gray'>
      <div class='col-lg-12'>
        <center>
          <form action="" method="post">
            <input type="password" name='password' class="form-control" size="50" placeholder="password" required>
            <input type="submit" class="btn btn-danger" style='display: inline;' name="enter" value="Enter" placeholder="Enter">
          </form>
        </center>
      </div>
    </div>
  </body>
</html>
<?php }?>
