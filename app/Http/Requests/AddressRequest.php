<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
        $rules['address'] = 'required';
        $rules['phone_no'] = 'required';
        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
                {
                    return [];
                }
                break;
                case 'POST':
                {
                   $rules['email'] = 'required|unique:contact_details,email,null,id,deleted_at,null';
                }
                case 'PUT':
                case 'PATCH':
                {
                  if(FormRequest::segment(2)){
                    $rules['email'] = 'required|unique:contact_details,email,' . FormRequest::segment(2) . ',id,deleted_at,null';
                }
            }
            break;
            default:break;
        }
        return $rules;
    }
}
