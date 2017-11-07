<?php

/*
  ------------------------------------------------------------------------------------------------
  Project		    : KRQ 1.0.0
  Created By    	: Joan Britto S
  Created Date  	: 26.07.2017
  Purpose       	: To handle service provider details
  ------------------------------------------------------------------------------------------------
 */

namespace App\Models;

use View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ServiceProvider extends Model
{
  protected $table = 'service_providers';
  public $timestamps = true;
  public $incrementing = false;

  use SoftDeletes;

  protected $fillable = array('id','category_id', 'short_description','owner_name', 'owner_designation', 'location_id', 'name_sp','slug','logo','city','address','status_owner_manager','opening_hrs','closing_hrs','working_days','phone','website_link','googlemap_latitude','googlemap_longitude','email','password', 'order_by', 'status', 'owner_phone', 'working_saturdays', 'working_sundays', 'saturday_opening_hrs', 'saturday_closing_hrs', 'sunday_opening_hrs', 'sunday_closing_hrs');

  /**
   * Get the category that owns the service provider.
   */
  public function category()
  {
      return $this->belongsTo('App\Models\Category','category_id');
  }

  /**
   * Get the category that owns the service provider.
   */
  public function cities()
  {
      return $this->belongsTo('App\Models\City','city');
  }

  /**
   * Get the category that owns the service provider.
   */
  public function locality()
  {
      return $this->belongsTo('App\Models\Location','location_id');
  }
  /**
   * To upload the file
   */
	public static function upload_file($request, $file, $id =  false)
	{
		$destinationPath = base_path() . trans('main.provider_path');
	
		if (!empty($id)) {
			$fileDetails = ServiceProvider::where('id', '=', $id)->get();
			$oldFileName = $fileDetails[0]->logo;
			if ($request->hasFile($file) && $request->file($file)->getSize() > 0) {
	
				if (!file_exists($destinationPath)) {
					mkdir($destinationPath, 0700);
				}
				// To unlink the old file
				if ($oldFileName) {
					@unlink($destinationPath.'/'.$oldFileName);
				}
				$filename   = $request->file($file)->getClientOriginalName();
				$request->file($file)->move($destinationPath, $filename);
			}
		} else {
			if ($request->hasFile($file) && $request->file($file)->getSize() > 0) {
				if (!file_exists($destinationPath)) {
					mkdir($destinationPath, 0700);
				}
				$filename   = $request->file($file)->getClientOriginalName();
				$request->file($file)->move($destinationPath, $filename);
			}
		}
		return $filename;
	}

    /**
    * To get service provider name by Id 
    *
    * @return array
    **/
    public static function getServiceNameById($id)
    {
        $serviceProviderDetails  = ServiceProvider::where('id', '=', $id)->first();
        return $serviceProviderDetails->name_sp;
    }

}
