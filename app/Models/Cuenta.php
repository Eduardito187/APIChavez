<?php
namespace App\Models;
use App\Models\Rango;
use App\Models\Seguimiento;
use App\Models\ControlLLAVES;
use App\Models\Tareas;
use App\Models\ProductoLote;
use App\Models\Recepcion;
use App\Models\Mensajes;
use Illuminate\Database\Eloquent\Model;
class Cuenta extends Model{
    protected $table="cuenta";
    public $timestamps=false;
    protected $fillable = ['ID', 'usuario', 'contra','nombre','foto','RangoID','DIR','FechaCreado','FechaActualizado','FechaEliminado'];
    public function rango() {
        return $this->hasOne(Rango::class,'ID','RangoID');
    }
    public function seguimiento() {
        return $this->hasMany(Seguimiento::class,'Responsable','ID');
    }
    public function seguimiento_creado() {
        return $this->hasMany(Seguimiento::class,'Creador','ID');
    }
    public function control_llaves() {
        return $this->hasMany(ControlLLAVES::class,'Responsable','ID');
    }
    public function tareas_solicitante() {
        return $this->hasMany(Tareas::class,'Solicitante','ID');
    }
    public function tareas_responsable() {
        return $this->hasMany(Tareas::class,'Responsable','ID');
    }
    public function producto_lote() {
        return $this->hasMany(ProductoLote::class,'Responsable','ID');
    }
    public function recepcion() {
        return $this->hasMany(Recepcion::class,'Responsable','ID');
    }
    public function mensajes_de() {
        return $this->hasMany(Mensajes::class,'De','ID');
    }
    public function mensajes_para() {
        return $this->hasMany(Mensajes::class,'Para','ID');
    }
}
?>