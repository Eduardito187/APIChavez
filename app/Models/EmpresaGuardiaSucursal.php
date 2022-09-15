<?php
namespace App\Models;
use App\Models\EmpresaGuardia;
use App\Models\Sucursal;
use App\Models\Guardia;
use App\Models\GuardiaSucursal;
use App\Models\ControlDiario;
use Illuminate\Database\Eloquent\Model;
class EmpresaGuardiaSucursal extends Model{
    protected $table="empresa_guardias_sucursal";
    public $timestamps=false;
    protected $fillable = ['ID','Empresa','Sucursal','Ingreso','Salida'];
    public function empresa() {
        return $this->hasOne(EmpresaGuardia::class,'ID','Empresa');
    }
    public function sucursal() {
        return $this->hasOne(Sucursal::class,'ID','Sucursal');
    }
    public function guardia_sucursal() {
        return $this->hasMany(GuardiaSucursal::class,'GS','ID');
    }
    public function control_diario() {
        return $this->hasMany(ControlDiario::class,'GS','ID');
    }
}
?>