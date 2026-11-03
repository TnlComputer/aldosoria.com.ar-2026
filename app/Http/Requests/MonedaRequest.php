<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MonedaRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    return [
      'moneda' => ['required', 'max:3', 'string'],
      'simbolo' => ['required', 'max:100'],
      'name' => ['required', 'max:50'],
      'pais' => ['required', 'max:50'],
      'cambio' => ['required', 'integer']
    ];
  }
}
