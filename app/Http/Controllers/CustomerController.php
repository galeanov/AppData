<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests;


class CustomerController extends Controller
{
    public function index()
{



    Excel::load('prueba4.xlsx', function($reader) {

         $reader->limitRows(2425);

        //  $reader->select(array('CONTRATOS','EGRESO'))->get();
        // recorre las hojas
        //
        $array=[];

        $cont=0;

        $reader->each(function($sheet) use( &$array, &$array2, &$cont) {
           //  dd($sheet);

            $porciones = explode(",",  $sheet["cursos_desaprobados"]);
            //dd($porciones);

            foreach ($porciones as &$valor) {
                $bandera=0;

//necesitas que el primer array sume ya q no entra por q no hay data
                if($cont==0){
                    $bandera=0;
                }else{
                    foreach ($array as &$valorArray) {
                      //  dd($valor);
                        if($valorArray->name==$valor){
                            $bandera=1;
                        }else{

                           // $bandera=0;
                        }
                      //  dd($valorArray->name);
                    }
                }


                if($bandera!=1 ){
                    $cont++;
                    $object = new \stdClass();
                    $object->cont =$cont;

                    $object->name = $valor;
                    array_unshift($array,$object);
                }

                // $object->egresado = $sheet["egresado"];



            }


         //   $object = new \stdClass();
        //    $object->cont =$cont;

          //  $object->name = $nrociclo;
            // $object->egresado = $sheet["egresado"];


           // array_unshift($array,$object);



        });
        //retiro parcial
        asort($array);
        //  dd($array);

        $reader->sheet('Hoja1',function($sheet)  use( &$array) {

            foreach ($array as $valor2x){
                // dd($valor2x->name);
                $sheet->appendRow([
                    $valor2x->name,$valor2x->cont,
                ]);
            }
            //   dd($array);
        }) ;


    })->export('xlsx');

}
}
