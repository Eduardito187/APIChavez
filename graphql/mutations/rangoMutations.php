<?php
use App\Models\Rango;
use App\Models\Permisos;
use GraphQL\Type\Definition\Type;
$rangoMutations=[
    'addRango'=>[
        'type'=>$boolType,
        'args'=>[
            'Nombre'=>Type::nonNull(Type::string())
        ],
        'resolve'=>function($root, $args){
            $total=Rango::distinct()->count('ID');
            $rango=new Rango([
                'ID'=>$total+1,
                'Nombre'=>$args["Nombre"]
            ]);
            $x=$rango->save();
            return array("Respuesta"=>true);
        }
    ],
    'editRango' => [
        'type' => $boolType,
        'args' => [
            'ID'=>Type::nonNull(Type::int()),
            'Nombre'=>Type::nonNull(Type::string())
        ],
        'resolve' => function($root, $args) {
            $rango=Rango::find($args['ID']);
            $v=false;
            if ($rango!=null) {
                Rango::where('ID', $args['ID'])->update(['Nombre' => $args["Nombre"]]);
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
    'delRango' => [
        'type' => $boolType,
        'args' => [
            'ID' => Type::nonNull(Type::int())
        ],
        'resolve' => function($root, $args) {
            $rango = Rango::find($args['ID']);
            $v=false;
            if ($rango!=null && true==false) {
                Rango::where('ID', $args['ID'])->delete();
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
    'PermisosUpdate' => [
        'type' => $resType,
        'args' => [
            'ID' => Type::nonNull(Type::int()),
            'P1' => Type::int(),
            'P2' => Type::int(),
            'P3' => Type::int(),
            'P4' => Type::int(),
            'P5' => Type::int(),
            'P6' => Type::int(),
            'P7' => Type::int(),
            'P8' => Type::int(),
            'P9' => Type::int(),
            'P10' => Type::int(),
            'P11' => Type::int(),
            'P12' => Type::int(),
            'P13' => Type::int(),
            'P14' => Type::int(),
            'P15' => Type::int(),
            'P16' => Type::int(),
            'P17' => Type::int(),
            'P18' => Type::int(),
            'P19' => Type::int(),
            'P20' => Type::int(),
            'P21' => Type::int(),
            'P22' => Type::int(),
            'P23' => Type::int(),
            'P24' => Type::int(),
            'P25' => Type::int(),
            'P26' => Type::int(),
            'P27' => Type::int(),
            'P28' => Type::int(),
            'P29' => Type::int(),
            'P30' => Type::int(),
            'P31' => Type::int(),
            'P32' => Type::int(),
            'P33' => Type::int(),
            'P34' => Type::int(),
            'P35' => Type::int(),
            'P36' => Type::int(),
            'P37' => Type::int(),
            'P38' => Type::int(),
            'P39' => Type::int(),
            'P40' => Type::int(),
            'P41' => Type::int(),
            'P42' => Type::int(),
            'P43' => Type::int(),
            'P44' => Type::int(),
            'P45' => Type::int(),
            'P46' => Type::int(),
            'P47' => Type::int(),
            'P48' => Type::int(),
            'P49' => Type::int(),
            'P50' => Type::int(),
            'P51' => Type::int(),
            'P52' => Type::int(),
            'P53' => Type::int(),
            'P54' => Type::int(),
            'P55' => Type::int(),
            'P56' => Type::int(),
            'P57' => Type::int(),
            'P58' => Type::int(),
            'P59' => Type::int(),
            'P60' => Type::int(),
            'P61' => Type::int(),
        ],
        'resolve' => function($root, $args) {
            $rango = Rango::where('ID', $args["ID"])->with(['permisos'])->first();
            $r="";
            if ($rango->permisos==null) {
                $total=Permisos::distinct()->count('ID');
                $permisos=new Permisos([
                    'ID'=>$total+1,
                    'RangoID'=>$args["ID"],
                    'P1' => isset($args["P1"])?$args["P1"]:0,
                    'P2' => isset($args["P2"])?$args["P2"]:0,
                    'P3' => isset($args["P3"])?$args["P3"]:0,
                    'P4' => isset($args["P4"])?$args["P4"]:0,
                    'P5' => isset($args["P5"])?$args["P5"]:0,
                    'P6' => isset($args["P6"])?$args["P6"]:0,
                    'P7' => isset($args["P7"])?$args["P7"]:0,
                    'P8' => isset($args["P8"])?$args["P8"]:0,
                    'P9' => isset($args["P9"])?$args["P9"]:0,
                    'P10' => isset($args["P10"])?$args["P10"]:0,
                    'P11' => isset($args["P11"])?$args["P11"]:0,
                    'P12' => isset($args["P12"])?$args["P12"]:0,
                    'P13' => isset($args["P13"])?$args["P13"]:0,
                    'P14' => isset($args["P14"])?$args["P14"]:0,
                    'P15' => isset($args["P15"])?$args["P15"]:0,
                    'P16' => isset($args["P16"])?$args["P16"]:0,
                    'P17' => isset($args["P17"])?$args["P17"]:0,
                    'P18' => isset($args["P18"])?$args["P18"]:0,
                    'P19' => isset($args["P19"])?$args["P19"]:0,
                    'P20' => isset($args["P20"])?$args["P20"]:0,
                    'P21' => isset($args["P21"])?$args["P21"]:0,
                    'P22' => isset($args["P22"])?$args["P22"]:0,
                    'P23' => isset($args["P23"])?$args["P23"]:0,
                    'P24' => isset($args["P24"])?$args["P24"]:0,
                    'P25' => isset($args["P25"])?$args["P25"]:0,
                    'P26' => isset($args["P26"])?$args["P26"]:0,
                    'P27' => isset($args["P27"])?$args["P27"]:0,
                    'P28' => isset($args["P28"])?$args["P28"]:0,
                    'P29' => isset($args["P29"])?$args["P29"]:0,
                    'P30' => isset($args["P30"])?$args["P30"]:0,
                    'P31' => isset($args["P31"])?$args["P31"]:0,
                    'P32' => isset($args["P32"])?$args["P32"]:0,
                    'P33' => isset($args["P33"])?$args["P33"]:0,
                    'P34' => isset($args["P34"])?$args["P34"]:0,
                    'P35' => isset($args["P35"])?$args["P35"]:0,
                    'P36' => isset($args["P36"])?$args["P36"]:0,
                    'P37' => isset($args["P37"])?$args["P37"]:0,
                    'P38' => isset($args["P38"])?$args["P38"]:0,
                    'P39' => isset($args["P39"])?$args["P39"]:0,
                    'P40' => isset($args["P40"])?$args["P40"]:0,
                    'P41' => isset($args["P41"])?$args["P41"]:0,
                    'P42' => isset($args["P42"])?$args["P42"]:0,
                    'P43' => isset($args["P43"])?$args["P43"]:0,
                    'P44' => isset($args["P44"])?$args["P44"]:0,
                    'P45' => isset($args["P45"])?$args["P45"]:0,
                    'P46' => isset($args["P46"])?$args["P46"]:0,
                    'P47' => isset($args["P47"])?$args["P47"]:0,
                    'P48' => isset($args["P48"])?$args["P48"]:0,
                    'P49' => isset($args["P49"])?$args["P49"]:0,
                    'P50' => isset($args["P50"])?$args["P50"]:0,
                    'P51' => isset($args["P51"])?$args["P51"]:0,
                    'P52' => isset($args["P52"])?$args["P52"]:0,
                    'P53' => isset($args["P53"])?$args["P53"]:0,
                    'P54' => isset($args["P54"])?$args["P54"]:0,
                    'P55' => isset($args["P55"])?$args["P55"]:0,
                    'P56' => isset($args["P56"])?$args["P56"]:0,
                    'P57' => isset($args["P57"])?$args["P57"]:0,
                    'P58' => isset($args["P58"])?$args["P58"]:0,
                    'P59' => isset($args["P59"])?$args["P59"]:0,
                    'P60' => isset($args["P60"])?$args["P60"]:0,
                    'P61' => isset($args["P61"])?$args["P61"]:0,
                ]);
                $x=$permisos->save();
                $r="Permisos Agregados.";
            }else{
                Permisos::where('RangoID', $args['ID'])->update([
                    'P1' => isset($args["P1"])?$args["P1"]:$rango->permisos->P1,
                    'P2' => isset($args["P2"])?$args["P2"]:$rango->permisos->P2,
                    'P3' => isset($args["P3"])?$args["P3"]:$rango->permisos->P3,
                    'P4' => isset($args["P4"])?$args["P4"]:$rango->permisos->P4,
                    'P5' => isset($args["P5"])?$args["P5"]:$rango->permisos->P5,
                    'P6' => isset($args["P6"])?$args["P6"]:$rango->permisos->P6,
                    'P7' => isset($args["P7"])?$args["P7"]:$rango->permisos->P7,
                    'P8' => isset($args["P8"])?$args["P8"]:$rango->permisos->P8,
                    'P9' => isset($args["P9"])?$args["P9"]:$rango->permisos->P9,
                    'P10' => isset($args["P10"])?$args["P10"]:$rango->permisos->P10,
                    'P11' => isset($args["P11"])?$args["P11"]:$rango->permisos->P11,
                    'P12' => isset($args["P12"])?$args["P12"]:$rango->permisos->P12,
                    'P13' => isset($args["P13"])?$args["P13"]:$rango->permisos->P13,
                    'P14' => isset($args["P14"])?$args["P14"]:$rango->permisos->P14,
                    'P15' => isset($args["P15"])?$args["P15"]:$rango->permisos->P15,
                    'P16' => isset($args["P16"])?$args["P16"]:$rango->permisos->P16,
                    'P17' => isset($args["P17"])?$args["P17"]:$rango->permisos->P17,
                    'P18' => isset($args["P18"])?$args["P18"]:$rango->permisos->P18,
                    'P19' => isset($args["P19"])?$args["P19"]:$rango->permisos->P19,
                    'P20' => isset($args["P20"])?$args["P20"]:$rango->permisos->P20,
                    'P21' => isset($args["P21"])?$args["P21"]:$rango->permisos->P21,
                    'P22' => isset($args["P22"])?$args["P22"]:$rango->permisos->P22,
                    'P23' => isset($args["P23"])?$args["P23"]:$rango->permisos->P23,
                    'P24' => isset($args["P24"])?$args["P24"]:$rango->permisos->P24,
                    'P25' => isset($args["P25"])?$args["P25"]:$rango->permisos->P25,
                    'P26' => isset($args["P26"])?$args["P26"]:$rango->permisos->P26,
                    'P27' => isset($args["P27"])?$args["P27"]:$rango->permisos->P27,
                    'P28' => isset($args["P28"])?$args["P28"]:$rango->permisos->P28,
                    'P29' => isset($args["P29"])?$args["P29"]:$rango->permisos->P29,
                    'P30' => isset($args["P30"])?$args["P30"]:$rango->permisos->P30,
                    'P31' => isset($args["P31"])?$args["P31"]:$rango->permisos->P31,
                    'P32' => isset($args["P32"])?$args["P32"]:$rango->permisos->P32,
                    'P33' => isset($args["P33"])?$args["P33"]:$rango->permisos->P33,
                    'P34' => isset($args["P34"])?$args["P34"]:$rango->permisos->P34,
                    'P35' => isset($args["P35"])?$args["P35"]:$rango->permisos->P35,
                    'P36' => isset($args["P36"])?$args["P36"]:$rango->permisos->P36,
                    'P37' => isset($args["P37"])?$args["P37"]:$rango->permisos->P37,
                    'P38' => isset($args["P38"])?$args["P38"]:$rango->permisos->P38,
                    'P39' => isset($args["P39"])?$args["P39"]:$rango->permisos->P39,
                    'P40' => isset($args["P40"])?$args["P40"]:$rango->permisos->P40,
                    'P41' => isset($args["P41"])?$args["P41"]:$rango->permisos->P41,
                    'P42' => isset($args["P42"])?$args["P42"]:$rango->permisos->P42,
                    'P43' => isset($args["P43"])?$args["P43"]:$rango->permisos->P43,
                    'P44' => isset($args["P44"])?$args["P44"]:$rango->permisos->P44,
                    'P45' => isset($args["P45"])?$args["P45"]:$rango->permisos->P45,
                    'P46' => isset($args["P46"])?$args["P46"]:$rango->permisos->P46,
                    'P47' => isset($args["P47"])?$args["P47"]:$rango->permisos->P47,
                    'P48' => isset($args["P48"])?$args["P48"]:$rango->permisos->P48,
                    'P49' => isset($args["P49"])?$args["P49"]:$rango->permisos->P49,
                    'P50' => isset($args["P50"])?$args["P50"]:$rango->permisos->P50,
                    'P51' => isset($args["P51"])?$args["P51"]:$rango->permisos->P51,
                    'P52' => isset($args["P52"])?$args["P52"]:$rango->permisos->P52,
                    'P53' => isset($args["P53"])?$args["P53"]:$rango->permisos->P53,
                    'P54' => isset($args["P54"])?$args["P54"]:$rango->permisos->P54,
                    'P55' => isset($args["P55"])?$args["P55"]:$rango->permisos->P55,
                    'P56' => isset($args["P56"])?$args["P56"]:$rango->permisos->P56,
                    'P57' => isset($args["P57"])?$args["P57"]:$rango->permisos->P57,
                    'P58' => isset($args["P58"])?$args["P58"]:$rango->permisos->P58,
                    'P59' => isset($args["P59"])?$args["P59"]:$rango->permisos->P59,
                    'P60' => isset($args["P60"])?$args["P60"]:$rango->permisos->P60,
                    'P61' => isset($args["P61"])?$args["P61"]:$rango->permisos->P61
                ]);
                $r="Permisos Actualizados.";
            }
            return array("Respuesta"=>$r);
        }
    ],
]
?>