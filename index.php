<?php
  header('Content-Type: text/html; charset=iso-8859-1');
  $man=(isset($_REQUEST['man'])?$_REQUEST['man']:"2024M07");
  $far=(substr($man,0,4)-1).substr($man,4,3);

  print("<html><head>");
  print("<title>Undirli&eth;ir v&iacute;sit&ouml;lu neysluver&eth;s</title>");
  print("<style>*{font-family:lato;color:#133942;} a{text-decoration:none;}</style>");
  print("<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Lato:300,400,700,900' type='text/css'>");
  print("</head><body>");
  print("<h2>Undirli&eth;ir v&iacute;sit&ouml;lu neysluver&eth;s</h2>");
  print("<h3>Breyting &aacute; 12 m&aacute;na&eth;a t&iacute;mabili</h3>");
  print("<h2>".$man." / ".$far."</h2>");

  $mydb=new mysqli("localhost","dbuser","dbpassword","dbname");
  if ($mydb -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mydb -> connect_error;
    exit();
  }

//  print("<td style='vertical-align:top;width:120px;'>");

  $sql="select distinct man from vnv order by man limit 1000 offset 12";
  $res=$mydb->query($sql);
  $heiti=array();
  while($row=$res->fetch_assoc()){
    $heiti[]=$row['man'];
  }
  rsort($heiti);
  print("<form name='val1' method='get' action='?'><select name='man' id='man'>");
  foreach($heiti as $v){
    print("<option value='".$v."' ".($man==$v?"selected":"").">".$v."</option>");
  }
  print("</select><input class='land' type='submit' value='S&aelig;kja'></form>");

  print("<table><tr>");
  print("<td style='vertical-align:top;'>");

  $listi=array();
  $sql="select heiti,gildi from vnv where man='".$far."'";
  $res=$mydb->query($sql);
  while($row=$res->fetch_assoc()){
    $listi[$row['heiti']]=$row['gildi'];
  }

  $uttak=array();
  $sql="select heiti,gildi from vnv where man='".$man."'";
  $res=$mydb->query($sql);
  while($row=$res->fetch_assoc()){
    if(isset($listi[$row['heiti']])){
      if($listi[$row['heiti']]!=0){
          $uttak[$row['heiti']]=$row['gildi']/$listi[$row['heiti']]-1;
      }  else {
          $uttak[$row['heiti']]=0;
      }
    } else {
      $uttak[$row['heiti']]="0";
    }
  }

  $h="";
  $p1="";
  $p2="";
  foreach($uttak as $k=>$v){
    $gildi=number_format($v*100,1);
    $style='color:#133942;';
    if($gildi>7.5){
      $style='color:red;background-color:yellow;';
    }
    else if($gildi>2.5){
      $style='color:red;';
    }
    if($gildi<0){
      $style='color:green;';
    }
    if(substr($k,2,1)==" "){
      $k2="<a style='".$style."' href='lidur.php?lidur=".$k."' target='_blank'>".$k."</a>";  
      $p2.="<span style='".$style."font-size:16pt;'>".$k2.": ".$gildi."%</span> ".($gildi>2.5?str_repeat("&#128681",floor(($gildi)/2.5)):"")."<br>";
    }
    if(substr($k,0,1)=="V"){
      $style.="font-weight:bold;font-size:16pt;;";
      $k2="<a style='".$style."' href='lidur.php?lidur=".$k."' target='_blank'>".$k."</a>";
      $p2.="<span style='".$style."font-size:16pt;'>".$k2.": ".$gildi."%</span> ".($gildi>2.5?str_repeat("&#128681",floor(($gildi)/2.5)):"")."<br>";
    }
    if(strpos($k," ")==2){
      $style.="font-weight:bold;font-size:16pt;";
    }
    if(strpos($k," ")==3){
      $style.="padding-left:4px;font-size:14pt;";
    }
    if(strpos($k," ")==4){
      $style.="padding-left:8px;font-size:12pt;";
    }
    if(strpos($k," ")==5){
      $style.="padding-left:12px;font-size:11pt;";
    }
    if($h!=substr($k,0,2)){ $p1.="<hr>";$h=substr($k,0,2);}
      $k="<a style='".$style."' href='lidur.php?lidur=".$k."' target='_blank'>".$k."</a>";
      $p1.="<span style='".$style."'>".$k.": ".$gildi."%</span> ".($gildi>2.5?str_repeat("&#128681",floor(($gildi)/2.5)):"")."<br>";

  }
  print($p2);
  print("<td style='vertical-align:top;'>");
  print($p1);
  print("</tr></table>");
  print("</body></html>");
?>
