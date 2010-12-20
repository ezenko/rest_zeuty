<?php
/**
* Calendar event management class
*
* @package RealEstate
* @subpackage Include
* @copyright Pilot Group <http://www.pilotgroup.net/>
* @author $Author: irina $
* @version $Revision: 1.1 $ $Date: 2008/09/25 10:38:29 $
**/

class CalendarEvent {
	/**
	 * Database connection instance of a class
	 *
	 * @var object	 
	 */
	var $_dbconn;
	var $month_count;	
	var $month_shift;

	/**
	* Class Constructor
	* @access public	
	* @return void
	**/
	function CalendarEvent() {
		global $dbconn, $config;

		$this->_dbconn = $dbconn;					
	}

	/**
	 * Get data for calendar displaying
	 *
	 * @param int $start_month
	 * @param int $start_year
	 * @param int $id_ad
	 * @param int $user_id
	 * @return array
	 */
	function GetMonthYearArray($start_month, $start_year, $id_ad, $user_id, $month_count, $month_shift){
		$array = array();
		$array["month"]["cur0"] = $start_month;
		$array["year"]["cur0"] = $start_year;
		
		if ($start_month - 1 < 1) {
			$array["prev_mon_1"] =$start_month + 11;
			$array["prev_year_1"] = $start_year - 1;
		} else {
			$array["prev_mon_1"] =$start_month - 1;
			$array["prev_year_1"] = $start_year;
		}
		
		if ($start_month - $month_shift < 1) {
			$array["prev_mon"] =$start_month - $month_shift + 12;
			$array["prev_year"] = $start_year - 1;
		} else {
			$array["prev_mon"] =$start_month - $month_shift;
			$array["prev_year"] = $start_year;
		}
		
		for ($i = 1; $i < $month_count; $i++){
			if ($start_month+$i > 12) {
				for ($k = $i; $k < $month_count; $k++){
					$array["month"]["cur$k"] = $start_month + $k - 12;
					$array["year"]["cur$k"] = $start_year + 1;					
				}
			} else {
				$array["month"]["cur$i"] =$start_month+$i;
				$array["year"]["cur$i"] = $start_year;
			}
		}		
				
		if ($start_month + $month_shift > 12) {
			$array["next_mon"] =$start_month - 12 + $month_shift;
			$array["next_year"] = $start_year + 1;
		} else {
			$array["next_mon"] =$start_month + $month_shift;
			$array["next_year"] = $start_year;
		}
		
		if ($start_month + 1 > 12) {
			$array["next_mon_1"] =$start_month - 11;
			$array["next_year_1"] = $start_year + 1;
		} else {
			$array["next_mon_1"] =$start_month + 1;
			$array["next_year_1"] = $start_year;
		}
		
		$current_day = adodb_getdate();
		$available_date = $this->GetMovedate($id_ad);
				
		foreach ($array["month"] as $key => $value)
		{
			$amount_days = adodb_date( "t", adodb_mktime(0, 0, 0, $value, 1, $array["year"][$key]) );			
			$first_day_tmstmp_start = adodb_mktime(0, 0, 0, $value, 1, $array["year"][$key]);
			$first_day_tmstmp_end = adodb_mktime(23, 59, 59, $value, 1, $array["year"][$key]);
			$first_day = adodb_getdate($first_day_tmstmp_start);									
			$reserve_days = $this->GetReserveDays($id_ad, $user_id);
			
			$week = array(	0 => "false",
								1 => "false",
								2 => "false",
								3 => "false",
								4 => "false",
								5 => "false",
								6 => "false");
			$week[$first_day["wday"]] = array("mday" => $first_day["mday"],"wday" => $first_day["wday"]);
			
			if ( $current_day["mday"] == $first_day["mday"] &&
					 $current_day["mon"] == $first_day["mon"] &&
					 $current_day["year"] == $first_day["year"] )
				{
					$week[$first_day["wday"]]["current_day"] = "true";
				} else {
					$week[$first_day["wday"]]["current_day"] = "false";
				}
				
			if ($first_day_tmstmp_start < $available_date){
				$week[$first_day["wday"]]["reserved_day"] = "not_available";
			} else{
				$week[$first_day["wday"]]["reserved_day"] = "false";	
				$half_period_count = 0;		
				foreach ($reserve_days AS $period){								
					if ( $first_day_tmstmp_start >= $period["start_tmstmp"] && $first_day_tmstmp_end <= $period["end_tmstmp"])
					{
						$week[$first_day["wday"]]["reserved_day"] = "true";										
					}elseif ( $first_day_tmstmp_start <= $period["end_tmstmp"] && $first_day_tmstmp_end >= $period["end_tmstmp"] ){
						$week[$first_day["wday"]]["reserved_day"] = "half_tf";										
						$half_period_count++;
					}elseif ( $first_day_tmstmp_start <= $period["start_tmstmp"] && $first_day_tmstmp_end >= $period["start_tmstmp"] ){
						$week[$first_day["wday"]]["reserved_day"] = "half_ft";										
						$half_period_count++;
					}
				}
				if ($half_period_count > 1){
					$week[$first_day["wday"]]["reserved_day"] = "half_tft";						
				}
			}
			
			
			for ( $days_cnt = 2; $days_cnt <= $amount_days; $days_cnt++ ) {
				
				$this_day_tmstmp_start = adodb_mktime(0, 0, 0, $first_day["mon"], $days_cnt, $first_day["year"]);
				$this_day_tmstmp_end = adodb_mktime(23, 59, 59, $first_day["mon"], $days_cnt, $first_day["year"]);
				$this_day = adodb_getdate($this_day_tmstmp_start);

				if ( $this_day["wday"] == 1 ) {
					$array["calendar"]["month"][$key][] = $week;
					$week = array(
									0 => "false",
									1 => "false",
									2 => "false",
									3 => "false",
									4 => "false",
									5 => "false",
									6 => "false");
				}
				$week[$this_day["wday"]] = array("mday" => $this_day["mday"],"wday" => $this_day["wday"]);
				if ( $current_day["mday"] == $this_day["mday"] &&
					 $current_day["mon"] == $this_day["mon"] &&
					 $current_day["year"] == $this_day["year"] )
				{
					$week[$this_day["wday"]]["current_day"] = "true";
				} else {
					$week[$this_day["wday"]]["current_day"] = "false";
				}
				
				if ($this_day_tmstmp_start < $available_date){
				$week[$this_day["wday"]]["reserved_day"] = "not_available";
				} else{
					$week[$this_day["wday"]]["reserved_day"] = "false";
					$half_period_count = 0;
					foreach ($reserve_days AS $period){					
						if ( $this_day_tmstmp_start >= $period["start_tmstmp"] && $this_day_tmstmp_end <= $period["end_tmstmp"])
						{
							$week[$this_day["wday"]]["reserved_day"] = "true";								
						}elseif ( $this_day_tmstmp_start <= $period["end_tmstmp"] && $this_day_tmstmp_end >= $period["end_tmstmp"] ){
							$week[$this_day["wday"]]["reserved_day"] = "half_tf";										
							$half_period_count++;
						}elseif ( $this_day_tmstmp_start <= $period["start_tmstmp"] && $this_day_tmstmp_end >= $period["start_tmstmp"] ){
							$week[$this_day["wday"]]["reserved_day"] = "half_ft";										
							$half_period_count++;
						}
					}
					if ($half_period_count > 1){
						$week[$this_day["wday"]]["reserved_day"] = "half_tft";									
					}
				}								
			}
			$array["calendar"]["month"][$key][] = $week;					
		}		
		$array["calendar"]["year"][0] = $current_day["year"];	
		for ( $year_c = 0; $year_c <4; $year_c++ ) {
			$array["calendar"]["year"][$year_c] = $current_day["year"]+$year_c;					
		}	
		return $array;
	}
	/**
	 * Get periods, that was reserved yet
	 *
	 * @param int $id_ad
	 * @param int $user_id
	 * @param int $id_event
	 * @return array
	 */
	function GetReserveDays($id_ad, $user_id, $id_event = "") {		
		$strSQL = "SELECT id, start_date, start_time, end_date, end_time FROM ".CALENDAR_EVENTS_TABLE." WHERE id_ad='$id_ad' AND user_id='$user_id' AND TIMESTAMP(concat(end_date,' ', end_time)) > NOW() ";						
		if (isset($id_event) && ($id_event != "")){
			$strSQL .= "AND id ='".intval($id_event)."'";
		}				
		$strSQL .= " ORDER BY TIMESTAMP(concat(start_date,' ', start_time)) ASC";				
		$record_set = $this->_dbconn->Execute( $strSQL );
		$reserve_days = array();
		$i =0 ;
		while ( !$record_set->EOF ) {			
			$row = $record_set->GetRowAssoc( false );
			$start_date = explode("-",$row["start_date"]);
			$start_time = explode(":",$row["start_time"]);
			$end_date = explode("-",$row["end_date"]);
			$end_time = explode(":",$row["end_time"]);
			$reserve_days[$i]["start_tmstmp"] = adodb_mktime($start_time[0], $start_time[1], 0, $start_date[1], $start_date[2], $start_date[0]);			
			$reserve_days[$i]["end_tmstmp"] = adodb_mktime($end_time[0], $end_time[1], 0, $end_date[1], $end_date[2], $end_date[0]);
			$reserve_days[$i]["id"] = $row["id"];
			$i++;
			$record_set->MoveNext();
		}		
		return $reserve_days;
	}

	/**
	 * Check adding period
	 *
	 * @param array $request_days
	 * @param array $reserve_days
	 * @return int
	 */
	function CheckReservePeriod($request_days, $reserve_days) {		
		foreach ($reserve_days AS $period){			
			if (($request_days["start_tmstmp"] <= $period["end_tmstmp"] ) && ($request_days["end_tmstmp"] >= $period["start_tmstmp"])){
				return 0;
			}
		}
		return 1;
	}
	/**
	 * Insert into database
	 *
	 * @param int $id_ad
	 * @param int $user_id
	 * @param array $request_days
	 */
	
	
	function InsertPeriod($id_ad, $user_id, $request_days, $id_event){				
		$start_date = date("Y-m-d", $request_days["start_tmstmp"]);
		$start_time = date("H:i:s", $request_days["start_tmstmp"]);
		$end_date = date("Y-m-d", $request_days["end_tmstmp"]);
		$end_time = date("H:i:s", $request_days["end_tmstmp"]);
				
		if ($id_event != 0){
			$strSQL = "INSERT INTO ".CALENDAR_EVENTS_TABLE." (id, id_ad, user_id, start_date, start_time, end_date, end_time) 
					VALUES ('$id_event', '$id_ad', '$user_id', '$start_date', '$start_time', '$end_date', '$end_time')";
		}
		else {
			$strSQL = "INSERT INTO ".CALENDAR_EVENTS_TABLE." (id_ad, user_id, start_date, start_time, end_date, end_time) 
					VALUES ('$id_ad', '$user_id', '$start_date', '$start_time', '$end_date', '$end_time')";
		}
		
		$this->_dbconn->Execute($strSQL);
				
	}
	
	function AddTimePeriod($id_ad, $user_id, $start_tmstmp, $end_tmstmp, $id_event = "") {
				
		$request_days["start_tmstmp"] = $start_tmstmp;		
		$request_days["end_tmstmp"] = $end_tmstmp;		
		$current_day = mktime();		
				
		$available_date = $this->GetMovedate($id_ad);

		if ($request_days["start_tmstmp"] < $current_day){						
			return "err_not_valid_period2";
		}	
				
	
		if ($request_days["end_tmstmp"] <= $request_days["start_tmstmp"]){
			return "err_not_valid_period";
		}
		if ($request_days["start_tmstmp"] <= $available_date){
			return "err_not_available_period";
		}
		
		
		$reserve_days = $this->GetReserveDays($id_ad, $user_id);
		
		if (!$this->CheckReservePeriod($request_days, $reserve_days)){
			
			return "err_reserved_yet";
		}
		
		$this->InsertPeriod($id_ad, $user_id, $request_days, $id_event);
		return "err_period_added";
	}
	
	/**
	 * Delete reserved period from database
	 *
	 * @param int $id_event
	 * @param int $id_ad
	 */
	
	function DeleteEvent($id_event, $id_ad){
		$strSQL = "DELETE FROM ".CALENDAR_EVENTS_TABLE." WHERE id='$id_event' AND id_ad='$id_ad'";		
		$this->_dbconn->Execute($strSQL);
	}
	
	/**
	 * Get available date
	 *
	 * @param int $id_ad
	 * @return int
	 */
	function GetMovedate($id_ad){
		$strSQL = "SELECT UNIX_TIMESTAMP(movedate) AS available_date FROM ".RENT_ADS_TABLE." WHERE id='$id_ad'";		
		$rs = $this->_dbconn->Execute($strSQL);			
		return $rs->fields[0];
	}
	
	/**
	 * Get first empty available period
	 *
	 * @param int $id_ad
	 * @param int $id_user
	 */
	function GetEmptyPeriod($id_ad, $id_user){
		$array = array();
		$array["is_reserved"] = 0;		
		$array["reserved_start_period"] = "";
		$array["reserved_end_period"] = "";		
		
		return $array;
		
		$now_day = mktime();
		$reserved_days = $this->GetReserveDays($id_ad, $id_user);				
		$available_date  = $this->GetMovedate($id_ad);
		$flag = 0;
		foreach ($reserved_days AS $period){				
			if ($available_date > $now_day){
				if ($period["start_tmstmp"] > $available_date){
					$array["reserved_start_period"] = date("m.d.Y H:i", $available_date);					
					$array["reserved_end_period"] = date("m.d.Y H:i", $period["start_tmstmp"]);						
					$array["is_reserved"] = 1;
					break;					
				}elseif($period["start_tmstmp"] <= $available_date && $period["end_tmstmp"] > $available_date){					
						$array["reserved_start_period"] = date("m.d.Y H:i", $period["end_tmstmp"]);	
						$array["is_reserved"] = 1;									
				}
			}elseif ($available_date <= $now_day){				
				if ($period["start_tmstmp"] > $now_day){					
					$array["reserved_start_period"] = date("m.d.Y H:i", $now_day);										
					$array["reserved_end_period"] = date("m.d.Y H:i", $period["start_tmstmp"]);						
					$array["is_reserved"] = 1;
					break;
				}elseif( $period["start_tmstmp"] <= $now_day && $period["end_tmstmp"] > $now_day ){
					$array["reserved_start_period"] = date("m.d.Y H:i", $period["end_tmstmp"]);										
					$array["reserved_end_period"] = "";						
					$array["is_reserved"] = 1;
					break;
				}
			}
			
		}
		return $array;		
	}
}

?>