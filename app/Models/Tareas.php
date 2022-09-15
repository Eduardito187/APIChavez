<?php
namespace App\Models;
use App\Models\Cuenta;
use App\Models\Sucursal;
use App\Models\Area;
use App\Models\Prioridad;
use Illuminate\Database\Eloquent\Model;
class Tareas extends Model{
    protected $table="tareas";
    public $timestamps=false;
    protected $fillable = ['ID','Codigo','Sucursal','Detalle','Solicitante','Responsable','Estado','Prioridad','Pospuesta','FechaCreacion','FechaPospuesta','FechaInicio','FechaFinalizacion','Conclusion','Eliminado','FechaEliminado','area'];
    
    public function sucursal() {
        return $this->hasOne(Sucursal::class,'ID','Sucursal');
    }
    public function solicitante() {
        return $this->hasOne(Cuenta::class,'ID','Solicitante');
    }
    public function responsable() {
        return $this->hasOne(Cuenta::class,'ID','Responsable');
    }
    public function prioridad() {
        return $this->hasOne(Prioridad::class,'ID','Prioridad');
    }
    public function areas() {
        return $this->hasOne(Area::class,'ID','area');
    }
}
?>