<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules['service_name'] = 'required|min:3|max:50';
        $rules['status'] = 'required';
        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
                {
                    return [];
                }
                break;
                case 'POST':
                {
                   $rules['service_name'] = 'required|unique:services,service_name,null,id,deleted_at,null';
                }
                case 'PUT':
                case 'PATCH':
                {
                  if(FormRequest::scategory_nameegment(2)){
                    $rules['service_name'] = 'required|unique:services,service_name,' . FormRequest::segment(2) . ',id,deleted_at,null';
                }
            }
            break;
            default:break;
        }
        return $rules;
    }
}
