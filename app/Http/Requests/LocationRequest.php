<?php

/*
  ------------------------------------------------------------------------------------------------
  Project          	: KRQ 1.0.0
  Created By       	: Loganathan N
  Created Date    	: 20.07.2017
  Purpose           : To handle Location validation
  ------------------------------------------------------------------------------------------------
 */

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $rules['city_id'] = 'required';
        $rules['status'] = 'required';
        switch ($this->method()) {
            case 'GET':
            case 'DELETE': {
                    return [];
                }
                break;
            case 'POST': {                 
                    $rules['locality_name'] = 'required|min:3|max:100|unique:localities,locality_name';                    
                }
            case 'PUT':
            case 'PATCH': {
                    if (FormRequest::segment(2)) {
                        $rules['locality_name'] = 'required|unique:localities,locality_name,' . FormRequest::segment(2) . ',id';
                    }
                }
                break;
            default:break;
        }
        return $rules;
    }

}
