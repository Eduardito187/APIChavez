<?php
use App\Models\Seguimiento;
use App\Models\Sucursal;
use GraphQL\Type\Definition\Type;
$seguimientoMutations=[
    'addSeguimiento'=>[
        'type'=>$boolType,
        'args'=>[
            'Codigo'=>Type::nonNull(Type::string()),
            'Descripcion'=>Type::nonNull(Type::string()),
            'Sucursal'=>Type::nonNull(Type::int()),
            'Prioridad'=>Type::nonNull(Type::int()),
            'Responsable'=>Type::int(),
            'Creador'=>Type::nonNull(Type::int()),
            'Solicitante'=>Type::nonNull(Type::string()),
            'Autorizador'=>Type::nonNull(Type::string())
        ],
        'resolve'=>function($root, $args){
            $cod=NULL;
            if ($args["Codigo"]!="" && $args["Codigo"]!=" ") {
                $cod=$args["Codigo"];
            }
            $Sucursal = Sucursal::find($args['Sucursal']);
            $FECHA=date("Y-m-d");
            $Seguimiento=new Seguimiento([
                'ID'=>NULL,
                'Codigo'=>$cod,
                'FechaCreacion'=>$FECHA,
                'FechaInicio'=>null,
                'FechaFin'=>null,
                'Solicitante'=>$args["Solicitante"],
                'Descripcion'=>$args["Descripcion"],
                'Autorizacion'=>$args["Autorizador"],
                'Sucursal'=>$args["Sucursal"],
                'Responsable'=>$args["Responsable"],
                'Prioridad'=>$args["Prioridad"],
                'Carpeta'=>NULL,
                'Conclusion'=>null,
                'Estado'=>"Pendiente",
                'Creador'=>$args["Creador"],
                'Eliminado'=>0,
                'FechaEliminado'=>NULL,
                'Tipo'=>"Seguimiento",
                'FechaAsignado'=>NULL
            ]);
            $x=$Seguimiento->save();
            $INFO = Seguimiento::where('Creador', $args["Creador"])->where('FechaCreacion',$FECHA)->where('Solicitante',$args["Solicitante"])->where('Descripcion',$args["Descripcion"])->get()->toArray();
            if ($cod==null) {
                $cod=$INFO[0]["ID"];
            }
            $ubicacion="\\\\192.168.0.11\APIChavez\graphql\data/".$Sucursal->Nombre."/".$cod;
            if(!mkdir('./graphql/data/'.$Sucursal->Nombre.'/'.$cod, 0777, true)) {
                return null;
            }
            Seguimiento::where('ID',$INFO[0]["ID"])->update([
                'Carpeta'=>$ubicacion
            ]);
            return array("Respuesta"=>true);
        }
    ],
    'addInstantaneo'=>[
        'type'=>$boolType,
        'args'=>[
            'Descripcion'=>Type::nonNull(Type::string()),
            'Sucursal'=>Type::nonNull(Type::int()),
            'Prioridad'=>Type::nonNull(Type::int()),
            'Creador'=>Type::nonNull(Type::int()),
            'Solicitante'=>Type::nonNull(Type::string()),
            'Autorizador'=>Type::nonNull(Type::string()),
            'Conclucion'=>Type::nonNull(Type::string())
        ],
        'resolve'=>function($root, $args){
            $cod=NULL;
            $Sucursal = Sucursal::find($args['Sucursal']);
            $FECHA=date("Y-m-d");
            $Seguimiento=new Seguimiento([
                'ID'=>NULL,
                'Codigo'=>Null,
                'FechaCreacion'=>$FECHA,
                'FechaInicio'=>null,
                'FechaFin'=>null,
                'Solicitante'=>$args["Solicitante"],
                'Descripcion'=>$args["Descripcion"],
                'Autorizacion'=>$args["Autorizador"],
                'Sucursal'=>$args["Sucursal"],
                'Responsable'=>$args["Creador"],
                'Prioridad'=>$args["Prioridad"],
                'Carpeta'=>NULL,
                'Conclusion'=>null,
                'Estado'=>"Pendiente",
                'Creador'=>$args["Creador"],
                'Eliminado'=>0,
                'FechaEliminado'=>NULL,
                'Tipo'=>"Instantaneo",
                'FechaAsignado'=>NULL
            ]);
            $x=$Seguimiento->save();
            $INFO = Seguimiento::where('Creador', $args["Creador"])->where('FechaCreacion',$FECHA)->where('Solicitante',$args["Solicitante"])->where('Descripcion',$args["Descripcion"])->get()->toArray();
            if ($cod==null) {
                $cod=$INFO[0]["ID"];
            }
            $ubicacion="\\\\192.168.0.11\APIChavez\graphql\data/".$Sucursal->Nombre."/".$cod;
            if(!mkdir('./graphql/data/'.$Sucursal->Nombre.'/'.$cod, 0777, true)) {
                return null;
            }
            Seguimiento::where('ID',$INFO[0]["ID"])->update([
                'Carpeta'=>$ubicacion
            ]);
            Seguimiento::where('ID', $INFO[0]['ID'])->update([
                'FechaInicio'=>date("Y-m-d"),
                'Estado'=>"Iniciado"
            ]);
            Seguimiento::where('ID', $INFO[0]['ID'])->update([
                'Conclusion'=>isset($args["Conclucion"])?$args["Conclucion"]:$INFO[0]['Conclusion'],
                'FechaFin'=>date("Y-m-d"),
                'Estado'=>"Finalizado"
            ]);
            return array("Respuesta"=>true);
        }
    ],
    'editSeguimiento' => [
        'type' => $boolType,
        'args' => [
            'ID'=>Type::nonNull(Type::int()),
            'Codigo'=>Type::string(),
            'Solicitante'=>Type::string(),
            'Descripcion'=>Type::string(),
            'Autorizacion'=>Type::string(),
            'Sucursal'=>Type::int(),
            'Responsable'=>Type::int(),
            'Prioridad'=>Type::int(),
            'Conclusion'=>Type::string()
        ],
        'resolve' => function($root, $args) {
            $Seguimiento=Seguimiento::find($args['ID']);
            $v=false;
            if ($Seguimiento!=null) {
                Seguimiento::where('ID', $args['ID'])->update([
                    'Codigo'=>isset($args["Codigo"])?$args["Codigo"]:$Seguimiento->Codigo,
                    'Solicitante'=>isset($args["Solicitante"])?$args["Solicitante"]:$Seguimiento->Solicitante,
                    'Descripcion'=>isset($args["Descripcion"])?$args["Descripcion"]:$Seguimiento->Descripcion,
                    'Autorizacion'=>isset($args["Autorizacion"])?$args["Autorizacion"]:$Seguimiento->Autorizacion,
                    'Sucursal'=>isset($args["Sucursal"])?$args["Sucursal"]:$Seguimiento->Sucursal,
                    'Responsable'=>isset($args["Responsable"])?$args["Responsable"]:$Seguimiento->Responsable,
                    'Prioridad'=>isset($args["Prioridad"])?$args["Prioridad"]:$Seguimiento->Prioridad,
                    'Conclusion'=>isset($args["Conclusion"])?$args["Conclusion"]:$Seguimiento->Conclusion
                ]);
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
    'delSeguimiento' => [
        'type' => $boolType,
        'args' => [
            'ID' => Type::nonNull(Type::int())
        ],
        'resolve' => function($root, $args) {
            $Seguimiento = Seguimiento::find($args['ID']);
            $v=false;
            if ($Seguimiento!=null) {
                //Seguimiento::where('ID', $args['ID'])->delete();
                Seguimiento::where('ID', $args['ID'])->update([
                    'Eliminado'=>1,
                    'FechaEliminado'=>date("Y-m-d")
                ]);
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
    'Seguimiento_Inicio' => [
        'type' => $boolType,
        'args' => [
            'ID'=>Type::nonNull(Type::int())
        ],
        'resolve' => function($root, $args) {
            $Seguimiento=Seguimiento::find($args['ID']);
            $v=false;
            if ($Seguimiento!=null) {
                Seguimiento::where('ID', $args['ID'])->update([
                    'FechaInicio'=>date("Y-m-d"),
                    'Estado'=>"Iniciado"
                ]);
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
    'Seguimiento_Fin' => [
        'type' => $boolType,
        'args' => [
            'ID'=>Type::nonNull(Type::int()),
            'Conclusion'=>Type::string()
        ],
        'resolve' => function($root, $args) {
            $Seguimiento=Seguimiento::find($args['ID']);
            $v=false;
            if ($Seguimiento!=null) {
                Seguimiento::where('ID', $args['ID'])->update([
                    'Conclusion'=>isset($args["Conclusion"])?$args["Conclusion"]:$Seguimiento->Conclusion,
                    'FechaFin'=>date("Y-m-d"),
                    'Estado'=>"Finalizado"
                ]);
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
    'Seguimiento_Codigo_Add'=>[
        'type' => $boolType,
        'args' => [
            'ID'=>Type::nonNull(Type::int()),
            'Codigo'=>Type::string()
        ],
        'resolve' => function($root, $args) {
            $Seguimiento=Seguimiento::find($args['ID']);
            $Sucursal = Sucursal::find($Seguimiento->Sucursal);
            rename('./graphql/data/'.$Sucursal->Nombre.'/'.$Seguimiento->ID,'./graphql/data/'.$Sucursal->Nombre.'/'.$args["Codigo"]);
            $v=false;
            $ubicacion="\\\\192.168.0.11\APIChavez\graphql\data/".$Sucursal->Nombre."/".$args["Codigo"];
            if ($Seguimiento!=null) {
                Seguimiento::where('ID', $args['ID'])->update([
                    'Codigo'=>$args["Codigo"],
                    'Carpeta'=>$ubicacion
                ]);
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
    'AsignarResponsable' => [
        'type' => $boolType,
        'args' => [
            'ID'=>Type::nonNull(Type::int()),
            'Responsable'=>Type::nonNull(Type::int())
        ],
        'resolve' => function($root, $args) {
            $Seguimiento=Seguimiento::find($args['ID']);
            $v=false;
            if ($Seguimiento!=null) {
                Seguimiento::where('ID', $args['ID'])->update([
                    'Responsable'=>isset($args["Responsable"])?$args["Responsable"]:$Seguimiento->Responsable,
                    'FechaAsignado'=>date("Y-m-d")
                ]);
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
]
?>