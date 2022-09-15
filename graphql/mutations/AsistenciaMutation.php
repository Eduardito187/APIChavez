<?php
use App\Models\VacacionPersona;
use App\Models\VacacionesDias;
use App\Models\Trabajadores;
use App\Models\Control;
use App\Models\TablaControl;
use GraphQL\Type\Definition\Type;
$AsistenciaMutation=[
    'AddVacacionDias'=>[
        'type'=>$boolType,
        'args'=>[
            'Dias'=>Type::nonNull(Type::int())
        ],
        'resolve'=>function($root, $args){
            $VacacionesDias=new VacacionesDias([
                'ID'=>NULL,
                'Dias'=>$args["Dias"]
            ]);
            $x=$VacacionesDias->save();
            return array("Respuesta"=>true);
        }
    ],
    'AddTrabajador' => [
        'type' => $boolType,
        'args' => [
            'Nombre'=>Type::nonNull(Type::string()),
            'Apellido'=>Type::nonNull(Type::string()),
            'CI'=>Type::nonNull(Type::string()),
            'Puesto'=>Type::nonNull(Type::string()),
            'FechaContratacion'=>Type::nonNull(Type::string()),
            'Supervisor'=>Type::nonNull(Type::int())
        ],
        'resolve' => function($root, $args) {
            $sup=NULL;
            if ($args["Supervisor"]!=0) {
                $sup=$args["Supervisor"];
            }
            $Trabajadores=new Trabajadores([
                'ID'=>NULL,
                'Nombre'=>$args["Nombre"],
                'Apellido'=>$args["Apellido"],
                'CI'=>$args["CI"]!=''?$args["CI"]:NULL,
                'Puesto'=>$args["Puesto"],
                'FechaContratacion'=>$args["FechaContratacion"]!=''?$args["FechaContratacion"]:NULL,
                'Supervisor'=>$sup
            ]);
            $x=$Trabajadores->save();
            return array("Respuesta"=>true);
        }
    ],
    'EditTrabajador' => [
        'type' => $boolType,
        'args' => [
            'ID'=>Type::nonNull(Type::int()),
            'Nombre'=>Type::nonNull(Type::string()),
            'Apellido'=>Type::nonNull(Type::string()),
            'CI'=>Type::nonNull(Type::string()),
            'Puesto'=>Type::nonNull(Type::string()),
            'FechaContratacion'=>Type::nonNull(Type::string()),
            'Supervisor'=>Type::nonNull(Type::int())
        ],
        'resolve' => function($root, $args) {
            $sup=NULL;
            $Trabajador = Trabajadores::find($args["ID"]);
            if ($Trabajador==null) {
                return array("Respuesta"=>false);
            }
            if ($args["Supervisor"]!=0) {
                $sup=$args["Supervisor"];
            }else{
                $sup=NULL;
            }
            Trabajadores::where('ID',$args["ID"])->update([
                'Nombre'=>isset($args["Nombre"])?$args["Nombre"]:$Trabajador->Nombre,
                'Apellido'=>isset($args["Apellido"])?$args["Apellido"]:$Trabajador->Apellido,
                'CI'=>isset($args["CI"])?$args["CI"]:$Trabajador->CI,
                'Puesto'=>isset($args["Puesto"])?$args["Puesto"]:$Trabajador->Puesto,
                'FechaContratacion'=>isset($args["FechaContratacion"])?$args["FechaContratacion"]:$Trabajador->FechaContratacion,
                'Supervisor'=>$sup
            ]);
            return array("Respuesta"=>true);
        }
    ],
    'AddVacacionPersona' => [
        'type' => $boolType,
        'args' => [
            'Vacacion' => Type::nonNull(Type::int()),
            'Trabajador' => Type::nonNull(Type::int()),
            'Anho' => Type::nonNull(Type::int())
        ],
        'resolve' => function($root, $args) {
            $VacacionPersona=new VacacionPersona([
                'ID'=>NULL,
                'Vacacion'=>$args["Vacacion"],
                'Trabajador'=>$args["Trabajador"],
                'Anho'=>$args["Anho"]
            ]);
            $x=$VacacionPersona->save();
            return array("Respuesta"=>true);
        }
    ],
    'AddControlAsistencia' => [
        'type' => $boolType,
        'args' => [
            'Trabajador' => Type::nonNull(Type::int()),
            'Tipo' => Type::nonNull(Type::string()),
            'Motivo' => Type::nonNull(Type::string())
        ],
        'resolve' => function($root, $args) {
            $m="";
            if ($args["Tipo"] == "Permiso" || $args["Tipo"] == "Falta") {
                $m=$args["Motivo"];
            }else{
                $m=NULL;
            }
            $Control=new Control([
                'ID'=>NULL,
                'Trabajador'=>$args["Trabajador"],
                'Fecha'=>date("Y-m-d"),
                'Tipo'=>$args["Tipo"],
                'Motivo'=>$m
            ]);
            $x=$Control->save();

            $TablaControl = TablaControl::where('Anho', date("Y"))->where('Trabajador',$args["Trabajador"])->get()->toArray();
            if (count($TablaControl)==0) {
                $n_TablaControl=new TablaControl([
                    'ID'=>NULL,
                    'Trabajador'=>$args["Trabajador"],
                    'Anho'=>date("Y"),
                    'Libre'=>($args["Tipo"] == "Libre")?1:0,
                    'BajaMedica'=>($args["Tipo"] == "Baja Medica")?1:0,
                    'Permisos'=>($args["Tipo"] == "Permiso")?1:0,
                    'Faltas'=>($args["Tipo"] == "Falta")?1:0
                ]);
                $x=$n_TablaControl->save();
            }else{
                $info=TablaControl::find($TablaControl[0]["ID"]);
                TablaControl::where('Anho', date("Y"))->where('Trabajador',$args["Trabajador"])->update([
                    'Libre'=>($args["Tipo"] == "Libre")?$info->Libre+1:$info->Libre,
                    'BajaMedica'=>($args["Tipo"] == "Baja Medica")?$info->BajaMedica+1:$info->BajaMedica,
                    'Permisos'=>($args["Tipo"] == "Permiso")?$info->Permisos+1:$info->Permisos,
                    'Faltas'=>($args["Tipo"] == "Falta")?$info->Faltas+1:$info->Faltas
                ]);
            }
            return array("Respuesta"=>true);
        }
    ]
]
?>