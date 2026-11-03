<?php

namespace App\Http\Requests;

use App\Support\Reglas;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class userUpdateRequest extends FormRequest
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
      'name' => Reglas::nombre(),
      'last_name' => Reglas::nombre(),
      'phone' => Reglas::telefono(),
      'roles' => ['required', 'in:ROLE_USER,ROLE_ADMIN'],
      'pais_id' => ['nullable', 'integer'],
      'whatsapp' => Reglas::siNo(),
      'tlgram' => Reglas::siNo(),
    ];
  }

  protected function prepareForValidation(): void
  {
    $this->merge([
      'name' => Reglas::limpiar($this->name),
      'last_name' => Reglas::limpiar($this->last_name),
      'phone' => Reglas::limpiar($this->phone),
    ]);
  }

  public function messages(): array
  {
    return [
      'name.regex' => 'El nombre solo puede tener letras.',
      'last_name.regex' => 'El apellido solo puede tener letras.',
      'phone.regex' => 'El teléfono solo puede tener números, espacios, guiones y el signo +.',
    ];
  }
}
