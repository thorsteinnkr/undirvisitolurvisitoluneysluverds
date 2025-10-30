<?php
  header('Content-Type: text/html; charset=iso-8859-1');
  $man=(isset($_REQUEST['man'])?$_REQUEST['man']:"2025M10");
  $far=(substr($man,0,4)-1).substr($man,4,3);
  $far24=(substr($man,0,4)-2).substr($man,4,3);
  $far36=(substr($man,0,4)-3).substr($man,4,3);

  print("<html><head>");
  print("<title>Undirli&eth;ir v&iacute;sit&ouml;lu neysluver&eth;s</title>");
  print("<style>*{font-family:lato;color:#133942;} a{text-decoration:none;}</style>");
  print("<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Lato:300,400,700,900' type='text/css'>");
  print("</head><body>");
  print("<h2>Undirli&eth;ir v&iacute;sit&ouml;lu neysluver&eth;s</h2>");
  print("<h3>Breyting &aacute; 12/24/36 m&aacute;na&eth;a t&iacute;mabili</h3>");
  print("<h2>".$man." / ".$far."</h2>");

  $mydb=new mysqli("sql301.infinityfree.com","if0_36987692","QF48g036zr2yAcj","if0_36987692_uvn");
  if ($mydb -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mydb -> connect_error;
    exit();
  }

//  print("<td style='vertical-align:top;width:120px;'>");

// Finna mánuði ->

  $sql="select distinct man from vnv order by man limit 1000 offset 36";
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


// <- Finna mánuði

  $listi=array();
  $listi24=array();
  $listi36=array();
  $sql="select heiti,gildi from vnv where man='".$far."'";
  $res=$mydb->query($sql);
  while($row=$res->fetch_assoc()){
    $listi[$row['heiti']]=$row['gildi'];
  }
  $sql="select heiti,gildi from vnv where man='".$far24."'";
  $res=$mydb->query($sql);
  while($row=$res->fetch_assoc()){
    $listi24[$row['heiti']]=$row['gildi'];
  }
  $sql="select heiti,gildi from vnv where man='".$far36."'";
  $res=$mydb->query($sql);
  while($row=$res->fetch_assoc()){
    $listi36[$row['heiti']]=$row['gildi'];
  }

  $uttak=array();
  $uttak24=array();
  $uttak36=array();
  
  $sql="select heiti,gildi from vnv where man='".$man."' order by heiti";
  $res=$mydb->query($sql);
  while($row=$res->fetch_assoc()){
    if(isset($listi[$row['heiti']])){
      if($listi[$row['heiti']]!=0){
          $uttak[$row['heiti']]=$row['gildi']/$listi[$row['heiti']]-1;
          $uttak24[$row['heiti']]=$row['gildi']/$listi24[$row['heiti']]-1;
          $uttak36[$row['heiti']]=$row['gildi']/$listi36[$row['heiti']]-1;
      }  else {
          $uttak[$row['heiti']]="0";
          $uttak24[$row['heiti']]="0";
          $uttak36[$row['heiti']]="0";
      }
    } else {
      $uttak[$row['heiti']]="0";
      $uttak24[$row['heiti']]="0";
      $uttak36[$row['heiti']]="0";
    }
  }

  $uttak = array_merge(array_splice($uttak, -1), $uttak);
  $uttak24 = array_merge(array_splice($uttak24, -1), $uttak24);
  $uttak36 = array_merge(array_splice($uttak36, -1), $uttak36);

  print("<table><tr>");
  print("<td style='vertical-align:top;'>");

  $h="";
  $p1="";
  $p2="";
  $p2.="<tr><td colspan='4'><hr></tr><tr><td><td>12M<td>24M<td>36M</tr><tr><td><td colspan='3'><hr></tr>";

  foreach($uttak as $k=>$v){
    $gildi=number_format($v*100,1);
    $gildi24=number_format($uttak24[$k]*100,1);
    $gildi36=number_format($uttak36[$k]*100,1);
    $style='color:#133942;'; 
    $style24='color:#133942;';
    $style36='color:#133942;';
    if ($gildi>15){
      $style='color:white;background-color:black;';  
    } else if ($gildi>10){
      $style='color:yellow;background-color:red;';  
    } else if($gildi>7.5){
      $style='color:red;background-color:gold;';
    } else if($gildi>5){
      $style='color:red;background-color:yellow;';
    } else if($gildi>2.5){
      $style='color:red;';
    } else if($gildi<0){
      $style='background-color:lightgreen;';
    }
    if ($gildi24>30){
      $style24='color:white;background-color:black;';  
    } else if($gildi24>20){
      $style24='color:yellow;background-color:red;';
    } else if($gildi24>15){
      $style24='color:red;background-color:gold;';
    } else if($gildi24>10){
      $style24='color:red;background-color:yellow;';
    } else if($gildi24>5){
      $style24='color:red;';
    } else if($gildi24<0){
      $style24='background-color:lightgreen;';
    }
    if ($gildi36>45){
      $style36='color:white;background-color:black;';  
    } else if($gildi36>30){
      $style36='color:yellow;background-color:red;';
    }  else if($gildi36>22.5){
      $style36='color:red;background-color:gold;';
    } else if($gildi36>15){
      $style36='color:red;background-color:yellow;';
    } else if($gildi36>7.5){
      $style36='color:red;';
    } else if($gildi36<0){
      $style36='background-color:lightgreen;';
    }

    if(substr($k,2,1)==" " || substr($k,0,1)=="V"){
      $k2="<a style='".$style."' href='lidur.php?lidur=".$k."' target='_blank'>".$k."</a>";  
      $p2.="<tr><td><span style='".$style."font-size:16pt;'>".$k2."</span>";
      $p2.="<td width='100px'><span style='".$style."font-size:16pt;'>".$gildi."%</span>  ";//".($gildi>2.5?str_repeat("&#128681",floor(($gildi)/2.5)):"")."
      $p2.="<td width='100px'><span style='".$style24."font-size:14pt;font-style: italic;'>".$gildi24."%</span> ";
      $p2.="<td width='100px'><span style='".$style36."font-size:14pt;font-style: italic;'>".$gildi36."%</span> </tr>";
    }
    /*if(substr($k,0,1)=="V"){
      $style.="font-weight:bold;font-size:16pt;;";
      $k2="<a style='".$style."' href='lidur.php?lidur=".$k."' target='_blank'>".$k."</a>";
      $p2.="<tr><td><span style='".$style."font-size:16pt;'>".$k2.": ".$gildi."%</span>  ";//".($gildi>2.5?str_repeat("&#128681",floor(($gildi)/2.5)):"")."
      $p2.="<td><span style='".$style24."font-size:14pt;font-style: italic;margin-left:16px;'>[24M: ".$gildi24."%]</span> ";
      $p2.="<td><span style='".$style36."font-size:14pt;font-style: italic;'>[36M: ".$gildi36."%]</span> </tr>";
    }*/
    if(substr($k,0,1)=="V"){
      $style.="font-weight:bold;font-size:16pt;;";
    }
    if(strpos($k," ")==2){
      $style.="font-weight:bold;font-size:16pt;";
      $style24.="font-weight:bold;font-size:14pt;";
      $style36.="font-weight:bold;font-size:14pt;";
    }
    if(strpos($k," ")==3){
      $style.="padding-left:4px;font-size:14pt;";
      $style24.="font-size:12pt;";
      $style36.="font-size:12pt;";
    }
    if(strpos($k," ")==4){
      $style.="padding-left:8px;font-size:12pt;";
      $style24.="font-size:11pt;";
      $style36.="font-size:11pt;";
    }
    if(strpos($k," ")==5){
      $style.="padding-left:12px;font-size:11pt;";
      $style24.="font-size:10pt;";
      $style36.="font-size:10pt;";
    }
    if($h!=substr($k,0,2)){ $p1.="<tr><td colspan='4'><hr></tr><tr><td><td>12M<td>24M<td>36M</tr><tr><td><td colspan='3'><hr></tr>";$h=substr($k,0,2);}

    $k="<a style='".$style."' href='lidur.php?lidur=".$k."' target='_blank'>".$k."</a>";
    $p1.="<tr><td><span style='".$style."'>".$k."</span>";
    $p1.="<td width='100px'><span style='".$style."'>".$gildi."%</span>";//".($gildi>2.5?str_repeat("&#128681",floor(($gildi)/2.5)):"")."
    $p1.="<td width='100px'><span style='".$style24."font-style: italic;'>".$gildi24."%</span>";
    $p1.="<td width='100px'><span style='".$style36."font-style: italic;'>".$gildi36."%</span> </tr>";
  }
  print("<table>".$p2."</table>");
  print("<td style='vertical-align:top;'>");
  print("<table>".$p1."</table>");
  print("</tr></table>");
  print("</body></html>");
?>
