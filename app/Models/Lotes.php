<?php
namespace App\Models;
use App\Models\ProductoLote;
use Illuminate\Database\Eloquent\Model;
class Lotes extends Model{
    protected $table="lotes";
    public $timestamps=false;
    protected $fillable = ['ID', 'Nombre'];

    public function producto_lote() {
        return $this->hasMany(ProductoLote::class,'Lote','ID');
    }
}
?>