<?
function now_for_mysql(){
	return date('Y-m-d H:i:s');
}

function empty_date(){
	return "0000-00-00";
}

function empty_time(){
	return "00:00:00";
}

function date_to_sortable($date){
	if(stristr($date, empty_date())) :
		return '';
	else :
		if(strlen($date > 10)) :
			$tokens = explode(' ', $date);
			return $tokens[0];
		else :
			return $date;
		endif;
	endif;
}


function empty_datetime(){
	return empty_date() . " " . empty_time();
}

function mdy_to_mysql($date){
		
  $tokens = split("/", $date);

  if(isset($tokens[0]) && isset($tokens[1]) && isset($tokens[2])) :
	  if(strlen($tokens[2]) == 2) $tokens[2] = "20" . $tokens[2];
	  if($tokens[0] == '00' && $tokens[1] == '00') return '';
	  else return $tokens[2] . "/" . $tokens[0] . "/" . $tokens[1];
  else :
	  return "";
  endif;

}

function time_to_mysql($time){
	$parts = strptime($time, "%H:%M");
	
	//adjust to 24 hour time
	$adjust = $parts['tm_hour'] != 12 && preg_match('/pm/i', $time) ? 12 : 0;
	
	if(isset($parts['tm_min']) && isset($parts['tm_hour'])){
		if($parts['tm_min'] < 10){ $parts['tm_min'] = '0' . $parts['tm_min']; }
		return ($parts['tm_hour'] + $adjust) . ":" . $parts['tm_min'] . ":00";
	}else{
		return "00:00:00";	
	}
}

function mysql_to_time($dt){
	$yr=strval(substr($dt,0,4));
	$mo=strval(substr($dt,5,2));
	$da=strval(substr($dt,8,2));
	$hr=strval(substr($dt,11,2));
	$mi=strval(substr($dt,14,2));
	// $se=strval(substr($dt,17,2));

	return date("h:i a ", mktime ($hr,$mi,0,$mo,$da,$yr))."";
}

function date_to_mdy($dt){
  	$yr=strval(substr($dt,0,4));
	$mo=strval(substr($dt,5,2));
	$da=strval(substr($dt,8,2));
	
	if($mo == '00' && $da == '00') return '';
	return "$mo/$da/$yr";
  
}

function mysql_to_human($str){
	return mysql_datetime_to_human($str);
}

function mysql_datetime_to_human($dt)
{
$yr=strval(substr($dt,0,4));
$mo=strval(substr($dt,5,2));
$da=strval(substr($dt,8,2));
$hr=strval(substr($dt,11,2));
$mi=strval(substr($dt,14,2));
// $se=strval(substr($dt,17,2));

return date("M d @ h:i a ", mktime ($hr,$mi,0,$mo,$da,$yr))."";
} 

function mysql_to_mdy($dt, $default_now = true){
  $time = human_to_unix($dt);
  if($time > 0 && $dt != '0000-00-00 00:00:00')
	  return  mdate("%m/%d/%Y", $time);
  elseif($default_now)
	  return mdate("%m/%d/%Y", time());
  else
	  return "";
}

function days_from_today($date_string){
	$unix_due = human_to_unix($date_string);
	$days_due = ($unix_due - strtotime(date("Y-m-d"))) / (60 * 60 * 24);
	return floor($days_due);
}

//Replacement for php function found on php website
if(function_exists("strptime") == false)
{
    function strptime($sDate, $sFormat)
    {
        $aResult = array
        (
            'tm_sec'   => 0,
            'tm_min'   => 0,
            'tm_hour'  => 0,
            'tm_mday'  => 1,
            'tm_mon'   => 0,
            'tm_year'  => 0,
            'tm_wday'  => 0,
            'tm_yday'  => 0,
            'unparsed' => $sDate,
        );
        
        while($sFormat != "")
        {
            // ===== Search a %x element, Check the static string before the %x =====
            $nIdxFound = strpos($sFormat, '%');
            if($nIdxFound === false)
            {
                
                // There is no more format. Check the last static string.
                $aResult['unparsed'] = ($sFormat == $sDate) ? "" : $sDate;
                break;
            }
            
            $sFormatBefore = substr($sFormat, 0, $nIdxFound);
            $sDateBefore   = substr($sDate,   0, $nIdxFound);
            
            if($sFormatBefore != $sDateBefore) break;
            
            // ===== Read the value of the %x found =====
            $sFormat = substr($sFormat, $nIdxFound);
            $sDate   = substr($sDate,   $nIdxFound);
            
            $aResult['unparsed'] = $sDate;
            
            $sFormatCurrent = substr($sFormat, 0, 2);
            $sFormatAfter   = substr($sFormat, 2);
            
            $nValue = -1;
            $sDateAfter = "";
            switch($sFormatCurrent)
            {
                case '%S': // Seconds after the minute (0-59)
                    
                    sscanf($sDate, "%2d%[^\\n]", $nValue, $sDateAfter);
                    
                    if(($nValue < 0) || ($nValue > 59)) return false;
                    
                    $aResult['tm_sec']  = $nValue;
                    break;
                
                // ----------
                case '%M': // Minutes after the hour (0-59)
                    sscanf($sDate, "%2d%[^\\n]", $nValue, $sDateAfter);
                    
                    if(($nValue < 0) || ($nValue > 59)) return false;
                
                    $aResult['tm_min']  = $nValue;
                    break;
                
                // ----------
                case '%H': // Hour since midnight (0-23)
                    sscanf($sDate, "%2d%[^\\n]", $nValue, $sDateAfter);
                    
                    if(($nValue < 0) || ($nValue > 23)) return false;
                
                    $aResult['tm_hour']  = $nValue;
                    break;
                
                // ----------
                case '%d': // Day of the month (1-31)
                    sscanf($sDate, "%2d%[^\\n]", $nValue, $sDateAfter);
                    
                    if(($nValue < 1) || ($nValue > 31)) return false;
                
                    $aResult['tm_mday']  = $nValue;
                    break;
                
                // ----------
                case '%m': // Months since January (0-11)
                    sscanf($sDate, "%2d%[^\\n]", $nValue, $sDateAfter);
                    
                    if(($nValue < 1) || ($nValue > 12)) return false;
                
                    $aResult['tm_mon']  = ($nValue - 1);
                    break;
                
                // ----------
                case '%Y': // Years since 1900
                    sscanf($sDate, "%4d%[^\\n]", $nValue, $sDateAfter);
                    
                    if($nValue < 1900) return false;
                
                    $aResult['tm_year']  = ($nValue - 1900);
                    break;
                
                // ----------
                default: break 2; // Break Switch and while
            }
            
            // ===== Next please =====
            $sFormat = $sFormatAfter;
            $sDate   = $sDateAfter;
            
            $aResult['unparsed'] = $sDate;
            
        } // END while($sFormat != "")
        
        
        // ===== Create the other value of the result array =====
        $nParsedDateTimestamp = mktime($aResult['tm_hour'], $aResult['tm_min'], $aResult['tm_sec'],
                                $aResult['tm_mon'] + 1, $aResult['tm_mday'], $aResult['tm_year'] + 1900);
        
        // Before PHP 5.1 return -1 when error
        if(($nParsedDateTimestamp === false)
        ||($nParsedDateTimestamp === -1)) return false;
        
        $aResult['tm_wday'] = (int) strftime("%w", $nParsedDateTimestamp); // Days since Sunday (0-6)
        $aResult['tm_yday'] = (strftime("%j", $nParsedDateTimestamp) - 1); // Days since January 1 (0-365)

        return $aResult;
    } // END of function
    
} // END if(function_exists("strptime") == false) 

//end of datetime_helper.php