<?php
/*
------------------------------------------------------------------------------------------------
Project         : KRQ 1.0.0
Created By      : Vijay Felix Raj C
Created Date    : 19.07.2017
Purpose         : To handle Category validation
------------------------------------------------------------------------------------------------
*/
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
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
        $rules['order_by'] = 'required';
        $rules['status'] = 'required';
        $rules['service_id'] = 'required';
        $rules['category_image'] = "image|mimes:jpeg,png,jpg|max:2048";
        switch ($this->method()) {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            break;
            case 'POST':
            {
                $rules['category_name'] = 'required|unique:categories,category_name,null,id,deleted_at,null|min:3|max:200';
                $rules['status'] = 'required';
                $rules['order_by'] = 'required';
            }
            case 'PUT':
            case 'PATCH':
            {
                if(FormRequest::segment(2)){
                    $rules['category_name'] = 'required|unique:categories,category_name,' . FormRequest::segment(2) . ',id,deleted_at,null';
                }
            }
            break;
        default:break;
        }
        return $rules;
    }
}
