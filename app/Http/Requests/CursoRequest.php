<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class CursoRequest extends FormRequest
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
      'name' => 'required',
      'title' => ['required', 'max:100'],
      'videos_curso_cantidad' => ['required', 'max:3'],
      'image_curso' => ['nullable', File::image()->max('10mb')],
      'video_demo_Uri' => ['nullable', 'string', 'max:100'],
      'coast' => ['required',],
      'moneda_id' => 'required',
      'tipo_curso_id' => 'required'
    ];
  }
}

