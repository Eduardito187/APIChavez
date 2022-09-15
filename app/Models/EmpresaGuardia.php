<?php
namespace App\Models;
use App\Models\EmpresaGuardiaSucursal;
use Illuminate\Database\Eloquent\Model;
use App\Models\ControlDiario;
class EmpresaGuardia extends Model{
    protected $table="empresa_guardias";
    public $timestamps=false;
    protected $fillable = ['ID','Nombre','Telefono','Direccion','Correo','Supervisores'];
    public function empresa_sucursal() {
        return $this->hasMany(EmpresaGuardiaSucursal::class,'Empresa','ID');
    }
    public function control_diario() {
        return $this->hasMany(ControlDiario::class,'Empresa','ID');
    }
}
?>