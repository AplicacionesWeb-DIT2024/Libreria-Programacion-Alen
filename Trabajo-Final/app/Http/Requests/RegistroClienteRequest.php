<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class RegistroClienteRequest extends FormRequest
{
    public const PROVINCIAS = [
        'Buenos Aires', 'Ciudad Autónoma de Buenos Aires', 'Catamarca', 'Chaco', 'Chubut',
        'Córdoba', 'Corrientes', 'Entre Ríos', 'Formosa', 'Jujuy', 'La Pampa', 'La Rioja',
        'Mendoza', 'Misiones', 'Neuquén', 'Río Negro', 'Salta', 'San Juan', 'San Luis',
        'Santa Cruz', 'Santa Fe', 'Santiago del Estero', 'Tierra del Fuego', 'Tucumán',
    ];

    private const REGEX_NOMBRE = "/^[\p{L}][\p{L}\s'’.-]*$/u";

    public function authorize(): bool
    {
        return true; 
    }

  
    protected function prepareForValidation(): void
    {
        $limpiar = fn ($valor) => is_string($valor) ? preg_replace('/\s+/u', ' ', trim($valor)) : $valor;

        $campos = [
            'nombre', 'apellido', 'username', 'telefono',
            'domicilio_calle', 'domicilio_numero', 'domicilio_localidad',
            'domicilio_provincia', 'domicilio_codigo_postal',
        ];

        $datos = [];
        foreach ($campos as $campo) {
            if ($this->has($campo)) {
                $datos[$campo] = $limpiar($this->input($campo));
            }
        }

        if ($this->has('email')) {
            $datos['email'] = mb_strtolower(trim((string) $this->input('email')));
        }

        if (($datos['telefono'] ?? null) === '') {
            $datos['telefono'] = null;
        }

        $this->merge($datos);
    }

    public function rules(): array
    {
        $fechaMaxima = now()->subYears(18)->toDateString(); 

        return [
            'nombre' => ['required', 'string', 'max:255', 'regex:' . self::REGEX_NOMBRE],
            'apellido' => ['required', 'string', 'max:255', 'regex:' . self::REGEX_NOMBRE],
            'username' => [
                'required', 'string', 'min:4', 'max:50',
                'regex:/^[A-Za-z0-9._-]+$/',
                function ($attribute, $value, $fail) {
                    if (User::whereRaw('LOWER(username) = ?', [mb_strtolower(trim($value))])->exists()) {
                        $fail('Ya existe un usuario con ese nombre de usuario.');
                    }
                },
            ],
            'email' => [
                'required', 'email:rfc', 'max:255', 'regex:/^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/',
                function ($attribute, $value, $fail) {
                    if (User::whereRaw('LOWER(email) = ?', [mb_strtolower(trim($value))])->exists()) {
                        $fail('Ya existe un usuario con ese email.');
                    }
                },
            ],
            'password' => [
                'required', 'string', 'max:72', 'confirmed',
                Password::min(8)->letters()->mixedCase()->numbers(),
            ],
            'fecha_nacimiento' => [
                'required', 'date_format:Y-m-d',
                'after_or_equal:1900-01-01',
                'before_or_equal:' . $fechaMaxima,
            ],
            'telefono' => ['nullable', 'string', 'max:20', 'regex:/^\+?[0-9\s-]{6,20}$/'],
            'domicilio_calle' => ['required', 'string', 'max:255'],
            'domicilio_numero' => ['required', 'string', 'max:20', 'regex:/^([0-9]+[A-Za-z]?|[Ss]\/[Nn])$/'],
            'domicilio_localidad' => ['required', 'string', 'max:255'],
            'domicilio_provincia' => ['required', 'string', Rule::in(self::PROVINCIAS)],
            'domicilio_codigo_postal' => ['required', 'regex:/^\d{4}$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'max' => 'El campo :attribute no puede superar los :max caracteres.',

            'nombre.regex' => 'El nombre solo puede contener letras, espacios, apóstrofes, puntos y guiones.',
            'apellido.regex' => 'El apellido solo puede contener letras, espacios, apóstrofes, puntos y guiones.',

            'username.min' => 'El nombre de usuario debe tener al menos 4 caracteres.',
            'username.max' => 'El nombre de usuario no puede superar los 50 caracteres.',
            'username.regex' => 'El nombre de usuario solo puede tener letras, números, puntos, guiones y guiones bajos (sin espacios).',

            'email.email' => 'Ingrese un email válido.',
            'email.regex' => 'Ingrese un email válido.',

            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.max' => 'La contraseña no puede superar los 72 caracteres.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.letters' => 'La contraseña debe incluir al menos una letra.',
            'password.mixed' => 'La contraseña debe incluir mayúsculas y minúsculas.',
            'password.numbers' => 'La contraseña debe incluir al menos un número.',

            'fecha_nacimiento.date_format' => 'Ingresá una fecha de nacimiento válida.',
            'fecha_nacimiento.before_or_equal' => 'Tenés que ser mayor de 18 años para registrarte.',
            'fecha_nacimiento.after_or_equal' => 'Ingresá una fecha de nacimiento válida.',

            'telefono.regex' => 'El teléfono solo puede tener números, espacios, guiones y un + al inicio (entre 6 y 20 caracteres).',
            'domicilio_numero.regex' => 'Ingrese un número de calle válido (por ejemplo 1234, 12B o S/N).',
            'domicilio_provincia.in' => 'Elegí una provincia de la lista.',
            'domicilio_codigo_postal.regex' => 'El código postal debe tener 4 números.',
        ];
    }

    public function attributes(): array
    {
        return [
            'nombre' => 'nombre',
            'apellido' => 'apellido',
            'username' => 'nombre de usuario',
            'email' => 'email',
            'password' => 'contraseña',
            'fecha_nacimiento' => 'fecha de nacimiento',
            'telefono' => 'teléfono',
            'domicilio_calle' => 'calle',
            'domicilio_numero' => 'número',
            'domicilio_localidad' => 'localidad',
            'domicilio_provincia' => 'provincia',
            'domicilio_codigo_postal' => 'código postal',
        ];
    }
}

