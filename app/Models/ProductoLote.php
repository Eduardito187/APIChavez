<?php
namespace App\Models;
use App\Models\Lotes;
use App\Models\Producto;
use App\Models\Cuenta;
use App\Models\Sucursal;
use Illuminate\Database\Eloquent\Model;
class ProductoLote extends Model{
    protected $table="producto_lote";
    public $timestamps=false;
    protected $fillable = ['ID','Lote','Producto','FechaSalida','Responsable','Sucursal','Cantidad'];
    public function lote() {
        return $this->hasOne(Lotes::class,'ID','Lote');
    }
    public function producto() {
        return $this->hasOne(Producto::class,'ID','Producto');
    }
    public function productos() {
        return $this->hasMany(Producto::class,'ID','Producto');
    }
    public function cuenta() {
        return $this->hasOne(Cuenta::class,'ID','Responsable');
    }
    public function sucursal() {
        return $this->hasOne(Sucursal::class,'ID','Sucursal');
    }
}
?>