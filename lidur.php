<?php
  header('Content-Type: text/html; charset=iso-8859-1');
  $lidur=(isset($_REQUEST['lidur'])?$_REQUEST['lidur']:"Vísitala neysluverðs");
  //$far=(substr($man,0,4)-1).substr($man,4,3);

  print("<html><head>");
  print("<title>Undirli&eth;ir v&iacute;sit&ouml;lu neysluver&eth;s</title>");
    print("<style>*{font-family:lato;color:#133942;} a{text-decoration:underline;color:purple;}</style>");
  print("<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Lato:300,400,700,900' type='text/css'>");
  print("</head><body>");
  print("<h1><a href='/'>Undirli&eth;ir v&iacute;sit&ouml;lu neysluver&eth;s</a></h1>");
  print("<h2>".$lidur."</h2>");
  print("<h3>Breyting &aacute; 12 m&aacute;na&eth;a t&iacute;mabili</h3>");

  $mydb=new mysqli("sql301.infinityfree.com","if0_36987692","QF48g036zr2yAcj","if0_36987692_uvn");
  if ($mydb -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mydb -> connect_error;
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
  $n=0;
  foreach($gildi as $k=>$v){
    $far=(substr($k,0,4)-1).substr($k,4,3);
    if(isset($gildi[$far])){
      $p=number_format(($v/$gildi[$far]-1)*100,1);
      $style="color:#133942;";
      /*if($p>7.5){
        $style="color:red;background-color:yellow;";
      }
      else if($p>2.5){
        $style="color:red;";
      }
      if($p<0){
        $style="color:green;";
      }
      */
    if ($p>15){
      $style='color:white;background-color:black;';  
    } else if ($p>10){
      $style='color:yellow;background-color:red;';  
    } else if($p>7.5){
      $style='color:red;background-color:gold;';
    } else if($p>5){
      $style='color:red;background-color:yellow;';
    } else if($p>2.5){
      $style='color:red;';
    } else if($p<0){
      $style='background-color:lightgreen;';
    }

      $n++;      
      print("<span style='".$style."'>".$k." [".$v."]: ".$p."%</span>".($n%12==0?"<hr>":"<br>"));//.($p>2.5?str_repeat("&#128681",floor(($p)/2.5)):"")
    }
  }

  print("</tr></table>");
  print("</body></html>");
?>
