<?php

namespace App\Livewire;

use App\Models\Marcas_autos;
use App\Models\Marcas_images_autos;
use Livewire\Attributes\Url;
use Livewire\Component;

class MarcaModelo extends Component
{
  /** La marca y la ficha quedan en la URL: se pueden compartir y usar el boton "atras" */
  #[Url(as: 'marca', except: '')]
  public string $slcMarca = '';

  #[Url(as: 'ficha', except: 0)]
  public int $slcModelo = 0;

  #[Url(as: 'q', except: '')]
  public string $buscar = '';

  public function elegirMarca(string $marca): void
  {
    $this->slcMarca = $this->slcMarca === $marca ? '' : $marca;
    $this->slcModelo = 0;
    $this->buscar = '';
  }

  public function elegirModelo(int $id): void
  {
    $this->slcModelo = $id;
  }

  public function volver(): void
  {
    $this->slcModelo = 0;
  }

  public function updatedSlcMarca(): void
  {
    $this->slcModelo = 0;
  }

  public function render()
  {
    $marcas = Marcas_autos::marcasConFichas();

    $modelos = collect();
    if ($this->slcMarca !== '' || $this->buscar !== '') {
      $modelos = Marcas_autos::query()
        ->when($this->slcMarca !== '', fn ($q) => $q->where('marca', $this->slcMarca))
        ->when(trim($this->buscar) !== '', function ($q) {
          foreach (preg_split('/\s+/', trim($this->buscar)) as $palabra) {
            $like = '%' . addcslashes(mb_substr($palabra, 0, 40), '%_\\') . '%';
            $q->where(fn ($w) => $w->where('marca', 'like', $like)->orWhere('modelo', 'like', $like)
              ->orWhere('motor', 'like', $like)->orWhere('ecu', 'like', $like));
          }
        })
        ->orderBy('marca')
        ->orderBy('modelo')
        ->limit(120)
        ->get();
    }

    $vehiculo = $this->slcModelo ? Marcas_autos::find($this->slcModelo) : null;

    return view('livewire.marca-modelo', compact('marcas', 'modelos', 'vehiculo'));
  }
}
