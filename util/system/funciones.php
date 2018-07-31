<?php

class Funciones{
    
    public static function EscribirLogs($nomfile, $descripcion, $ubi = 1){
        $carpeta = "../logs/";
        if($ubi == 2){
            $carpeta = "util/logs/";
        }
        
        $myfile = fopen( $carpeta . $nomfile . ".txt", "a") or die("Archivo inaccessible!");
        fwrite($myfile, date('Y-m-d H:i:s') . " >>> " . $descripcion . "\r\n");
        fclose($myfile);
    }
    
    public static function clrAll($str) {
       $str=str_replace("&","&amp;",$str);
       $str=str_replace("\"","&quot;",$str);
       $str=str_replace("'","&apos;",$str);
       $str=str_replace(">","&gt;",$str);
       $str=str_replace("<","&lt;",$str);
       return $str;
    } 
    
    public static function ObtenerIp(){
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if(getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if(getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if(getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if(getenv('HTTP_FORWARDED'))
           $ipaddress = getenv('HTTP_FORWARDED');
        else if(getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }
    
    public static function ObtenerNavegador($useragent){
        /*
        $navegadores = array(
                  'Opera' => 'Opera',
                  'Mozilla Firefox'=> '(Firebird)|(Firefox)',
                  'Galeon' => 'Galeon',
                  'Mozilla'=>'Gecko',
                  'MyIE'=>'MyIE',
                  'Lynx' => 'Lynx',
                  'Netscape' => '(Mozilla/4\.75)|(Netscape6)|(Mozilla/4\.08)|(Mozilla/4\.5)|(Mozilla/4\.6)|(Mozilla/4\.79)',
                  'Konqueror'=>'Konqueror',
                  'Internet Explorer 7' => '(MSIE 7\.[0-9]+)',
                  'Internet Explorer 6' => '(MSIE 6\.[0-9]+)',
                  'Internet Explorer 5' => '(MSIE 5\.[0-9]+)',
                  'Internet Explorer 4' => '(MSIE 4\.[0-9]+)',
        );
        foreach($navegadores as $navegador=>$pattern){
           if (eregi($pattern, $user_agent))
           return $navegador;
        }
        return 'Desconocido';
        //*****************************************************************
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        */
        if (preg_match('|MSIE ([0-9].[0-9]{1,2})|',$useragent,$matched)) {
            $browser_version=$matched[1];
            $browser = 'IE';
        } elseif (preg_match( '|Opera/([0-9].[0-9]{1,2})|',$useragent,$matched)) {
            $browser_version=$matched[1];
            $browser = 'Opera';
        } elseif(preg_match('|Firefox/([0-9\.]+)|',$useragent,$matched)) {
            $browser_version=$matched[1];
            $browser = 'Firefox';
        } elseif(preg_match('|Chrome/([0-9\.]+)|',$useragent,$matched)) {
            $browser_version=$matched[1];
            $browser = 'Chrome';
        } elseif(preg_match('|Safari/([0-9\.]+)|',$useragent,$matched)) {
            $browser_version=$matched[1];
            $browser = 'Safari';
        } else {
            // browser not recognized!
            $browser_version = 0;
            $browser= 'Desconocido';
        }
        return $browser." - ".$browser_version;
    }
    
}

?>