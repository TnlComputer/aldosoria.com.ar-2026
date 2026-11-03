<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class CursoVideoUriRequest extends FormRequest
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
      'video_curso_id' => 'required',
      'nro_video_id' => 'required',
      'nombre_curso' => 'required',
      'nombre_video' => 'required',
      'descripcion' => ['required', 'string', 'max:200'],
      'uri_video' => ['nullable', 'string', 'max:200'],
      'imagen_video' => ['nullable', File::image()->max('10mb')],
      'tipo_curso' => 'required',
      'archivo1_curso' => 'nullable',
      'archivo2_curso' => 'nullable',
      // 'archivo1_curso' => ['nullable', File::types('mimes:rar, zip, docx, xlsx')->max('10mb')],
      // 'archivo2_curso' =>
      // ['nullable', File::types('mimes:rar, zip, docx, xlsx')->max('10mb')],
    ];
  }
}
