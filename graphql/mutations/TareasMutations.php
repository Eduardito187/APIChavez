<?php
use App\Models\Tareas;
use GraphQL\Type\Definition\Type;
$TareasMutations=[
    'addTareas'=>[
        'type'=>$ContadorType,
        'args'=>[
            'Sucursal'=>Type::nonNull(Type::int()),
            'Area'=>Type::nonNull(Type::int()),
            'Detalle'=>Type::nonNull(Type::string()),
            'Solicitante'=>Type::nonNull(Type::int()),
            'Prioridad'=>Type::nonNull(Type::int())
        ],
        'resolve'=>function($root, $args){
            $DATE_ = date("Y-m-d");
            
            $objc=new mysql;
            $sql="SELECT GetCodeTarea(".date('Y').") AS Tarea";
            //CLASE PARA CONVERTIR CONSULTA MYSQL A FORMATO JSON
            $objson=new json;
            $aux= $objson->convertir($objc->consultar($sql));
            $COD_TAREA = $aux[0]["Tarea"];
            $Tareas=new Tareas([
                'ID'=>NULL,
                'Codigo'=>$COD_TAREA,
                'Sucursal'=> $args["Sucursal"] == 0 ? NULL : $args["Sucursal"] ,
                'Detalle'=>$args["Detalle"],
                'Solicitante'=>$args["Solicitante"],
                'Responsable'=>NULL,
                'Estado'=>"Pendiente",
                'Prioridad'=>$args["Prioridad"],
                'Pospuesta'=>NULL,
                'FechaCreacion'=>$DATE_,
                'FechaInicio'=>NULL,
                'Conclusion'=>NULL,
                'Eliminado'=>0,
                'FechaEliminado'=>NULL,
                'area'=> $args["Area"] == 0 ? NULL : $args["Area"]
            ]);
            $x=$Tareas->save();
            $Tarea=Tareas::where('Codigo',$COD_TAREA)->first();
            if ($Tarea == null) {
                return array("Cantidad"=>null);
            }
            if(!mkdir('./graphql/tareas/'.$COD_TAREA, 0777, true)) {
                return array("Cantidad"=>null);
            }
            return array("Cantidad"=>$Tarea->ID);
        }
    ],
    'editTareas' => [
        'type' => $boolType,
        'args' => [
            'ID'=>Type::nonNull(Type::int()),
            'Codigo'=>Type::string(),
            'Sucursal'=>Type::int(),
            'Detalle'=>Type::string(),
            'Solicitante'=>Type::int(),
            'Responsable'=>Type::int(),
            'Estado'=>Type::string(),
            'Prioridad'=>Type::int(),
            'Pospuesta'=>Type::string()
        ],
        'resolve' => function($root, $args) {
            $Tareas=Tareas::find($args['ID']);
            $v=false;
            if ($Tareas!=null) {
                Tareas::where('ID', $args['ID'])->update([
                    'Codigo'=>isset($args["Codigo"])?$args["Codigo"]:$Tareas->Codigo,
                    'Sucursal'=>isset($args["Sucursal"])?$args["Sucursal"]:$Tareas->Sucursal,
                    'Detalle'=>isset($args["Detalle"])?$args["Detalle"]:$Tareas->Detalle,
                    'Solicitante'=>isset($args["Solicitante"])?$args["Solicitante"]:$Tareas->Solicitante,
                    'Responsable'=>isset($args["Responsable"])?$args["Responsable"]:$Tareas->Responsable,
                    'Estado'=>isset($args["Estado"])?$args["Estado"]:$Tareas->Estado,
                    'Prioridad'=>isset($args["Prioridad"])?$args["Prioridad"]:$Tareas->Prioridad,
                    'Pospuesta'=>isset($args["Pospuesta"])?$args["Pospuesta"]:$Tareas->Pospuesta
                ]);
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
    'delTareas' => [
        'type' => $boolType,
        'args' => [
            'ID' => Type::nonNull(Type::int())
        ],
        'resolve' => function($root, $args) {
            $Tareas = Tareas::find($args['ID']);
            $v=false;
            if ($Tareas!=null) {
                //Tareas::where('ID', $args['ID'])->delete();
                Tareas::where('ID', $args['ID'])->update([
                    'Eliminado'=>1,
                    'FechaEliminado'=>date("Y-m-d")
                ]);
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
    'addResponsableTarea'=>[
        'type' => $boolType,
        'args' => [
            'ID' => Type::nonNull(Type::int()),
            'Responsable'=>Type::nonNull(Type::int())
        ],
        'resolve' => function($root, $args) {
            $Tareas = Tareas::find($args['ID']);
            $v=false;
            if ($Tareas!=null) {
                Tareas::where('ID', $args['ID'])->update([
                    'Responsable'=>$args["Responsable"],
                    'Estado'=>"En Proceso",
                    'FechaInicio'=>date("Y-m-d")
                ]);
                $v=true;
            }
            return array("Respuesta"=>$v);
        }
    ],
    'PosponerTarea'=>[
        'type' => $boolType,
        'args' => [
            'ID' => Type::nonNull(Type::int()),
            'Persona'=>Type::nonNull(Type::int()),
            'Fecha'=>Type::nonNull(Type::string()),
            'Motivo'=>Type::nonNull(Type::string())
        ],
        'resolve' => function($root, $args) {
            $Tareas = Tareas::find($args['ID']);
            $v=false;
            if ($Tareas!=null) {
                if ($Tareas->Solicitante==$args["Persona"] || $Tareas->Responsable==$args["Persona"]) {
                    Tareas::where('ID', $args['ID'])->update([
                        'FechaPospuesta'=>$args["Fecha"],
                        'Pospuesta'=>$args["Motivo"]
                    ]);
                    $v=true;
                }
            }
            return array("Respuesta"=>$v);
        }
    ],
    'TareaRealizado'=>[
        'type' => $boolType,
        'args' => [
            'ID' => Type::nonNull(Type::int()),
            'Persona'=>Type::nonNull(Type::int()),
            'Conclusion'=>Type::nonNull(Type::string())
        ],
        'resolve' => function($root, $args) {
            $Tareas = Tareas::find($args['ID']);
            $v=false;
            if ($Tareas!=null) {
                if ($Tareas->Solicitante==$args["Persona"] || $Tareas->Responsable==$args["Persona"]) {
                    Tareas::where('ID', $args['ID'])->update([
                        'Estado'=>"Finalizado",
                        'FechaFinalizacion'=>date("Y-m-d"),
                        'Conclusion'=>$args["Conclusion"]
                    ]);
                    $v=true;
                }
            }
            return array("Respuesta"=>$v);
        }
    ],
]
?>