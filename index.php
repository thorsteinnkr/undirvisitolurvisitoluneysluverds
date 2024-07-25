<?php
  $man=(isset($_REQUEST['man'])?$_REQUEST['man']:"2024M01");
  $far=(substr($man,0,4)-1).substr($man,4,3);

  print("<style>a{text-decoration:none;}</style>");
  print("<h2>Undirliðir vísitölu neysluverðs</h2>");
  print("<h3>Breyting á 12 mánaða tímabili</h3>");
  print("<h2>".$man." / ".$far."</h2>");

  $mydb=new mysqli("localhost","dbuser","dbpassword","dbname");
  if ($mysqli -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
    exit();
  }

  print("<table><tr>");
  print("<td style='vertical-align:top;width:120px;'>");

  $sql="select distinct man from vnv order by man limit 1000 offset 12";
  $res=$mydb->query($sql);
  $heiti=array();
  while($row=$res->fetch_assoc()){
    $heiti[]=$row['man'];
  }
  rsort($heiti);
  foreach($heiti as $v){
    print("<a style='color:purple;text-decoration:underline;' href='?man=".$v."'>".$v."</a><br>");
  }

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
      $uttak[$row['heiti']]=$row['gildi']/$listi[$row['heiti']]-1;
    } else {
      $uttak[$row['heiti']]="0";
    }
  }

  $h="";
  foreach($uttak as $k=>$v){
    $gildi=number_format($v*100,1);
    $style='color:green;';
    if($gildi>2.5){
      $style='color:red;';
    }
    if($k=="Vísitala neysluverðs"){
      $style.="font-weight:bold;font-size:16pt;";
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
    if($h!=substr($k,0,2)){ print("<hr>");$h=substr($k,0,2);}
    $k="<a style='".$style."' href='lidur.php?lidur=".$k."' target='_blank'>".$k."</a>";
      print("<span style='".$style."'>".$k.": ".$gildi."%</span> ".str_repeat("&#128681",floor(($gildi-0.01)/2.5))."<br>");
  }
  print("<td style='vertical-align:top;'>");
  print("</tr></table>");
?>
