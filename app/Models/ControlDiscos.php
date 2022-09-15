<?php
namespace App\Models;
use App\Models\Sucursal;
use App\Models\SalidaDiscos;
use Illuminate\Database\Eloquent\Model;
class ControlDiscos extends Model{
    protected $table="control_discos";
    public $timestamps=false;
    protected $fillable = ['ID','Fecha','Sucursal','CantidadDiscos','ReqFiscal','FechaFinalizacion','Salida'];
    public function sucursal() {
        return $this->hasOne(Sucursal::class,'ID','Sucursal');
    }
    public function salida() {
        return $this->hasOne(SalidaDiscos::class,'ID','Salida');
    }
}
?>