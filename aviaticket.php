<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="css/style_index.css">
        <title>Авиа Билеты</title>
    </head>
    <body>
<?php

define('BERLOGIC_AVIA_URL','https://devlt.berlogic.de/Partner/Avia/v3');

define('BERLOGIC_AGENCY','pulkovoairport');
define('BERLOGIC_AGENT_CODE','pulkovoairport');
define('BERLOGIC_SALES_POINT','pulkovoairport');
define('BERLOGIC_PASSWORD','KG=bR5C8zrW2!');

//include 'ticket.php';
function newObj2Array($obj) {
  $out = array();
  $obj = (array)$obj;
  if(!empty($obj)) {
    foreach ($obj as $name => $value) {
      $value = (array)$value;
      if(isset($value[0]) && is_string($value[0]) && !isset($value[1])) {
        $out[strtolower($name)] =(string)$value[0];
      } else {
        $out[strtolower($name)] = newObj2Array($value);
      }
    }
  }
  return $out;
}

function newxml2array($xml){
  $a = simplexml_load_string('<?xml version="1.0" encoding="utf-8"?><root>'.$xml.'</root>',NULL, LIBXML_NOCDATA);
  return newObj2Array($a);
 }

function newelements2array($xml,$tag,$notrim=false) {
  $result=array();
  if($notrim) {
    if(preg_match_all("/<$tag>(.+?)<\/$tag>/ims",$xml,$m)) {
      for($i=0;$i<count($m[0]);$i++) {
        $result[$i]=newxml2array($m[1][$i]);
      }
    }
  } else {    if(preg_match_all("/<$tag>\s*(.+?)\s*<\/$tag>/ims",$xml,$m)) {
      for($i=0;$i<count($m[0]);$i++) {
        $result[$i]=newxml2array($m[1][$i]);
      }
    }
  }
  return $result;
}

// обработка ошибок и модификация ответа для более корректной обработки через simple_xml
// в случае ошибок, возвращает true
// в $action передаем searchFlightsResponse, checkPricingResponse 
// и т.д. по методу вебсервисов, которые ожидаем в ответе

function berlogic_check_errors(&$result,&$response,$action) {
  $response=str_replace('ns2:','',$response);
  $response=str_replace('xsi:type="airTicket"','xsi:type="ns2:airTicket"',$response);
  $response=str_replace('xsi:type="insurance"','xsi:type="ns2:insurance"',$response);
  $error='';
  if($action=='searchFlightsResponse' && preg_match("/<$action\s*[^>]*?\s*\/>/ims",$response)) {
    $error='Перелеты, соответствующие условиям поиска, не найдены. Пожалуйста, выберите другие критерии поиска. Например, даты, ограничение только прямые рейсы, клаcc перелета и повторите запрос.';
    $m[1]='searchFlightsResponse';
  } else if(empty($response) || preg_match("/<$action\s*[^>]*?>(.*)<\/$action>/ims",$response,$m)) {
    if(preg_match('/<faultcode>/ims',$response)) {
      if(preg_match('/<faultstring>(.*?)<\/faultstring>/ims',$response,$m)) $error=trim($m[1]);
    } elseif(preg_match('/<SOAP-ENV:Fault>/ims',$response)) {
      $error="Временно не доступно, повторите запрос позже";
      
    } elseif(empty($response)) {
      $error="Неизвестная ошибка, повторите запрос позже.";
    }
    if(!empty($error)) {
      $result['response']['error']=$error;
      return true;
    }
  } elseif(preg_match('/<SOAP-ENV:Fault>/ims',$response)) {
    $error="Неизвестная ошибка, повторите запрос позже.";
    $result['response']['error']=$error;
    return true;
  } else {
    $error="Неизвестная ошибка, повторите запрос позже.";
    if(preg_match('/<faultstring>(.*?)<\/faultstring>/ims',$response,$m)) {
      $error=trim($m[1]);
    }
    $result['response']['error']=$error;
    return true;
  }
  $response="<$action>\n".$m[1]."</$action>\n";
  return false;
}
 //-----------------------------------------------------получение данных из ticket.php ----------------------  
    error_reporting( E_ERROR );
   include 'searchFlights.php';
   //include 'ticket.php';
   $s_serviceClass=$_POST['serviceClass'];
   $s_beginLocation=$_POST['beginLocation'];
   $s_endLocation=$_POST['endLocation'];
   $s_adult=$_POST['value1'];
   $s_child=$_POST['value2'];
   $s_infant=$_POST['value3'];
   //$s_date=$_POST['date'];

   $xml = new SimpleXMLElement($xmlstr);
   $ns = $xml->getNamespaces(true);
    
   $serviceClass = $xml->children($ns['S'])->Body->children($ns['ns2'])->searchFlights->children($ns[''])->settings->serviceClass = $s_serviceClass;
   $beginLocation = $xml->children($ns['S'])->Body->children($ns['ns2'])->searchFlights->children($ns[''])->settings->route->beginLocation=$s_beginLocation;
   $endLocation = $xml->children($ns['S'])->Body->children($ns['ns2'])->searchFlights->children($ns[''])->settings->route->endLocation=$s_endLocation;
   $adult = $xml->children($ns['S'])->Body->children($ns['ns2'])->searchFlights->children($ns[''])->settings->seats->entry[0]->value=$s_adult;
   $child = $xml->children($ns['S'])->Body->children($ns['ns2'])->searchFlights->children($ns[''])->settings->seats->entry[1]->value=$s_child;
   $infant = $xml->children($ns['S'])->Body->children($ns['ns2'])->searchFlights->children($ns[''])->settings->seats->entry[2]->value=$s_infant;
  // $date = $xml->children($ns['S'])->Body->children($ns['ns2'])->searchFlights->children($ns[''])->settings->route->date->value=date('Y-m-dTH:i:sZ',strtotime($s_date));

   
   $xml->saveXML("Ticket.xml");
 
    $result=array();
   $xml=file_get_contents(dirname(__FILE__).'/Ticket.xml');
   //$xml->$s_xml;
   $response=berlogic_searchFlights($xml);

   if(berlogic_check_errors($result,$response,'searchFlightsResponse')) {
     // ошибка
     // делаем обработку и выдаем пользователю
          exit;
    }
   // преобразуем в массив
   $data=newelements2array($response,'return',false,true);

      header('Content-type: text/html; charset=utf-8');
      echo '<div style="text-align: center; color: darkslateblue; font-size: 2em; font-style: italic; font-weight: bold;">Авиабилеты</div>';
  echo '<table style="align: center" class="table_blur"  cellpadding="5" cellspacing="0" border="1">';
     echo '<tr><th>Категория пассажира</th><th>Сбор</th><th>Цена билета</th><th>Налог</th><th>Аэропорт вылета</th><th>Аэропорт прилета</th><th>Дата/Время</th><th>Мест</th><th>Самолет</th></tr>';

     foreach ($data as $key => $value) {
    //Если летим из Санкт-Петербурга
         if ($s_beginLocation=='LED'){
         if ($s_child==0){
             echo "<tr><td>".$value['cost']['elements']['category']='Взрослый'."</td><td>".
        $value['cost']['elements']['fee']."</td><td>".
        $value['cost']['elements']['tariff']."</td><td>".
        $value['cost']['elements']['taxes']."</td><td>".
        $value['segments'][0]['beginlocation']['displaycode']."</td><td>".
        $value['segments'][0]['endlocation']['displaycode']."</td><td>".
        date('H:i:s',strtotime($value['segments'][0]['begindate']))."</td><td>".
        $value['segments'][0]['seats']."</td><td>".
        $value['segments'][0]['operatingvendor']['displaycode']."</td></tr>"
        ;
 }
 else {
        echo "<tr><td>".$value['cost']['elements'][0]['category']='Взрослый'."</td><td>".
        $value['cost']['elements'][0]['fee']."</td><td>".
        $value['cost']['elements'][0]['tariff']."</td><td>".
        $value['cost']['elements'][0]['taxes']."</td><td>".
        $value['segments'][0]['beginlocation']['displaycode']."</td><td>".
        $value['segments'][0]['endlocation']['displaycode']."</td><td>".
        date('H:i:s',strtotime($value['segments'][0]['begindate']))."</td><td>".
        $value['segments'][0]['seats']."</td><td>".
        $value['segments'][0]['operatingvendor']['displaycode']."</td></tr>".
       
        "<tr><td>".$value['cost']['elements'][1]['category']='Ребенок'."</td><td>".
        $value['cost']['elements'][1]['fee']."</td><td>".
        $value['cost']['elements'][1]['tariff']."</td><td>".
        $value['cost']['elements'][1]['taxes']."</td><td>".
        $value['segments'][0]['beginlocation']['displaycode']."</td><td>".
        $value['segments'][0]['endlocation']['displaycode']."</td><td>".
        date('H:i:s',strtotime($value['segments'][0]['begindate']))."</td><td>".
        $value['segments'][0]['seats']."</td><td>".
        $value['segments'][0]['operatingvendor']['displaycode']."</td></tr>"
        ;
 }
 
         }
          //Если летим из др города
     else {
   //Если летят только взрослые
          if ($s_child==0) {
echo "<tr><td>".$value['cost']['elements']['category']='Взрослый'."</td><td>".
        $value['cost']['elements']['fee']."</td><td>".
        $value['cost']['elements']['tariff']."</td><td>".
        $value['cost']['elements']['taxes']."</td><td>".
        $value['segments']['beginlocation']['displaycode']."</td><td>".
        $value['segments']['endlocation']['displaycode']."</td><td>".
        date('H:i:s',strtotime($value['segments']['begindate']))."</td><td>".
        $value['segments']['seats']."</td><td>".
        $value['segments']['operatingvendor']['displaycode']."</td></tr>"
        ;
     }
     else {
         echo "<tr><td>".$value['cost']['elements'][0]['category']='Взрослый'."</td><td>".
        $value['cost']['elements'][0]['fee']."</td><td>".
        $value['cost']['elements'][0]['tariff']."</td><td>".
        $value['cost']['elements'][0]['taxes']."</td><td>".
        $value['segments']['beginlocation']['displaycode']."</td><td>".
        $value['segments']['endlocation']['displaycode']."</td><td>".
        date('H:i:s',strtotime($value['segments']['begindate']))."</td><td>".
        $value['segments']['seats']."</td><td>".
        $value['segments']['operatingvendor']['displaycode']."</td></tr>".
       
        "<tr><td>".$value['cost']['elements'][1]['category']='Ребенок'."</td><td>".
        $value['cost']['elements'][1]['fee']."</td><td>".
        $value['cost']['elements'][1]['tariff']."</td><td>".
        $value['cost']['elements'][1]['taxes']."</td><td>".
        $value['segments']['beginlocation']['displaycode']."</td><td>".
        $value['segments']['endlocation']['displaycode']."</td><td>".
        date('H:i:s',strtotime($value['segments']['begindate']))."</td><td>".
        $value['segments']['seats']."</td><td>".
        $value['segments']['operatingvendor']['displaycode']."</td></tr>"
        ;
     }
     }
     }
   
     

echo "</table>";
  
     //echo '<pre>';
     //print_r($data);
     //echo '</pre>';
      
// поиск вариантов (второй шаг)
function berlogic_searchFlights($xml) {
   // $xml=file_get_contents(dirname(__FILE__).'/xml/searchFlights.xml');
    $data=curl_post_request($xml,BERLOGIC_AVIA_URL);
    

  // тут можно обработать ошибки, записать логи и т.д. для $data
  return $data['response'];
}


function curl_post_request($xml,$url,$timeout=30) {
  $result=array();
  $result['start_time']=microtime(true);
  
  $content=$xml;
  $content=str_replace('__BERLOGIC_AGENCY__',BERLOGIC_AGENCY,$content);
  $content=str_replace('__BERLOGIC_AGENT_CODE__',BERLOGIC_AGENT_CODE,$content);
  $content=str_replace('__BERLOGIC_SALES_POINT__',BERLOGIC_SALES_POINT,$content);
  $content=str_replace('__BERLOGIC_PASSWORD__',BERLOGIC_PASSWORD,$content);

  $headers = array(
    'Content-type: text/xml;charset=utf-8',
//    "Accept: text/xml",
    "Accept-Encoding: gzip",
    'SOAPAction: '
  );
  $headers[]="Content-length: ".strlen($content);

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30); 
  curl_setopt($ch, CURLOPT_TIMEOUT,        $timeout); 
  curl_setopt($ch, CURLOPT_ENCODING , "");
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $content); // the SOAP request
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  $result['response'] = curl_exec($ch); 
  $error = curl_error($ch);
  $curl_info = curl_getinfo($ch); 
  curl_close($ch);
  $result['len']=$curl_info['size_download'];
  $result['end_time']=microtime(true);
  $result['time']=$result['end_time']-$result['start_time'];
  if($error) {
    $result[0]['error']=$error;
    $result[0]['error_code']=7;
  } else if(strlen($result['response'])==0) {
    $result[0]['error']='Request timeout';
    $result[0]['error_code']=7;
  }
  return $result;
}
?>
  </body>
</html>
