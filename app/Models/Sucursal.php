<?php
namespace App\Models;
use App\Models\Extintor;
use App\Models\Seguimiento;
use App\Models\ControlLLAVES;
use App\Models\EmpresaGuardiaSucursal;
use App\Models\ControlDiario;
use App\Models\Tareas;
use App\Models\ProductoLote;
use Illuminate\Database\Eloquent\Model;
class Sucursal extends Model{
    protected $table="sucursal";
    public $timestamps=false;
    protected $fillable = ['ID','Nombre','CodigoSucursal','Telefono','Direccion','TelfInterno','Correo','Region'];
    public function extintor() {
        return $this->hasMany(Extintor::class,'Sucursal','ID');
    }
    public function seguimiento() {
        return $this->hasMany(Seguimiento::class,'Sucursal','ID');
    }
    public function guardias() {
        return $this->hasMany(EmpresaGuardiaSucursal::class,'Sucursal','ID');
    }
    public function control_llaves() {
        return $this->hasMany(ControlLLAVES::class,'Sucursal','ID');
    }
    public function control_diario() {
        return $this->hasMany(ControlDiario::class,'Sucursal','ID');
    }
    public function tareas() {
        return $this->hasMany(Tareas::class,'Sucursal','ID');
    }
    public function producto_lote() {
        return $this->hasMany(ProductoLote::class,'Sucursal','ID');
    }

}
?>