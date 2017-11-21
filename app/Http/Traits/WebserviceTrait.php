<?php
namespace App\Http\Traits;

trait WebserviceTrait {
	
	/**
	 * To get the registration mode 
	 *
	 * @param string $registerMode
	 * @return int
	 */
    public function getRegisterMode($registerMode) {
		$registerMode = ($registerMode) ? $registerMode : '';
        // Get all the register modes list
        $registerModeList = $this->registerModesList();
        return $registerModeList[$registerMode];
    }
	
	/**
	 * To return the list of register modes
	 *
	 * @return array 
	 */
	public function registerModesList(){
		$registerModes = array(
							''=>'0',
							'Mobile'=>'1',
							'Facebook'=>'2'
						);
		/*$registerModes = array(
							''=>'0',
							'Mobile'=>'1',
							'Facebook'=>'2'
						);*/
		return $registerModes;
	}
	
	/**
	 * To generate the random number generation for mobile OTP
	 *
	 * @return int
	 */
	public function generateOTP(){
		$mobileOTP	= substr(str_shuffle("0123456789"), 0, 4);
		return $mobileOTP;
	}
	
	/**
	 * To return the list of register modes
	 *
	 * @return array 
	 */
	public function workingDaysList(){
		$workingDays = array(
							0 => '',
							1 => 'Mon to Fri',
							2 => 'Saturday',
							3 => 'Sunday'
						);
		return $workingDays;
	}

	/**
	 * To return the list of Hours
	 *
	 * @return array
	 **/
	/**
     * Returns the time in 12 Hours Format in dropdown
     * @return array
     */
    public static function getTimeDropDown($start=FALSE, $end=FALSE){
      	$start_time = ($start) ? $start : '8';
      	$end_time = ($end) ? $end : '21';
      	$i=0;
      	for($hours=$start_time; $hours<$end_time; $hours++){
          	if ($hours<12) {
              	$time = $hours.' AM';
          	} else if($hours==12) {
            	$time = '12 PM';
          	} else {
            	$time = $hours - 12 .' PM';
          	}
          	$result[$i] = array('id' =>$hours,'name' => $time);	
          	$i++;
      	}
      	return $result;
    }
}