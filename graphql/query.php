<?php

use App\Models\Area;
use App\Models\Cuenta;
use App\Models\Rango;
use App\Models\Proveedor;
use App\Models\Sucursal;
use App\Models\Extintor;
use App\Models\ExtintorDato;
use App\Models\Prioridad;
use App\Models\Seguimiento;
use App\Models\EmpresaGuardia;
use App\Models\Guardia;
use App\Models\EmpresaGuardiaSucursal;
use App\Models\GuardiaSucursal;
use App\Models\ControlLLAVES;
use App\Models\ControlDiario;
use App\Models\Tareas;
use App\Models\Lotes;
use App\Models\Producto;
use App\Models\ProductoLote;
use App\Models\Recepcion;
use App\Models\Permisos;
use App\Models\Mensajes;
use App\Models\VacacionPersona;
use App\Models\VacacionesDias;
use App\Models\Trabajadores;
use App\Models\Control;
use App\Models\TablaControl;
use App\Models\ControlDiscos;
use App\Models\SalidaDiscos;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
require('FCH/json.php');
require('FCH/mysql.php');
$rootQuery=new ObjectType([
    'name'=>'Query',
    'fields'=>[
        'SiguienteCITE'=>[
            'type'=>$CiteType,
            'resolve'=>function($root,$args){
                //EJECUCION DE UNA FUNCION MYSQL
                //CLASE DE CONECCION PDO_MYSQL
                $objc=new mysql;
                $sql="SELECT ObtenerSiguienteCite() AS CITE";
                //CLASE PARA CONVERTIR CONSULTA MYSQL A FORMATO JSON
                $objson=new json;
                $aux= $objson->convertir($objc->consultar($sql));
                return array("CITE"=>$aux[0]["CITE"]);
            }
        ],
        'ResponsablesHabil'=>[
            'type'=>Type::listOf($cuentaType),
            'resolve'=>function($root,$args){
                //EJECUCION DE UNA FUNCION MYSQL
                //CLASE DE CONECCION PDO_MYSQL
                $objc=new mysql;
                $sql="SELECT DISTINCT ID,nombre FROM cuenta WHERE NOT EXISTS (SELECT * FROM seguimiento WHERE seguimiento.Responsable = cuenta.ID) 
                OR EXISTS(SELECT * FROM seguimiento WHERE seguimiento.Estado='Iniciado' AND seguimiento.Responsable=cuenta.ID OR seguimiento.Estado='Pendiente' AND seguimiento.Responsable=cuenta.ID 
                GROUP BY seguimiento.Responsable HAVING COUNT(*) <= 3)";
                //CLASE PARA CONVERTIR CONSULTA MYSQL A FORMATO JSON para cambiar el limite de seguimiento por responsable
                $objson=new json;
                $aux= $objson->convertir($objc->consultar($sql));
                return $aux;
            }
        ],
        'cuenta'=>[
            'type'=>$cuentaType,
            'args'=>[
                'ID'=>Type::nonNull(Type::string())
            ],
            'resolve'=>function($root,$args){
                $cuenta=Cuenta::find($args["ID"])->toArray();
                return $cuenta;
            }
        ],
        'cuenta_menos'=>[
            'type'=>Type::listOf($cuentaType),
            'args'=>[
                'ID'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $cuenta=Cuenta::where('ID', '!=', $args["ID"])->get()->toArray();
                return $cuenta;
            }
        ],
        'total_cuentas'=>[
            'type'=>Type::int(),
            'resolve'=>function($root,$args){
                $count = Cuenta::all()->count();
                return $count;
            }
        ],
        'cuentas'=>[
            'type'=>Type::listOf($cuentaType),
            'resolve'=>function($root,$args){
                $cuentas = Cuenta::get()->toArray();
                return $cuentas;
            }
        ],
        'mensajes'=>[
            'type'=>Type::listOf($mensajesType),
            'resolve'=>function($root,$args){
                $mensajes = Mensajes::get()->toArray();
                return $mensajes;
            }
        ],
        'rango'=>[
            'type'=>$rangoType,
            'args'=>[
                'ID'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $rango=Rango::find($args["ID"])->toArray();
                return $rango;
            }
        ],
        'rangos'=>[
            'type'=>Type::listOf($rangoType),
            'resolve'=>function($root,$args){
                $rangos = Rango::get()->toArray();
                return $rangos;
            }
        ],
        'proveedor'=>[
            'type'=>$proveedorType,
            'args'=>[
                'ID'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $proveedor=Proveedor::find($args["ID"])->toArray();
                return $proveedor;
            }
        ],
        'proveedores'=>[
            'type'=>Type::listOf($proveedorType),
            'resolve'=>function($root,$args){
                $proveedores = Proveedor::get()->toArray();
                return $proveedores;
            }
        ],
        'proveedoresLIKE'=>[
            'type'=>Type::listOf($proveedorType),
            'args'=>[
                'Busqueda'=>Type::nonNull(Type::string()),
                'Tipo'=>Type::nonNull(Type::string())
            ],
            'resolve'=>function($root,$args){
                if (isset($args["Tipo"]) && isset($args["Busqueda"])) {
                    $proveedores=[];
                    if ($args["Tipo"]=="Nombre") {
                        $proveedores=Proveedor::where('Nombre', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                    }else if ($args["Tipo"]=="Telefono") {
                        $proveedores=Proveedor::where('Telefono', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                    }else if ($args["Tipo"]=="Correo Electronico") {
                        $proveedores=Proveedor::where('Correo', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                    }
                    return $proveedores;
                }else{
                    return [];
                }
            }
        ],
        'sucursal'=>[
            'type'=>$sucursalType,
            'args'=>[
                'ID'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $sucursal=Sucursal::find($args["ID"])->toArray();
                return $sucursal;
            }
        ],
        'sucursalesLIKE'=>[
            'type'=>Type::listOf($sucursalType),
            'args'=>[
                'Busqueda'=>Type::nonNull(Type::string()),
                'Tipo'=>Type::nonNull(Type::string())
            ],
            'resolve'=>function($root,$args){
                if (isset($args["Tipo"]) && isset($args["Busqueda"])) {
                    $sucursales=[];
                    if ($args["Tipo"]=="Nombre") {
                        $sucursales=Sucursal::where('Nombre', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                    }else if ($args["Tipo"]=="Codigo") {
                        $sucursales=Sucursal::where('CodigoSucursal', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                    }else if ($args["Tipo"]=="Telefono") {
                        $sucursales=Sucursal::where('Telefono', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                    }else if ($args["Tipo"]=="Telefono Interno") {
                        $sucursales=Sucursal::where('TelfInterno', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                    }else if ($args["Tipo"]=="Correo Electronido") {
                        $sucursales=Sucursal::where('Correo', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                    }
                    return $sucursales;
                }else{
                    return [];
                }
            }
        ],
        'sucursales'=>[
            'type'=>Type::listOf($sucursalType),
            'resolve'=>function($root,$args){
                $sucursales = Sucursal::get()->toArray();
                return $sucursales;
            }
        ],
        'extintor'=>[
            'type'=>$extintorType,
            'args'=>[
                'ID'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $Extintor=Extintor::find($args["ID"])->toArray();
                return $Extintor;
            }
        ],
        'extintoresRecargo'=>[
            'type'=>Type::listOf($extintorDatoType),
            'resolve'=>function($root,$args){
                $Extintores = ExtintorDato::whereDate('Recargo','<=',date("Y-m-d"))->get()->toArray();
                return $Extintores;
            }
        ],
        'extintoresRecargoContador'=>[
            'type'=>$ContadorType,
            'resolve'=>function($root,$args){
                #'Codigo','PH','Peso','Observacion','Recargo',
                $Extintores = ExtintorDato::whereDate('Recargo','<=',date("Y-m-d"))->count();
                return array("Cantidad"=>$Extintores);
            }
        ],
        'extintores'=>[
            'type'=>Type::listOf($extintorDatoType),
            'resolve'=>function($root,$args){
                $Extintores = ExtintorDato::get()->toArray();
                return $Extintores;
            }
        ],
        'extintoresLIKE'=>[
            'type'=>Type::listOf($extintorDatoType),
            'args'=>[
                'Busqueda'=>Type::nonNull(Type::string()),
                'Tipo'=>Type::nonNull(Type::string())
            ],
            'resolve'=>function($root,$args){
                #'Codigo','PH','Peso','Observacion','Recargo',
                if (isset($args["Tipo"]) && isset($args["Busqueda"])) {
                    $Extintores=[];
                    if ($args["Tipo"]=="Peso") {
                        $Extintores=ExtintorDato::where('Peso', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                    }else if ($args["Tipo"]=="Tipo") {
                        $extintor=Extintor::select('ID')->where('Tipo', 'like', '%'.$args["Busqueda"].'%')->get();
                        $ids=array();
                        foreach($extintor as $s){
                            $ids[]=$s->ID;
                        }
                        $Extintores=ExtintorDato::whereIn('Extintores', $ids)->get()->toArray();
                    }else if ($args["Tipo"]=="Recargo") {
                        $Extintores=ExtintorDato::where('Recargo', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                    }else if ($args["Tipo"]=="Sucursal") {
                        $sucursales=Sucursal::select('ID')->where('Nombre', 'like', '%'.$args["Busqueda"].'%')->get();
                        $ids1=array();
                        foreach($sucursales as $s){
                            $ids1[]=$s->ID;
                        }
                        $extintor=Extintor::select('ID')->whereIn('Sucursal', $ids1)->get();

                        $ids=array();
                        foreach($extintor as $s){
                            $ids[]=$s->ID;
                        }
                        $Extintores=ExtintorDato::whereIn('Extintores', $ids)->get()->toArray();
                    }
                    return $Extintores;
                }else{
                    return [];
                }
            }
        ],
        'prioridad'=>[
            'type'=>$prioridadType,
            'args'=>[
                'ID'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $Prioridad=Prioridad::find($args["ID"])->toArray();
                return $Prioridad;
            }
        ],
        'prioridades'=>[
            'type'=>Type::listOf($prioridadType),
            'resolve'=>function($root,$args){
                $Prioridad=Prioridad::get()->toArray();
                return $Prioridad;
            }
        ],
        'seguimiento'=>[
            'type'=>$seguimientoType,
            'args'=>[
                'ID'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $Seguimiento=Seguimiento::find($args["ID"])->toArray();
                return $Seguimiento;
            }
        ],
        'seguimientos'=>[
            'type'=>Type::listOf($seguimientoType),
            'resolve'=>function($root,$args){
                $Seguimiento=Seguimiento::where('Eliminado',0)->get()->toArray();
                return $Seguimiento;
            }
        ],
        'seguimientosLIKE'=>[
            'type'=>Type::listOf($seguimientoType),
            'args'=>[
                'Busqueda'=>Type::nonNull(Type::string()),
                'Tipo'=>Type::nonNull(Type::string())
            ],
            'resolve'=>function($root,$args){
                if (isset($args["Busqueda"])) {
                    $Seguimiento=[];
                    if (isset($args["Tipo"])) {
                        if ($args["Tipo"]=="Solicitante") {
                            $Seguimiento=Seguimiento::where('Eliminado',0)->where('Solicitante', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                        }else if ($args["Tipo"]=="Autorizador") {
                            $Seguimiento=Seguimiento::where('Eliminado',0)->where('Autorizacion', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                        }else if ($args["Tipo"]=="Codigo Sucursal") {
                            $sucursales=Sucursal::select('ID')->where('CodigoSucursal', 'like', '%'.$args["Busqueda"].'%')->get();
                            $ids=array();
                            foreach($sucursales as $s){
                                $ids[]=$s->ID;
                            }
                            $Seguimiento=Seguimiento::where('Eliminado',0)->whereIn('Sucursal', $ids)->get()->toArray();
                        }else if ($args["Tipo"]=="Nombre Sucursal") {
                            $sucursales=Sucursal::select('ID')->where('Nombre', 'like', '%'.$args["Busqueda"].'%')->get();
                            $ids=array();
                            foreach($sucursales as $s){
                                $ids[]=$s->ID;
                            }
                            $Seguimiento=Seguimiento::where('Eliminado',0)->whereIn('Sucursal', $ids)->get()->toArray();
                        }else if ($args["Tipo"]=="Responsable"){
                            $cuentas=Cuenta::select('ID')->where('nombre', 'like', '%'.$args["Busqueda"].'%')->get();
                            $ids=array();
                            foreach($cuentas as $s){
                                $ids[]=$s->ID;
                            }
                            $Seguimiento=Seguimiento::where('Eliminado',0)->whereIn('Responsable', $ids)->get()->toArray();
                        }else if($args["Tipo"]=="Codigo Seguimiento"){
                            $Seguimiento=Seguimiento::where('Eliminado',0)->where('Codigo', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                        }
                    }
                    return $Seguimiento;
                }else{
                    return [];
                }
            }
        ],
        'seguimientos3M'=>[
            'type'=>Type::listOf($seguimientoType),
            'resolve'=>function($root,$args){
                $fecha_actual = date("Y-m-d");
                $fecha_3M = date("Y-m-d",strtotime($fecha_actual."- 3 month"));
                $Seguimiento=Seguimiento::where('Eliminado',0)->whereDate('FechaCreacion','>=',$fecha_3M)->get()->toArray();
                return $Seguimiento;
            }
        ],
        'seguimientosLIKE3M'=>[
            'type'=>Type::listOf($seguimientoType),
            'args'=>[
                'Busqueda'=>Type::nonNull(Type::string()),
                'Tipo'=>Type::nonNull(Type::string()),
                'MES'=>Type::nonNull(Type::string()),
                'ESTADO'=>Type::nonNull(Type::string())
            ],
            'resolve'=>function($root,$args){
                if (isset($args["Busqueda"])) {
                    $Seguimiento=[];
                    if (isset($args["Tipo"])) {
                        $fecha_actual = date("Y-m-d");
                        #$fecha_3M = date("Y-m-d",strtotime($fecha_actual."- 3 month"));
                        if ($args["ESTADO"]=="Pendiente") {
                            if ($args["Tipo"]=="Solicitante") {
                                $Seguimiento=Seguimiento::where('Eliminado',0)->where( Seguimiento::raw('MONTH(FechaCreacion)'), '=', $args["MES"] )->where('Estado','Pendiente')->where('Solicitante', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                            }else if ($args["Tipo"]=="Autorizador") {
                                $Seguimiento=Seguimiento::where('Eliminado',0)->where( Seguimiento::raw('MONTH(FechaCreacion)'), '=', $args["MES"] )->where('Estado','Pendiente')->where('Autorizacion', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                            }else if ($args["Tipo"]=="Codigo Sucursal") {
                                $sucursales=Sucursal::select('ID')->where('CodigoSucursal', 'like', '%'.$args["Busqueda"].'%')->get();
                                $ids=array();
                                foreach($sucursales as $s){
                                    $ids[]=$s->ID;
                                }
                                $Seguimiento=Seguimiento::where('Eliminado',0)->where( Seguimiento::raw('MONTH(FechaCreacion)'), '=', $args["MES"] )->where('Estado','Pendiente')->whereIn('Sucursal', $ids)->get()->toArray();
                            }else if ($args["Tipo"]=="Nombre Sucursal") {
                                $sucursales=Sucursal::select('ID')->where('Nombre', 'like', '%'.$args["Busqueda"].'%')->get();
                                $ids=array();
                                foreach($sucursales as $s){
                                    $ids[]=$s->ID;
                                }
                                $Seguimiento=Seguimiento::where('Eliminado',0)->where( Seguimiento::raw('MONTH(FechaCreacion)'), '=', $args["MES"] )->where('Estado','Pendiente')->whereIn('Sucursal', $ids)->get()->toArray();
                            }else if ($args["Tipo"]=="Responsable"){
                                $cuentas=Cuenta::select('ID')->where('nombre', 'like', '%'.$args["Busqueda"].'%')->get();
                                $ids=array();
                                foreach($cuentas as $s){
                                    $ids[]=$s->ID;
                                }
                                $Seguimiento=Seguimiento::where('Eliminado',0)->where( Seguimiento::raw('MONTH(FechaCreacion)'), '=', $args["MES"] )->where('Estado','Pendiente')->whereIn('Responsable', $ids)->get()->toArray();
                            }else if($args["Tipo"]=="Codigo Seguimiento"){
                                $Seguimiento=Seguimiento::where('Eliminado',0)->where( Seguimiento::raw('MONTH(FechaCreacion)'), '=', $args["MES"] )->where('Estado','Pendiente')->where('Codigo', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                            }
                        }
                        if ($args["ESTADO"]=="Iniciado") {
                            if ($args["Tipo"]=="Solicitante") {
                                $Seguimiento=Seguimiento::where('Eliminado',0)->where( Seguimiento::raw('MONTH(FechaInicio)'), '=', $args["MES"] )->where('Estado','Iniciado')->where('Solicitante', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                            }else if ($args["Tipo"]=="Autorizador") {
                                $Seguimiento=Seguimiento::where('Eliminado',0)->where( Seguimiento::raw('MONTH(FechaInicio)'), '=', $args["MES"] )->where('Estado','Iniciado')->where('Autorizacion', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                            }else if ($args["Tipo"]=="Codigo Sucursal") {
                                $sucursales=Sucursal::select('ID')->where('CodigoSucursal', 'like', '%'.$args["Busqueda"].'%')->get();
                                $ids=array();
                                foreach($sucursales as $s){
                                    $ids[]=$s->ID;
                                }
                                $Seguimiento=Seguimiento::where('Eliminado',0)->where( Seguimiento::raw('MONTH(FechaInicio)'), '=', $args["MES"] )->where('Estado','Iniciado')->whereIn('Sucursal', $ids)->get()->toArray();
                            }else if ($args["Tipo"]=="Nombre Sucursal") {
                                $sucursales=Sucursal::select('ID')->where('Nombre', 'like', '%'.$args["Busqueda"].'%')->get();
                                $ids=array();
                                foreach($sucursales as $s){
                                    $ids[]=$s->ID;
                                }
                                $Seguimiento=Seguimiento::where('Eliminado',0)->where( Seguimiento::raw('MONTH(FechaInicio)'), '=', $args["MES"] )->where('Estado','Iniciado')->whereIn('Sucursal', $ids)->get()->toArray();
                            }else if ($args["Tipo"]=="Responsable"){
                                $cuentas=Cuenta::select('ID')->where('nombre', 'like', '%'.$args["Busqueda"].'%')->get();
                                $ids=array();
                                foreach($cuentas as $s){
                                    $ids[]=$s->ID;
                                }
                                $Seguimiento=Seguimiento::where('Eliminado',0)->where( Seguimiento::raw('MONTH(FechaInicio)'), '=', $args["MES"] )->where('Estado','Iniciado')->whereIn('Responsable', $ids)->get()->toArray();
                            }else if($args["Tipo"]=="Codigo Seguimiento"){
                                $Seguimiento=Seguimiento::where('Eliminado',0)->where( Seguimiento::raw('MONTH(FechaInicio)'), '=', $args["MES"] )->where('Estado','Iniciado')->where('Codigo', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                            }
                        }
                        if($args["ESTADO"]=="Finalizado"){
                            if ($args["Tipo"]=="Solicitante") {
                                $Seguimiento=Seguimiento::where('Eliminado',0)->where('Estado','Finalizado')->where( Seguimiento::raw('MONTH(FechaFin)'), '=', $args["MES"] )->where('Solicitante', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                            }else if ($args["Tipo"]=="Autorizador") {
                                $Seguimiento=Seguimiento::where('Eliminado',0)->where('Estado','Finalizado')->where( Seguimiento::raw('MONTH(FechaFin)'), '=', $args["MES"] )->where('Autorizacion', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                            }else if ($args["Tipo"]=="Codigo Sucursal") {
                                $sucursales=Sucursal::select('ID')->where('CodigoSucursal', 'like', '%'.$args["Busqueda"].'%')->get();
                                $ids=array();
                                foreach($sucursales as $s){
                                    $ids[]=$s->ID;
                                }
                                $Seguimiento=Seguimiento::where('Eliminado',0)->where('Estado','Finalizado')->where( Seguimiento::raw('MONTH(FechaFin)'), '=', $args["MES"] )->whereIn('Sucursal', $ids)->get()->toArray();
                            }else if ($args["Tipo"]=="Nombre Sucursal") {
                                $sucursales=Sucursal::select('ID')->where('Nombre', 'like', '%'.$args["Busqueda"].'%')->get();
                                $ids=array();
                                foreach($sucursales as $s){
                                    $ids[]=$s->ID;
                                }
                                $Seguimiento=Seguimiento::where('Eliminado',0)->where('Estado','Finalizado')->where( Seguimiento::raw('MONTH(FechaFin)'), '=', $args["MES"] )->whereIn('Sucursal', $ids)->get()->toArray();
                            }else if ($args["Tipo"]=="Responsable"){
                                $cuentas=Cuenta::select('ID')->where('nombre', 'like', '%'.$args["Busqueda"].'%')->get();
                                $ids=array();
                                foreach($cuentas as $s){
                                    $ids[]=$s->ID;
                                }
                                $Seguimiento=Seguimiento::where('Eliminado',0)->where('Estado','Finalizado')->where( Seguimiento::raw('MONTH(FechaFin)'), '=', $args["MES"] )->whereIn('Responsable', $ids)->get()->toArray();
                            }else if($args["Tipo"]=="Codigo Seguimiento"){
                                $Seguimiento=Seguimiento::where('Eliminado',0)->where('Estado','Finalizado')->where( Seguimiento::raw('MONTH(FechaFin)'), '=', $args["MES"] )->where('Codigo', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                            }
                        }
                    }
                    return $Seguimiento;
                }else{
                    return [];
                }
            }
        ],
        'files_seg'=>[
            'type'=>Type::int(),
            'args'=>[
                'ID'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $Seguimiento=Seguimiento::find($args["ID"]);
                if ($Seguimiento!=null) {
                    $Sucursal = Sucursal::find($Seguimiento->Sucursal);
                    if ($Seguimiento->Codigo==null) {
                        $sub=$Seguimiento->ID;
                    }else{
                        $sub=$Seguimiento->Codigo;
                    }
                    $num = count(glob("./graphql/data/".$Sucursal->Nombre."/".$sub."/"."*"));
                    return $num;
                }
                return 0;
            }
        ],
        'files'=>[
            'type'=>Type::int(),
            'resolve'=>function($root,$args){
                $i = 0; 
                $dir = './graphql/data/Trompillo/00-11-00';
                if ($handle = opendir($dir)) {
                    while (($file = readdir($handle)) !== false){
                        if (!in_array($file, array('.', '..')) && !is_dir($dir.$file)) 
                            $i++;
                    }
                }
                return $i;
            }
        ],
        'empresa_guardia'=>[
            'type'=>$empresaGuardiaType,
            'args'=>[
                'ID'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $EmpresaGuardia=EmpresaGuardia::find($args["ID"])->toArray();
                return $EmpresaGuardia;
            }
        ],
        'empresa_guardias'=>[
            'type'=>Type::listOf($empresaGuardiaType),
            'resolve'=>function($root,$args){
                $EmpresaGuardia=EmpresaGuardia::get()->toArray();
                return $EmpresaGuardia;
            }
        ],
        'guardia'=>[
            'type'=>$guardiaType,
            'args'=>[
                'ID'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $Guardia=Guardia::find($args["ID"])->toArray();
                return $Guardia;
            }
        ],
        'guardias'=>[
            'type'=>Type::listOf($guardiaType),
            'resolve'=>function($root,$args){
                $Guardia=Guardia::get()->toArray();
                return $Guardia;
            }
        ],
        'empresa_guardia_sucursal'=>[
            'type'=>$empresaguardiasucursalType,
            'args'=>[
                'ID'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $EmpresaGuardiaSucursal=EmpresaGuardiaSucursal::find($args["ID"])->toArray();
                return $EmpresaGuardiaSucursal;
            }
        ],
        'empresa_guardia_sucursal_llaves'=>[
            'type'=>Type::listOf($empresaguardiasucursalType),
            'args'=>[
                'Sucursal'=>Type::nonNull(Type::int()),
                'Empresa'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $EmpresaGuardiaSucursal=EmpresaGuardiaSucursal::where('Sucursal',$args["Sucursal"])->where('Empresa',$args["Empresa"])->get()->toArray();
                return $EmpresaGuardiaSucursal;
            }
        ],
        'empresa_guardia_sucursal_llave_sucursal'=>[
            'type'=>Type::listOf($empresaguardiasucursalType),
            'args'=>[
                'Sucursal'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $EmpresaGuardiaSucursal=EmpresaGuardiaSucursal::where('Sucursal',$args["Sucursal"])->get()->toArray();
                return $EmpresaGuardiaSucursal;
            }
        ],
        'empresa_guardia_sucursal_llave_empresa'=>[
            'type'=>Type::listOf($empresaguardiasucursalType),
            'args'=>[
                'Empresa'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $EmpresaGuardiaSucursal=EmpresaGuardiaSucursal::where('Empresa',$args["Empresa"])->get()->toArray();
                return $EmpresaGuardiaSucursal;
            }
        ],
        'empresa_guardias_sucursal'=>[
            'type'=>Type::listOf($empresaguardiasucursalType),
            'resolve'=>function($root,$args){
                $EmpresaGuardiaSucursal=EmpresaGuardiaSucursal::get()->toArray();
                return $EmpresaGuardiaSucursal;
            }
        ],
        'guardia_sucursal'=>[
            'type'=>$guardiasucursalType,
            'args'=>[
                'ID'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $GuardiaSucursal=GuardiaSucursal::find($args["ID"])->toArray();
                return $GuardiaSucursal;
            }
        ],
        'guardias_sucursal'=>[
            'type'=>Type::listOf($guardiasucursalType),
            'resolve'=>function($root,$args){
                $GuardiaSucursal=GuardiaSucursal::get()->toArray();
                return $GuardiaSucursal;
            }
        ],
        'control_llave'=>[
            'type'=>$ControlLLAVESType,
            'args'=>[
                'ID'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $ControlLLAVES=ControlLLAVES::find($args["ID"])->toArray();
                return $ControlLLAVES;
            }
        ],
        'control_llavesLIKE'=>[
            'type'=>Type::listOf($ControlLLAVESType),
            'args'=>[
                'Busqueda'=>Type::nonNull(Type::string())
            ],
            'resolve'=>function($root,$args){
                if (isset($args["Busqueda"])) {
                    $ControlLLAVES=[];
                    $ControlLLAVES=ControlLLAVES::where('Entrega', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                    return $ControlLLAVES;
                }else{
                    return [];
                }
            }
        ],
        'control_llaves'=>[
            'type'=>Type::listOf($ControlLLAVESType),
            'resolve'=>function($root,$args){
                $ControlLLAVES=ControlLLAVES::get()->toArray();
                return $ControlLLAVES;
            }
        ],
        'control_diario'=>[
            'type'=>$ControlDiarioType,
            'args'=>[
                'ID'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $ControlDiario=ControlDiario::find($args["ID"])->toArray();
                return $ControlDiario;
            }
        ],
        'control_diarios'=>[
            'type'=>Type::listOf($ControlDiarioType),
            'resolve'=>function($root,$args){
                $ControlDiario=ControlDiario::get()->toArray();
                return $ControlDiario;
            }
        ],
        'tarea'=>[
            'type'=>$TareasType,
            'args'=>[
                'ID'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $Tareas=Tareas::find($args["ID"])->toArray();
                return $Tareas;
            }
        ],
        'tareas'=>[
            'type'=>Type::listOf($TareasType),
            'resolve'=>function($root,$args){
                $Tareas=Tareas::where('Eliminado',0)->get()->toArray();
                return $Tareas;
            }
        ],
        'tareasLIKE'=>[
            'type'=>Type::listOf($TareasType),
            'args'=>[
                'Busqueda'=>Type::nonNull(Type::string()),
                'Tipo'=>Type::nonNull(Type::string()),
                'ESTADO'=>Type::nonNull(Type::string()),
                'MES'=>Type::nonNull(Type::string())
            ],
            'resolve'=>function($root,$args){
                if (isset($args["Busqueda"])) {
                    $Tareas=[];
                    if (isset($args["Tipo"])) {
                        if ($args["ESTADO"]=="En Proceso") {
                            if ($args["Tipo"]=="Codigo Tarea") {
                                $Tareas=Tareas::where('Eliminado',0)->where('Estado','En Proceso')->where( Tareas::raw('MONTH(FechaInicio)'), '=', $args["MES"] )->where('Codigo', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                            }else if ($args["Tipo"]=="Codigo Sucursal") {
                                $sucursales=Sucursal::select('ID')->where('CodigoSucursal', 'like', '%'.$args["Busqueda"].'%')->get();
                                $ids=array();
                                foreach($sucursales as $s){
                                    $ids[]=$s->ID;
                                }
                                $Tareas=Tareas::where('Eliminado',0)->where('Estado','En Proceso')->where( Tareas::raw('MONTH(FechaInicio)'), '=', $args["MES"] )->whereIn('Sucursal', $ids)->get()->toArray();
                            }else if($args["Tipo"]=="Nombre Sucursal"){
                                $sucursales=Sucursal::select('ID')->where('Nombre', 'like', '%'.$args["Busqueda"].'%')->get();
                                $ids=array();
                                foreach($sucursales as $s){
                                    $ids[]=$s->ID;
                                }
                                $Tareas=Tareas::where('Eliminado',0)->where('Estado','En Proceso')->where( Tareas::raw('MONTH(FechaInicio)'), '=', $args["MES"] )->whereIn('Sucursal', $ids)->get()->toArray();
                            }
                        }
                        if ($args["ESTADO"]=="Pendiente") {
                            if ($args["Tipo"]=="Codigo Tarea") {
                                $Tareas=Tareas::where('Eliminado',0)->where('Estado','Pendiente')->where( Tareas::raw('MONTH(FechaCreacion)'), '=', $args["MES"] )->where('Codigo', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                            }else if ($args["Tipo"]=="Codigo Sucursal") {
                                $sucursales=Sucursal::select('ID')->where('CodigoSucursal', 'like', '%'.$args["Busqueda"].'%')->get();
                                $ids=array();
                                foreach($sucursales as $s){
                                    $ids[]=$s->ID;
                                }
                                $Tareas=Tareas::where('Eliminado',0)->where('Estado','Pendiente')->where( Tareas::raw('MONTH(FechaCreacion)'), '=', $args["MES"] )->whereIn('Sucursal', $ids)->get()->toArray();
                            }else if($args["Tipo"]=="Nombre Sucursal"){
                                $sucursales=Sucursal::select('ID')->where('Nombre', 'like', '%'.$args["Busqueda"].'%')->get();
                                $ids=array();
                                foreach($sucursales as $s){
                                    $ids[]=$s->ID;
                                }
                                $Tareas=Tareas::where('Eliminado',0)->where('Estado','Pendiente')->where( Tareas::raw('MONTH(FechaCreacion)'), '=', $args["MES"] )->whereIn('Sucursal', $ids)->get()->toArray();
                            }
                        }
                        if ($args["ESTADO"]=="Finalizado") {
                            if ($args["Tipo"]=="Codigo Tarea") {
                                $Tareas=Tareas::where('Eliminado',0)->where('Estado','Finalizado')->where( Tareas::raw('MONTH(FechaFinalizacion)'), '=', $args["MES"] )->where('Codigo', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                            }else if ($args["Tipo"]=="Codigo Sucursal") {
                                $sucursales=Sucursal::select('ID')->where('CodigoSucursal', 'like', '%'.$args["Busqueda"].'%')->get();
                                $ids=array();
                                foreach($sucursales as $s){
                                    $ids[]=$s->ID;
                                }
                                $Tareas=Tareas::where('Eliminado',0)->where('Estado','Finalizado')->where( Tareas::raw('MONTH(FechaFinalizacion)'), '=', $args["MES"] )->whereIn('Sucursal', $ids)->get()->toArray();
                            }else if($args["Tipo"]=="Nombre Sucursal"){
                                $sucursales=Sucursal::select('ID')->where('Nombre', 'like', '%'.$args["Busqueda"].'%')->get();
                                $ids=array();
                                foreach($sucursales as $s){
                                    $ids[]=$s->ID;
                                }
                                $Tareas=Tareas::where('Eliminado',0)->where('Estado','Finalizado')->where( Tareas::raw('MONTH(FechaFinalizacion)'), '=', $args["MES"] )->whereIn('Sucursal', $ids)->get()->toArray();
                            }
                        }

                        
                    }
                    return $Tareas;
                }else{
                    return [];
                }
            }
        ],
        'lote'=>[
            'type'=>$LotesType,
            'args'=>[
                'ID'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $Lotes=Lotes::find($args["ID"])->toArray();
                return $Lotes;
            }
        ],
        'lotes'=>[
            'type'=>Type::listOf($LotesType),
            'resolve'=>function($root,$args){
                $Lotes=Lotes::get()->toArray();
                return $Lotes;
            }
        ],
        'lotesLIKE'=>[
            'type'=>Type::listOf($LotesType),
            'args'=>[
                'Busqueda'=>Type::nonNull(Type::string())
            ],
            'resolve'=>function($root,$args){
                if (isset($args["Busqueda"])) {
                    $Lotes=[];
                    $Lotes=Lotes::where('Nombre', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                    return $Lotes;
                }else{
                    return [];
                }
            }
        ],
        'producto'=>[
            'type'=>$ProductoType,
            'args'=>[
                'ID'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $Producto=Producto::find($args["ID"])->toArray();
                return $Producto;
            }
        ],
        'productos'=>[
            'type'=>Type::listOf($ProductoType),
            'resolve'=>function($root,$args){
                $Producto=Producto::get()->toArray();
                return $Producto;
            }
        ],
        'productosLIKE'=>[
            'type'=>Type::listOf($ProductoType),
            'args'=>[
                'Busqueda'=>Type::nonNull(Type::string()),
                'Tipo'=>Type::nonNull(Type::string())
            ],
            'resolve'=>function($root,$args){
                if (isset($args["Tipo"]) && isset($args["Busqueda"])) {
                    $Producto=[];
                    if ($args["Tipo"]=="Nombre") {
                        $Producto=Producto::where('Nombre', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                    }else if ($args["Tipo"]=="Factura") {
                        $Producto=Producto::where('Factura', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                    }else if ($args["Tipo"]=="Modelo") {
                        $Producto=Producto::where('Modelo', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                    }
                    return $Producto;
                }else{
                    return [];
                }
            }
        ],
        'producto_lote'=>[
            'type'=>$ProductoLoteType,
            'args'=>[
                'ID'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $ProductoLote=ProductoLote::find($args["ID"])->toArray();
                return $ProductoLote;
            }
        ],
        'producto_lotes'=>[
            'type'=>Type::listOf($ProductoLoteType),
            'resolve'=>function($root,$args){
                $ProductoLote=ProductoLote::get()->toArray();
                return $ProductoLote;
            }
        ],
        'producto_lotes_salidas'=>[
            'type'=>Type::listOf($ProductoLoteType),
            'args'=>[
                'Sucursal'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $ProductoLote=ProductoLote::where('Sucursal',$args["Sucursal"])->get()->toArray();
                return $ProductoLote;
            }
        ],
        'recepcion'=>[
            'type'=>$RecepcionType,
            'args'=>[
                'ID'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $Recepcion=Recepcion::find($args["ID"])->toArray();
                return $Recepcion;
            }
        ],
        'recepciones'=>[
            'type'=>Type::listOf($RecepcionType),
            'resolve'=>function($root,$args){
                $Recepcion=Recepcion::get()->toArray();
                return $Recepcion;
            }
        ],
        'recepcionesLIKE'=>[
            'type'=>Type::listOf($RecepcionType),
            'args'=>[
                'Tipo'=>Type::nonNull(Type::string()),
                'Busqueda'=>Type::nonNull(Type::string())
            ],
            'resolve'=>function($root,$args){
                if (isset($args["Tipo"]) && isset($args["Busqueda"])) {
                    $Recepcion=[];
                    if ($args["Tipo"]=="Entregado") {
                        $Recepcion=Recepcion::where('Entregado', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                    }else if ($args["Tipo"]=="Descripcion") {
                        $Recepcion=Recepcion::where('Descripcion', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                    }
                    return $Recepcion;
                }else{
                    return [];
                }
            }
        ],
        'validacion_login'=>[
            'type'=>$validacionLoginType,
            'args'=>[
                'usuario'=>Type::string(),
                'contra'=>Type::string()
            ],
            'resolve'=>function($root,$args){
                $pwd=md5($args["contra"]);
                $cuenta=Cuenta::where('usuario',$args["usuario"])->where('contra',$pwd)->first();
                //return $cuenta;
                $v=false;
                $id_cuenta=0;
                if ($cuenta!=null) {
                    $v=true;
                    $id_cuenta=$cuenta->ID;
                }
                return array("estado"=>$v,"id_cuenta"=>$id_cuenta);
            }
        ],
        'saber_permiso'=>[
            'type'=>$boolType,
            'args'=>[
                'Cuenta'=>Type::nonNull(Type::int()),
                'Code'=>Type::nonNull(Type::string())
            ],
            'resolve'=>function($root,$args){
                $v=false;
                if (isset($args["Cuenta"]) && isset($args["Code"])) {
                    $cuenta = Cuenta::find($args["Cuenta"]);
                    $rango=$cuenta->RangoID;
                    $Permisos= Permisos::select($args["Code"])->where("RangoID", $rango)->first();
                    if ($Permisos->P1!=null) {
                        $v=$Permisos->P1;
                    }else if ($Permisos->P2!=null) {
                        $v=$Permisos->P2;
                    }else if ($Permisos->P3!=null) {
                        $v=$Permisos->P3;
                    }else if ($Permisos->P4!=null) {
                        $v=$Permisos->P4;
                    }else if ($Permisos->P5!=null) {
                        $v=$Permisos->P5;
                    }else if ($Permisos->P6!=null) {
                        $v=$Permisos->P6;
                    }else if ($Permisos->P7!=null) {
                        $v=$Permisos->P7;
                    }else if ($Permisos->P8!=null) {
                        $v=$Permisos->P8;
                    }else if ($Permisos->P9!=null) {
                        $v=$Permisos->P9;
                    }else if ($Permisos->P10!=null) {
                        $v=$Permisos->P10;
                    }else if ($Permisos->P11!=null) {
                        $v=$Permisos->P11;
                    }else if ($Permisos->P12!=null) {
                        $v=$Permisos->P12;
                    }else if ($Permisos->P13!=null) {
                        $v=$Permisos->P13;
                    }else if ($Permisos->P14!=null) {
                        $v=$Permisos->P14;
                    }else if ($Permisos->P15!=null) {
                        $v=$Permisos->P15;
                    }else if ($Permisos->P16!=null) {
                        $v=$Permisos->P16;
                    }else if ($Permisos->P17!=null) {
                        $v=$Permisos->P17;
                    }else if ($Permisos->P18!=null) {
                        $v=$Permisos->P18;
                    }else if ($Permisos->P19!=null) {
                        $v=$Permisos->P19;
                    }else if ($Permisos->P20!=null) {
                        $v=$Permisos->P20;
                    }else if ($Permisos->P21!=null) {
                        $v=$Permisos->P21;
                    }else if ($Permisos->P22!=null) {
                        $v=$Permisos->P22;
                    }else if ($Permisos->P23!=null) {
                        $v=$Permisos->P23;
                    }else if ($Permisos->P24!=null) {
                        $v=$Permisos->P24;
                    }else if ($Permisos->P25!=null) {
                        $v=$Permisos->P25;
                    }else if ($Permisos->P26!=null) {
                        $v=$Permisos->P26;
                    }else if ($Permisos->P27!=null) {
                        $v=$Permisos->P27;
                    }else if ($Permisos->P28!=null) {
                        $v=$Permisos->P28;
                    }else if ($Permisos->P29!=null) {
                        $v=$Permisos->P29;
                    }else if ($Permisos->P30!=null) {
                        $v=$Permisos->P30;
                    }else if ($Permisos->P31!=null) {
                        $v=$Permisos->P31;
                    }else if ($Permisos->P32!=null) {
                        $v=$Permisos->P32;
                    }else if ($Permisos->P33!=null) {
                        $v=$Permisos->P33;
                    }else if ($Permisos->P34!=null) {
                        $v=$Permisos->P34;
                    }else if ($Permisos->P35!=null) {
                        $v=$Permisos->P35;
                    }else if ($Permisos->P36!=null) {
                        $v=$Permisos->P36;
                    }else if ($Permisos->P37!=null) {
                        $v=$Permisos->P37;
                    }else if ($Permisos->P38!=null) {
                        $v=$Permisos->P38;
                    }else if ($Permisos->P39!=null) {
                        $v=$Permisos->P39;
                    }else if ($Permisos->P40!=null) {
                        $v=$Permisos->P40;
                    }else if ($Permisos->P41!=null) {
                        $v=$Permisos->P41;
                    }else if ($Permisos->P42!=null) {
                        $v=$Permisos->P42;
                    }else if ($Permisos->P43!=null) {
                        $v=$Permisos->P43;
                    }else if ($Permisos->P44!=null) {
                        $v=$Permisos->P44;
                    }else if ($Permisos->P45!=null) {
                        $v=$Permisos->P45;
                    }else if ($Permisos->P46!=null) {
                        $v=$Permisos->P46;
                    }else if ($Permisos->P47!=null) {
                        $v=$Permisos->P47;
                    }else if ($Permisos->P48!=null) {
                        $v=$Permisos->P48;
                    }else if ($Permisos->P49!=null) {
                        $v=$Permisos->P49;
                    }else if ($Permisos->P50!=null) {
                        $v=$Permisos->P50;
                    }else if ($Permisos->P51!=null) {
                        $v=$Permisos->P51;
                    }else if ($Permisos->P52!=null) {
                        $v=$Permisos->P52;
                    }else if ($Permisos->P53!=null) {
                        $v=$Permisos->P53;
                    }else if ($Permisos->P54!=null) {
                        $v=$Permisos->P54;
                    }else if ($Permisos->P55!=null) {
                        $v=$Permisos->P55;
                    }else if ($Permisos->P56!=null) {
                        $v=$Permisos->P56;
                    }else if ($Permisos->P57!=null) {
                        $v=$Permisos->P57;
                    }else if ($Permisos->P58!=null) {
                        $v=$Permisos->P58;
                    }else if ($Permisos->P59!=null) {
                        $v=$Permisos->P59;
                    }else if ($Permisos->P60!=null) {
                        $v=$Permisos->P60;
                    }else if ($Permisos->P61!=null) {
                        $v=$Permisos->P61;
                    }
                    //return $Permisos;
                }
                //return null;
                return array("Respuesta"=>$v);
            }
        ],
        'permisos'=>[
            'type'=>Type::listOf($permisosType),
            'resolve'=>function($root,$args){
                $Permisos =Permisos::get()->toArray();
                return $Permisos;
            }
        ],
        'permiso'=>[
            'type'=>$permisosType,
            'args'=>[
                'Rango'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $Permisos =Permisos::find($args["Rango"])->toArray();
                if ($Permisos==null) {
                    return null;
                }
                return $Permisos;
            }
        ],
        'guardias_suc_empr'=>[
            'type'=>$empresaguardiasucursalType,
            'args'=>[
                'Sucursal'=>Type::nonNull(Type::int()),
                'Empresa'=>Type::nonNull(Type::int()),
                'Horario'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $EmpresaGuardiaSucursal=EmpresaGuardiaSucursal::where('ID',$args["Horario"])->where('Sucursal',$args["Sucursal"])->where('Empresa',$args["Empresa"])->first();
                return $EmpresaGuardiaSucursal;
            }
        ],
        'control_diarios_reporte'=>[
            'type'=>Type::listOf($ControlDiarioType),
            'args'=>[
                'Sucursal'=>Type::nonNull(Type::int()),
                'Empresa'=>Type::nonNull(Type::int()),
                'M'=>Type::nonNull(Type::int()),
                'Y'=>Type::nonNull(Type::int()),
                'Check'=>Type::nonNull(Type::boolean())
            ],
            'resolve'=>function($root,$args){
                if ($args["Check"]) {
                    $ControlDiario = ControlDiario::where('Empresa', $args["Empresa"])->
                    where( ControlDiario::raw('YEAR(Fecha)'), '=', $args["Y"] )->
                    where( ControlDiario::raw('MONTH(Fecha)'), '=', $args["M"] )->get();
                }else{
                    $ControlDiario = ControlDiario::where('Sucursal', $args["Sucursal"])->
                    where('Empresa', $args["Empresa"])->
                    where( ControlDiario::raw('YEAR(Fecha)'), '=', $args["Y"] )->
                    where( ControlDiario::raw('MONTH(Fecha)'), '=', $args["M"] )->get();
                }
                //$ControlDiario=ControlDiario::get()->toArray();
                return $ControlDiario;
            }
        ],
        'seguimiento_reporte'=>[
            'type'=>Type::listOf($seguimientoType),
            'args'=>[
                'Tipo'=>Type::nonNull(Type::string()),
                'Estado'=>Type::nonNull(Type::string()),
                'M'=>Type::nonNull(Type::int()),
                'Y'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                if ($args["Tipo"]=="Fecha de Creacion") {
                    $Seguimiento = Seguimiento::where('Eliminado',0)->where('Estado', $args["Estado"])->
                        where( Seguimiento::raw('YEAR(FechaCreacion)'), '=', $args["Y"] )->
                        where( Seguimiento::raw('MONTH(FechaCreacion)'), '=', $args["M"] )->get();
                }else if ($args["Tipo"]=="Fecha de Inicio"){
                    $Seguimiento = Seguimiento::where('Eliminado',0)->where('Estado', $args["Estado"])->
                        where( Seguimiento::raw('YEAR(FechaInicio)'), '=', $args["Y"] )->
                        where( Seguimiento::raw('MONTH(FechaInicio)'), '=', $args["M"] )->get();
                }else if($args["Tipo"]=="Fecha de Finalizacion") {
                    $Seguimiento = Seguimiento::where('Eliminado',0)->where('Estado', $args["Estado"])->
                        where( Seguimiento::raw('YEAR(FechaFin)'), '=', $args["Y"] )->
                        where( Seguimiento::raw('MONTH(FechaFin)'), '=', $args["M"] )->get();
                }else if($args["Tipo"]=="Eliminado") {
                    $Seguimiento = Seguimiento::where('Eliminado',1)->
                        where( Seguimiento::raw('YEAR(FechaEliminado)'), '=', $args["Y"] )->
                        where( Seguimiento::raw('MONTH(FechaEliminado)'), '=', $args["M"] )->get();
                }
                return $Seguimiento;
            }
        ],
        'productos_disponibles'=>[
            'type'=>Type::listOf($ProductoType),
            'args'=>[
                'Lote'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $ProductoLote = ProductoLote::where('Lote', $args["Lote"])->whereHas('producto', function($q){
                    $q->where('Cantidad', '>=', 1);
                })->get()->toArray();
                $Productos=[];
                foreach ($ProductoLote as $valor) {
                    $v=false;
                    if (count($Productos)>0) {
                        foreach($Productos as $f){
                            if ($f["ID"]==$valor["Producto"]) {
                                $v=true;
                            }
                        }
                    }
                    if ($v==false) {
                        $Productos[]=Producto::find($valor["Producto"]);
                    }
                }
                return $Productos;
            }
        ],
        'ListaSalidas'=>[
            'type'=>Type::listOf($ProductoLoteType),
            'resolve'=>function($root,$args){
                $ProductoLote =ProductoLote::get()->toArray();
                return $ProductoLote;
            }
        ],
        'tareas_reporte'=>[
            'type'=>Type::listOf($TareasType),
            'args'=>[
                'Tipo'=>Type::nonNull(Type::string()),
                'Estado'=>Type::nonNull(Type::string()),
                'M'=>Type::nonNull(Type::int()),
                'Y'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                if ($args["Tipo"]=="Fecha de Creacion") {
                    $Tareas = Tareas::where('Eliminado',0)->where('Estado', $args["Estado"])->
                        where( Tareas::raw('YEAR(FechaCreacion)'), '=', $args["Y"] )->
                        where( Tareas::raw('MONTH(FechaCreacion)'), '=', $args["M"] )->get();
                }else if ($args["Tipo"]=="Fecha Pospuesta"){
                    $Tareas = Tareas::where('Eliminado',0)->where('Estado', $args["Estado"])->
                        where( Tareas::raw('YEAR(FechaPospuesta)'), '=', $args["Y"] )->
                        where( Tareas::raw('MONTH(FechaPospuesta)'), '=', $args["M"] )->get();
                }else if($args["Tipo"]=="Eliminado") {
                    $Tareas = Tareas::where('Eliminado',1)->
                        where( Tareas::raw('YEAR(FechaEliminado)'), '=', $args["Y"] )->
                        where( Tareas::raw('MONTH(FechaEliminado)'), '=', $args["M"] )->get();
                }
                return $Tareas;
            }
        ],
        'VacacionesDias'=>[
            'type'=>Type::listOf($VacacionesDiasType),
            'resolve'=>function($root,$args){
                $VacacionesDias=VacacionesDias::get()->toArray();
                return $VacacionesDias;
            }
        ],
        'Trabajadores'=>[
            'type'=>Type::listOf($TrabajadoresType),
            'resolve'=>function($root,$args){
                $Trabajadores=Trabajadores::get()->toArray();
                return $Trabajadores;
            }
        ],
        'Encargados'=>[
            'type'=>Type::listOf($TrabajadoresType),
            'resolve'=>function($root,$args){
                $Trabajadores=Trabajadores::where('Puesto',"Encargado Seguridad")->orWhere('Puesto',"Analista Monitoreo")->orWhere('Puesto',"Analista Seguridad")->get()->toArray();
                return $Trabajadores;
            }
        ],
        'VacacionesPersonas'=>[
            'type'=>Type::listOf($VacacionPersonaType),
            'resolve'=>function($root,$args){
                $VacacionPersona=VacacionPersona::get()->toArray();
                return $VacacionPersona;
            }
        ],
        'TablasControles'=>[
            'type'=>Type::listOf($TablaControlType),
            'resolve'=>function($root,$args){
                $TablaControl=TablaControl::get()->toArray();
                return $TablaControl;
            }
        ],
        'TablasControlesEn'=>[
            'type'=>Type::listOf($TablaControlType),
            'args'=>[
                'Anho'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $TablaControl=TablaControl::where('Anho', $args["Anho"])->get()->toArray();
                return $TablaControl;
            }
        ],
        'Controles'=>[
            'type'=>Type::listOf($ControlType),
            'resolve'=>function($root,$args){
                $Control=Control::get()->toArray();
                return $Control;
            }
        ],
        'ControlesFecha'=>[
            'type'=>Type::listOf($ControlType),
            'args'=>[
                'Anho'=>Type::nonNull(Type::int()),
                'Mes'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $Control=Control::whereMonth('Fecha',$args["Mes"])->whereYear('Fecha', $args["Anho"])->get()->toArray();
                return $Control;
            }
        ],
        'ControlesEnDe'=>[
            'type'=>Type::listOf($ControlType),
            'args'=>[
                'De'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $Control=Control::where('Trabajador',$args["De"])->whereYear('Fecha', date("Y"))->get()->toArray();
                return $Control;
            }
        ],
        'ControlesDE'=>[
            'type'=>Type::listOf($ControlType),
            'args'=>[
                'De'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $Control=Control::where('Trabajador',$args["De"])->get()->toArray();
                return $Control;
            }
        ],
        'ControlesDeDiscos'=>[
            'type'=>Type::listOf($ControlDiscosType),
            'resolve'=>function($root,$args){
                $ControlDiscos=ControlDiscos::get()->toArray();
                return $ControlDiscos;
            }
        ],
        'ControlesDeDiscosLIKE'=>[
            'type'=>Type::listOf($ControlDiscosType),
            'args'=>[
                'Tipo'=>Type::nonNull(Type::string()),
                'Busqueda'=>Type::nonNull(Type::string())
            ],
            'resolve'=>function($root,$args){
                if (isset($args["Tipo"]) && isset($args["Busqueda"])) {
                    $ControlDiscos=[];
                    if ($args["Tipo"]=="Requerimiento Fiscal") {
                        $ControlDiscos=ControlDiscos::where('ReqFiscal', 'like', '%'.$args["Busqueda"].'%')->get()->toArray();
                    }else if ($args["Tipo"]=="Codigo Sucursal") {
                        $sucursales=Sucursal::select('ID')->where('CodigoSucursal', 'like', '%'.$args["Busqueda"].'%')->get();
                        $ids=array();
                        foreach($sucursales as $s){
                            $ids[]=$s->ID;
                        }
                        $ControlDiscos=ControlDiscos::whereIn('Sucursal', $ids)->get()->toArray();
                    }
                    return $ControlDiscos;
                }else{
                    return [];
                }
            }
        ],
        'SalidasDeDiscos'=>[
            'type'=>Type::listOf($SalidaDiscosType),
            'resolve'=>function($root,$args){
                $SalidaDiscos=SalidaDiscos::get()->toArray();
                return $SalidaDiscos;
            }
        ],
        'Areas'=>[
            'type'=>Type::listOf($AreaType),
            'resolve'=>function($root,$args){
                $data =Area::get()->toArray();
                return $data;
            }
        ],
        'Area'=>[
            'type'=>$AreaType,
            'args'=>[
                'ID'=>Type::nonNull(Type::int())
            ],
            'resolve'=>function($root,$args){
                $data =Area::find($args["ID"])->toArray();
                if ($data==null) {
                    return null;
                }
                return $data;
            }
        ],
    ]
]);
?>