<?php
namespace App\Models;
use App\Models\Extintor;
use Illuminate\Database\Eloquent\Model;
class ExtintorDato extends Model{
    protected $table="extintor";
    public $timestamps=false;
    protected $fillable = ['ID','Codigo','PH','Peso','Recargo','Extintores','Observacion'];

    public function extintor() {
        return $this->hasOne(Extintor::class,'ID','Extintores');
    }
}
?>