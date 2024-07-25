<?php

  $lidur=(isset($_REQUEST['lidur'])?$_REQUEST['lidur']:"Vísitala neysluverðs");
  $far=(substr($man,0,4)-1).substr($man,4,3);

  print("<h2>".$lidur."</h2>");
  print("<h3>Breyting á 12 mánaða tímabili</h3>");

  $mydb=new mysqli("localhost","dbuser","dbpassword","dbname");
  if ($mysqli -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
    exit();
  }

  print("<table><tr>");
  print("<td style='vertical-align:top;'>");

  $sql="select man, gildi from vnv where heiti='".$lidur."' order by man desc";
  $res=$mydb->query($sql);
  $gildi=array();
  while($row=$res->fetch_assoc()){
    $gildi[$row['man']]=$row['gildi'];
  }
  foreach($gildi as $k=>$v){
    $far=(substr($k,0,4)-1).substr($k,4,3);
    if(isset($gildi[$far])){
      $p=number_format(($v/$gildi[$far]-1)*100,1);
      $style="color:green";
      if($p>2.5){
        $style="color:red";
      }
      print("<span style='".$style."'>".$k.": ".$p."%</span>".str_repeat("&#128681",floor(($p-0.01)/2.5))."<br>");
    }
  }

  print("</tr></table>");
?>
