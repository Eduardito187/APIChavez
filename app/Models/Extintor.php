<?php
namespace App\Models;
use App\Models\Sucursal;
use App\Models\Proveedor;
use App\Models\ExtintorDato;
use Illuminate\Database\Eloquent\Model;
class Extintor extends Model{
    protected $table="extintores";
    public $timestamps=false;
    protected $fillable = ['ID','Fecha','Tipo','Cantidad','Sucursal','Proveedor','Creacion'];

    public function sucursal() {
        return $this->hasOne(Sucursal::class,'ID','Sucursal');
    }
    public function proveedor() {
        return $this->hasOne(Proveedor::class,'ID','Proveedor');
    }
    public function extintores() {
        return $this->hasMany(ExtintorDato::class,'Extintores','ID');
    }
}
?>