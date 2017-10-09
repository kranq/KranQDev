<?php
/*
------------------------------------------------------------------------------------------------
Project       : KRQ
Created By    : Vijay Felix Raj C
Created Date  : 24.07.2017
Purpose       : To handle Common functions
------------------------------------------------------------------------------------------------
*/
namespace App\Helpers;

use App;
use Mail;
use Image;
use Route;
use Storage;
use GuzzleHttp\Client;

class KranHelper
{
    /**
     * To convert given base64 image to JPEG format
     * 
     * @param string $base64_string 
     * @return array
     */
    public static function base64_to_jpeg($base64_string)
    {
        $output_file = static::createTempFile('png');
        $data = explode(',', $base64_string);
        if (isset($data[1])) {
            $ifp = fopen($output_file, "wb");
            fwrite($ifp, base64_decode($data[1]));
            fclose($ifp);
        }
        return $output_file;
    }

    /**
     * To convert time to hours format
     * 
     * @param int $hour 
     * @return string $hour
     */
    public static function format_hours($hour) {
        if($hour) {
            $hour = rtrim($hour, "00");
            $hour = ltrim($hour, 0);

            if(substr($hour, -1) == ",") {
                $hour = substr_replace($hour ,"",-1);
            }
        }
        return $hour;
    }


    /**
     *  To format the date given
     * @param string $date
     * @param string $format
     * @return string $date
     */
    public static function formatDate($date,$format = 'd-m-Y') {
        if($date) {
            $date = new \DateTime($date);
            return $date->format($format);
        }else{
            $date = new \DateTime();
            return $date->format($format);
        }
    }

    /**
     * To set the string limit
     * 
     * @param array $row 
     * @return array
     */
    public static function reviewStringLimit($row) {
        return substr($row['reviews'],0,20). '...';
    }

    /**
     * To make list of provider status array
     * 
     * @param array $row 
     * @return array
     */
    public static function getProviderStatus($row) {
      $row_status = $row['status'] ? $row['status'] : '1';
      $status = ['1' => 'Under Review','2'=> 'Approved', '3' => 'Rejected'];
      return $status[$row_status];
    }

    /**
     * To convert Date time format 
     * 
     * @param string $date 
     * @return array
     */
    public static function dateTime($date = false) {
        return ($date) ? date('d/m/Y, h.i a', strtotime($date)) : date('d/m/Y h:i:s');
    }

    /**
     * To convert Date time format
     * 
     * @param array $date 
     * @return array
     */
    public static function dateTimeFormat($date = false) {
        return ($date['registered_on']) ? date('d/m/Y, h.i a', strtotime($date['registered_on'])) : date('d/m/Y, h.i a');
    }

    /**
     * To get all the the provider status for dropdown
     * 
     * @return array
     */
    public static function getProviderStatusDropdown() {
      $status = ['1' => 'Under Review','2'=> 'Approved', '3' => 'Rejected'];
      return $status;
    }

    /**
     * Returns the time in 12 Hours Format in dropdown
     * 
     * @param int $start 
     * @param int $end 
     * @return array
     */
    public static function getTimeDropDown($start=FALSE, $end=FALSE){
      $start_time = ($start) ? $start : '8';
      $end_time = ($end) ? $end : '21';
      for($hours=$start_time; $hours<$end_time; $hours++){
      //  for($mins=0; $mins<60; $mins+=30){
          //echo '<option>'.str_pad($hours,2,'0',STR_PAD_LEFT).'</option>';
          if($hours<12){
              $time = $hours.' AM';
          }else if($hours==12){
              $time = '12 PM';
          }else{
            $time = $hours - 12 .' PM';
          }

          $result[$hours] = $time;
        //}
      }
      return $result;
    }

    /**
     * Returns the weekdays
     * 
     * @return array
     */
    public static function getAllWeekDays(){
      $dayNames = [
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday',
        ];
        return $dayNames;
    }

    /**
     * Returns the weekdays
     * 
     * @return array
     */
    public static function getWeekDays(){
      $dayNames = [
            '1'=>'Mon to Fri',
            '2'=>'Saturday',
            '3'=>'Sunday',
        ];
        return $dayNames;
    }

    /**
     * Convert string to lower
     * 
     * @param string $str 
     * @return string
     */
    public static function convertString($str){
      return str_replace(' ', '-', strtolower($str));
    }
	
	/**
	* To convert the base64 encrypted string to image
	*
	* @param string $imageString
	* @param string $imageName
        * @param string $path 
	* @return string 
	*/
	public static function convertStringToImage($imageString,$imageName,$path){
            $imageData = base64_decode($imageString);
            $photo = @imagecreatefromstring($imageData);
            $dateval = date('Ymdhis');
            $imageName = KranHelper::convertString($imageName);
            if ($photo) {
                $file = $imageName.  '-' . $dateval . '.jpg';
                $path = base_path() . $path; //file upload path
                //$data['profile_picture'] = $path . $imageName. '-' . $dateval . '.jpg';
                if (!is_dir($path)) {
                    @mkdir($path);
                }
                if (imagejpeg($photo, $path . $file, 100)) {
                    return $file;
                } else {
                    return '';
		}
            }
            
	}
	
	/**
	 * To get the active menu
         * 
         * @param array $urlList 
         * @return string 
	 */
	public static function getActiveMenu($urlList){
		$currentUrl = explode('/',Route::getFacadeRoot()->current()->uri());
		$urlList = $urlList;
		$activeMenu = (in_array($currentUrl[0],$urlList)) ? 'active' : '' ;
		return $activeMenu;
	}
	
	/**
	 * To get the active sub menu
         * 
         * @param array $urlList 
         * @return string
	 */
	public static function getActiveSubMenu($urlList){
		$currentUrl = Route::getFacadeRoot()->current()->uri();
		$activeMenu = ($currentUrl == $urlList) ? 'active' : '' ;
		return $activeMenu;
	}
	
	/**
	 * To get the active sub settings menu
         * 
         * @param array $urlList 
         * @return string
	 */
	public static function getActiveSubMenuDefault($urlList){
		$currentUrl = explode('/',Route::getFacadeRoot()->current()->uri());
		$activeMenu = ($currentUrl[0] == $urlList) ? 'active' : '' ;
		return $activeMenu;
	}
	
    /**
     * To Common Upload for Amazon s3
     * 
     * @param array $request
     * @param string $filename
     * @return boolean
     */
    public static function imageUploadS3($request, $filename)
    {
        //echo '<pre>';print_r($filename);exit;
        $t = Storage::disk('s3')->put($filename, file_get_contents($request), 'uploads');
        $imageName = Storage::disk('s3')->url($filename);
        return true;
    }

    /**
     * Handles to generate random chars
     *
     * @param int $length
     * @return string 
     */
    public static function generate_random_string($length = false) {
        $length = ($length) ? $length : 6;
        // Specifies the characters
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $result = '';
        for ($i = 0; $i < $length; $i++) {
            $result .= $characters[mt_rand(0, 61)];
        }
        return $result;
    }

    /**
     * Returns the time in 12 Hours Format to view
     * 
     * @param int $hours
     * @return string
     **/
        public static function getFormattedTime($hours){
      $time = '';
      if($hours){
          if($hours<12){
              $time = $hours.' AM';
          }else if($hours==12){
              $time = '12 PM';
          }else{
            $time = $hours - 12 .' PM';
          }

      }
      return $time;
    }

    /**
    * To upload service provider image 
    *
    * @param string $imageString
    * @param string $file
    * @param string $path
    * @return string 
    */
    public static function uploadSPImage($imageString,$file,$path){
        $imageData = base64_decode($imageString);
        $photo = imagecreatefromstring($imageData);
        if ($photo) {
            $path = base_path() . $path; //file upload path
            //$data['profile_picture'] = $path . $imageName. '-' . $dateval . '.jpg';
            if (!is_dir($path)) {
                @mkdir($path);
            }
            if (imagejpeg($photo, $path . $file, 100)) {
                return $file;
            } else {
                return '';
            }
        }
            
    }
	
}
