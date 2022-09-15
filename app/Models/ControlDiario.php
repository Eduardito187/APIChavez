<?php
namespace App\Models;
use App\Models\Sucursal;
use App\Models\Guardia;
use App\Models\EmpresaGuardia;
use App\Models\EmpresaGuardiaSucursal;
use Illuminate\Database\Eloquent\Model;
class ControlDiario extends Model{
    protected $table="control_diario";
    public $timestamps=false;
    protected $fillable = ['ID','Sucursal','Empresa','Guardia','Calculo','Observacion','Fecha','GS'];
    public function sucursal() {
        return $this->hasOne(Sucursal::class,'ID','Sucursal');
    }
    public function guardia() {
        return $this->hasOne(Guardia::class,'ID','Guardia');
    }
    public function empresa_guardia() {
        return $this->hasOne(EmpresaGuardia::class,'ID','Empresa');
    }
    public function obtener_gs() {
        return $this->hasOne(EmpresaGuardiaSucursal::class,'ID','GS');
    }
}
?>