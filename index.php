<?php
  header('Content-Type: text/html; charset=iso-8859-1');
  $man=(isset($_REQUEST['man'])?$_REQUEST['man']:"2025M10");
  $far=(substr($man,0,4)-1).substr($man,4,3);

  print("<html><head>");
  print("<title>Undirli&eth;ir v&iacute;sit&ouml;lu neysluver&eth;s</title>");
  print("<script>function vis(myDiv) { var x = document.getElementById(myDIV); if (x.style.display === 'none') { x.style.display = 'block'; } else { x.style.display = 'none'; } }</script>");
  print("<style>*{font-family:lato;color:#133942;} a{text-decoration:none;}</style>");
  print("<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Lato:300,400,700,900' type='text/css'>");
  print("</head><body>");
  print("<h2>Undirli&eth;ir v&iacute;sit&ouml;lu neysluver&eth;s</h2>");
  print("<h3>Breyting &aacute; 12 m&aacute;na&eth;a t&iacute;mabili</h3>");
  print("<h2>".$man." / ".$far."</h2>");

  $mydb=new mysqli("sql301.infinityfree.com","if0_36987692","QF48g036zr2yAcj","if0_36987692_uvn");
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
  $sql="select heiti,gildi from vnv where man='".$man."' order by heiti";
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

  $uttak = array_merge(array_splice($uttak, -1), $uttak);

  $h="";
  $p1="";
  $p2="";
  $b=array();
  foreach($uttak as $k=>$v){
    $gildi=number_format($v*100,1);
    $style='color:#133942;';
    $div="";
    /*if($gildi>7.5){
      $style='color:red;background-color:yellow;';
    }
    else if($gildi>2.5){
      $style='color:red;';
    }
    if($gildi<0){
      $style='color:green;';
    }*/
    if ($gildi>15){
      $style='color:white;background-color:black;';  
      $b[1]++;
    } else if ($gildi>10){
      $style='color:yellow;background-color:red;';  
      $b[1]++;
    } else if($gildi>7.5){
      $style='color:red;background-color:gold;';
      $b[1]++;
    } else if($gildi>5){
      $style='color:red;background-color:yellow;';
      $b[1]++;
    } else if($gildi>2.5){
      $style='color:red;';
      $b[0]++;
    } else if($gildi>=0){
      $b[0]++;      
    } else if($gildi<0){
      $style='background-color:lightgreen;';
      $b[0]++;
    }
    if(substr($k,2,1)==" "){
      $k2="<a style='".$style."' href='lidur.php?lidur=".$k."' target='_blank'>".$k."</a>";  
      $p2.="<span style='".$style."font-size:16pt;'>".$k2.": ".$gildi."%</span> <br>";//".($gildi>2.5?str_repeat("&#128681",floor(($gildi)/2.5)):"")."
    }
    if(substr($k,0,1)=="V"){
      $style.="font-weight:bold;font-size:16pt;;";
      $k2="<a style='".$style."' href='lidur.php?lidur=".$k."' target='_blank'>".$k."</a>";
      $p2.="<span style='".$style."font-size:16pt;'>".$k2.": ".$gildi."%</span> <br>";//".($gildi>2.5?str_repeat("&#128681",floor(($gildi)/2.5)):"")."
    }
    if(strpos($k," ")==2){
      $style.="font-weight:bold;font-size:16pt;";
      $div="<button onClick='vis(d".substr($k,0,2).")'>(+)</button><div style='display:block;' id='d".substr($k,0,2)."'>";
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
      $k="<a style='".$style."' href='lidur.php?lidur=".$k."' target='_blank'>".$k."</a> ";
      $p1.="\n<span style='".$style."'>".$k.": ".$gildi."%</span> ".$div."<br>";//".($gildi>2.5?str_repeat("&#128681",floor(($gildi)/2.5)):"")."

  }
  print($p2);
  print("<br>");
  $hlutfall=floor($b[0]/($b[0]+$b[1])*100);
  print("Fj&ouml;ldi undir 5%: ".$b[0]." (".$hlutfall."%)<br>");
  print("Fj&ouml;ldi yfir 5%: ".$b[1]." (".(100-$hlutfall)."%)<br>");
  print("<td style='vertical-align:top;'>");
  print($p1);
  print("</tr></table>");
  print("</body></html>");
?>
