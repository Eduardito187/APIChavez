<?php
namespace App\Models;
use App\Models\Extintor;
use App\Models\Producto;
use Illuminate\Database\Eloquent\Model;
class Proveedor extends Model{
    protected $table="proveedor";
    public $timestamps=false;
    protected $fillable = ['ID','Nombre','Telefono','Direccion','Correo'];
    
    public function extintor() {
        return $this->hasMany(Extintor::class,'Proveedor','ID');
    }
    public function producto() {
        return $this->hasMany(Producto::class,'Proveedor','ID');
    }
}
?>