<?php
namespace App\Models;
use App\Models\Proveedor;
use App\Models\ProductoLote;
use Illuminate\Database\Eloquent\Model;
class Producto extends Model{
    protected $table="producto";
    public $timestamps=false;
    protected $fillable = ['ID','Factura','Nombre','Modelo','Proveedor','Costo','Cantidad'];
    public function proveedor() {
        return $this->hasOne(Proveedor::class,'ID','Proveedor');
    }
    public function producto_lote() {
        return $this->hasMany(ProductoLote::class,'Producto','ID');
    }
}
?>