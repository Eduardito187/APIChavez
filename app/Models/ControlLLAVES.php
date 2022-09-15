<?php
namespace App\Models;
use App\Models\Cuenta;
use App\Models\Sucursal;
use Illuminate\Database\Eloquent\Model;
class ControlLLAVES extends Model{
    protected $table="control_llavez";
    public $timestamps=false;
    protected $fillable = ['ID','FechaInicio','FechaDevolucion','Sucursal','Entrega','Observacion','Responsable'];
    public function responsable() {
        return $this->hasOne(Cuenta::class,'ID','Responsable');
    }
    public function sucursal() {
        return $this->hasOne(Sucursal::class,'ID','Sucursal');
    }
}
?>