<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class Subidas
{
  /** Reglas de validacion para imagenes subidas desde la administracion. */
  public const REGLAS_IMAGEN = ['nullable', 'file', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:10240'];

  /**
   * Archivos de los cursos: comprimidos, documentos y volcados de ECU (bin, ori, hex, WinOLS).
   * Se guardan fuera de la carpeta publica y siempre se entregan como descarga: nunca se ejecutan.
   */
  public const EXTENSIONES_ARCHIVO_CURSO = 'rar,zip,7z,pdf,doc,docx,xls,xlsx,txt,bin,ori,hex,s19,ols,kp,mpc,frf,sgo';

  public const REGLAS_ARCHIVO_CURSO = ['nullable', 'file', 'extensions:' . self::EXTENSIONES_ARCHIVO_CURSO, 'max:204800'];

  private const EXTENSIONES_IMAGEN = ['jpg', 'jpeg', 'png', 'webp', 'gif'];

  /** Tope de la aplicacion para archivos de cursos */
  private const MAXIMO_APP_MB = 200;

  /**
   * Tamaño maximo que realmente se puede subir: el menor entre el tope de la app
   * y los limites de PHP del servidor (upload_max_filesize y post_max_size).
   */
  public static function maximoArchivoMb(): int
  {
    $limites = [self::MAXIMO_APP_MB];

    foreach (['upload_max_filesize', 'post_max_size'] as $clave) {
      $bytes = self::bytes((string) ini_get($clave));
      if ($bytes > 0) {
        $limites[] = (int) floor($bytes / 1048576);
      }
    }

    return max(1, min($limites));
  }

  private static function bytes(string $valor): int
  {
    $valor = trim($valor);
    $numero = (int) $valor;

    return match (strtolower(substr($valor, -1))) {
      'g' => $numero * 1073741824,
      'm' => $numero * 1048576,
      'k' => $numero * 1024,
      default => $numero,
    };
  }

  /**
   * Guarda una imagen en una carpeta publica con un nombre generado por el servidor.
   * La extension sale del contenido real del archivo, nunca del nombre enviado.
   */
  public static function imagen(UploadedFile $archivo, string $carpetaPublica, string $prefijo = ''): string
  {
    $extension = strtolower((string) $archivo->guessExtension());

    abort_unless(in_array($extension, self::EXTENSIONES_IMAGEN, true), 422, 'Formato de imagen no permitido');

    $nombre = date('YmdHis') . ($prefijo !== '' ? '-' . Str::limit(Str::slug($prefijo), 60, '') : '') . '-' . Str::lower(Str::random(6)) . '.' . $extension;
    $archivo->move(public_path(trim($carpetaPublica, '/')), $nombre);

    return $nombre;
  }

  /**
   * Guarda un archivo de curso en storage/app/cursos (no accesible desde la web).
   */
  public static function archivoCurso(UploadedFile $archivo, string $sufijo): string
  {
    $extension = strtolower($archivo->getClientOriginalExtension());
    $extension = preg_match('/^[a-z0-9]{1,5}$/', $extension) ? $extension : 'bin';

    $nombre = date('YmdHis') . '_' . $sufijo . '_' . Str::lower(Str::random(6)) . '.' . $extension;
    $archivo->storeAs('cursos', $nombre, 'local');

    return $nombre;
  }
}
