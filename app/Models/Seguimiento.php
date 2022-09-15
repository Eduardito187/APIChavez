<?php
namespace App\Models;
use App\Models\Prioridad;
use App\Models\Cuenta;
use App\Models\Sucursal;
use Illuminate\Database\Eloquent\Model;
class Seguimiento extends Model{
    protected $table="seguimiento";
    public $timestamps=false;
    protected $fillable = ['ID','Codigo','FechaCreacion','FechaInicio','FechaFin','Solicitante','Descripcion','Autorizacion','Sucursal','Responsable','Prioridad','Carpeta','Conclusion','Estado','Creador','Eliminado','FechaEliminado','Tipo','FechaAsignado'];
    
    public function prioridad() {
        return $this->hasOne(Prioridad::class,'ID','Prioridad');
    }
    public function responsable() {
        return $this->hasOne(Cuenta::class,'ID','Responsable');
    }
    public function sucursal() {
        return $this->hasOne(Sucursal::class,'ID','Sucursal');
    }
    public function creador(){
        return $this->hasOne(Cuenta::class,'ID','Creador');
    }
}
?>