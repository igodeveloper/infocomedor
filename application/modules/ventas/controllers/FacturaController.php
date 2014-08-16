<?php

class Ventas_FacturaController extends Zend_Controller_Action {

    public function init() {
        $parametrosNamespace = new Zend_Session_Namespace('parametros');
        $parametrosNamespace->unlock();
         if(!$parametrosNamespace->username){
            $r = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
            $r->gotoUrl('/menus/menu')->redirectAndExit();
        }
    }

    public function indexAction() {
        //$this->_helper->viewRenderer->setNoRender ( true );
    }
public function imprimirfacturaAction(){
        try{
            $var_nombrearchivo = 'factura';
            $path_tmp = './tmp/';
            $orientation='P';
            $unit='mm';
            $format='A4';    
            $parametros = 60;
            if(!isset($pdf))
              $pdf= new PDFFacturaPY($orientation,$unit,$format,$parametros);
            $pdf->AliasNbPages();
            $pdf->AddPage();
            $pdf->Body();

            $file = basename($var_nombrearchivo."_".date('Ymdhis'));
            $file .= '.pdf';
            //Guardar el PDF en un fichero
            $pdf->Output($path_tmp.$file, 'F');
            $pdf->close();
            unset($pdf);    
        }catch( Exception $e ) {
	    	echo json_encode(array("result" => "ERROR","mensaje"=>$e->getCode()));
		$db->rollBack();
	}    
}

public function buscarAction() {

        $this->_helper->viewRenderer->setNoRender(true);
        $datos = $this->getRequest()->getParam("dataJsonBusqueda");
        $Obj = json_decode($datos);
//        print_r($Obj);
//        die();
        $parametrosNamespace = new Zend_Session_Namespace('parametros');
        $parametrosNamespace->unlock();

        $cantidadFilas = $this->getRequest()->getParam("rows");

        if (!isset($cantidadFilas)) {
            $cantidadFilas = 30;
        }

        $parametrosNamespace->cantidadFilas = $cantidadFilas;

        $page = $this->getRequest()->getParam("page");
        if (!isset($page)) {
            $page = 1;
        }



        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('C' => 'FACTURA'), array(
                	'C.FAC_NRO',
                	'C.CONTROL_FISCAL',
                    'P.CLIENTE_DES',
                    'C.COD_CLIENTE',
                    'C.FAC_FECHA_EMI',
                    'C.FAC_FECH_VTO',
                    'C.FAC_MES',
                    'C.FAC_ANO',
                    'C.FAC_MONTO_TOTAL',
                	'C.ESTADO'))
                ->join(array('P' => 'CLIENTE'), 'C.COD_CLIENTE = P.COD_CLIENTE')
                ->order(array('C.FAC_NRO DESC'));
                

        if ($Obj != null) {
            //print_r($Obj);
            //die();

            if ($Obj->codcliente != null) {
//                            die($Obj->codproveedor);
                $select->where("C.COD_CLIENTE = ?", $Obj->codcliente);
            }
            if ($Obj->namecliente != null) {
                $select->where("P.CLIENTE_DES = ?", $Obj->namecliente);
            }
            if ($Obj->codigointerno != null) {
                $select->where("C.FAC_NRO = ?", $Obj->codigointerno);
            }
            if ($Obj->controlfiscal != null) {
                $select->where("C.CONTROL_FISCAL = ?", $Obj->controlfiscal);
            }
            if ($Obj->fechaemision != null) {
                $select->where("C.FAC_FECHA_EMI >= ?", $Obj->fechaemision);
            }
            if ($Obj->fechavencimiento != null) {
                $select->where("C.FAC_FECH_VTO >= ?", $Obj->fechavencimiento);
            }
            if ($Obj->estado != - 1) {
                $select->where("C.ESTADO = ?", $Obj->estado);
            }
//                    print_r($select);
            $result = $db->fetchAll($select);
        } else {
            $result = $db->fetchAll($select);
        }

//        die($select);
//       $result = $db->fetchAll($select);
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/bootstrap.js');
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/gridFactura.js');
        $pagina = self::obtenerPaginas($result, $cantidadFilas, $page);
        echo $this->_helper->json($pagina);
    }
  public function productofinaldataAction() {
//        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $datos = $this->getRequest()->getParam("parametro");
        $getvalue = trim($datos["value"]);
        $getreference = $datos["reference"];
        switch ($getreference) {
            case 'cod':
                $where = "P.COD_PRODUCTO=" . $getvalue;
                break;
            case 'descripcion':
                $where = "P.PRODUCTO_DESC= '" . $getvalue . "'";
                break;
        }
        // esta atado a materia prima (1     Materia Prima)
        //$where = "P.COD_PRODUCTO_TIPO <> 1 ";


        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('P' => 'PRODUCTO'), array('P.COD_PRODUCTO', 'P.PRODUCTO_DESC', 
                                                       'P.COD_UNIDAD_MEDIDA', 'U.ISO_UNIDAD_MEDIDA','S.SALDO_STOCK','P.PRECIO_VENTA','P.COD_IMPUESTO'))
                ->distinct(true)
                ->join(array('U' => 'UNIDAD_MEDIDA'), 'P.COD_UNIDAD_MEDIDA = U.COD_UNIDAD_MEDIDA', array())
                ->joinLeft(array('S' => 'STOCK'), 'S.COD_PRODUCTO = P.COD_PRODUCTO', array())
                ->where($where);
        $result = $db->fetchAll($select);

        foreach ($result as $value) {
            $descripcionProducto = utf8_encode(trim($value ['PRODUCTO_DESC']));
            $codProducto = $value ['COD_PRODUCTO'];
            $uniMedidaCod = $value ['COD_UNIDAD_MEDIDA'];
            $uniMedidaDesc = utf8_encode(trim($value ['ISO_UNIDAD_MEDIDA']));
            $saldo_producto = $value ['SALDO_STOCK'];
             $precio_venta = $value ['PRECIO_VENTA'];
             $COD_IMPUESTO = $value ['COD_IMPUESTO'];
            $option = array("cod" => $codProducto, "descripcion" => $descripcionProducto, 
                            "unimedcod" => $uniMedidaCod, "unimeddesc" => $uniMedidaDesc, "saldo" => $saldo_producto, 
                            "precioventa" =>$precio_venta, "COD_IMPUESTO" =>$COD_IMPUESTO );
        }
        echo json_encode($option);
    }    
private function obtenerPaginas($result, $cantidadFilas, $page) {
        $this->_paginator = Zend_Paginator::factory($result);
        $this->_paginator->setItemCountPerPage($cantidadFilas);
        $this->_paginator->setCurrentPageNumber($page);
        $pagina ['rows'] = array();
        foreach ($this->_paginator as $item) {
			 if($item['ESTADO']=='P'){
			 	$estado_Fac = 'PENDIENTE';
			 }
			 if ($item['ESTADO']=='A'){
			 	$estado_Fac = 'ANULADO';
			 }
			 if ($item['ESTADO']=='C') {
			 	$estado_Fac = 'CANCELADO';
			 }
            $arrayDatos ['cell'] = array(
                null,
                null,
                $item['COD_CLIENTE'],
				$item['CLIENTE_DES'],
				$item['CONTROL_FISCAL'],
                $item['FAC_NRO'],
				$item['FAC_FECHA_EMI'],
				$item['FAC_FECH_VTO'],
//				$item['FAC_MES'],
//				$item['FAC_ANO'],
				$item['FAC_MONTO_TOTAL'],
				$estado_Fac   
            );
            $arrayDatos ['columns'] = array(
                "modificar",
                "pago",
				"COD_CLIENTE",
       			"CLIENTE_DES",
            	"CONTROL_FISCAL",
            	"FAC_NRO",			
				"FAC_FECHA_EMI",
				"FAC_FECH_VTO",
//				"FAC_MES",
//				"FAC_ANO",
				"FAC_MONTO_TOTAL",
				"ESTADO"
            );
            array_push($pagina ['rows'], $arrayDatos);
        }

        if ($cantidadFilas == 0)
            $cantidadFilas = 10;

        $pagina ['records'] = count($result);
        $pagina ['page'] = $page;
        $pagina ['total'] = ceil($pagina ['records'] / $cantidadFilas);

        if ($pagina['records'] == 0) {
            $pagina ['mensajeSinFilas'] = true;
        }


        return $pagina;
    }

    
	public function getkarritodataAction() {

        $this->_helper->viewRenderer->setNoRender(true);
        $Obj = $this->getRequest()->getParam("data");
//        $Obj = json_decode($datos);
//		print_r($Obj);die();
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('C' => 'KARRITO'), 
                	  array('C.COD_KARRITO',
							'C.KAR_FECH_MOV',
							'C.COD_CLIENTE',
                	  		'P.CLIENTE_DES',
							'C.COD_MESA',
							'C.COD_PRODUCTO',
                	  		'M.PRODUCTO_DESC',
                            'C.KAR_CANT_PRODUCTO',
							'C.KAR_CANT_FACTURAR',
                            'C.KAR_PRECIO_PRODUCTO',
                            'C.KAR_PRECIO_FACTURAR',
                            'C.COD_IMPUESTO',
							'C.MONTO_IMPUESTO',
							'C.COD_MOZO',
							'C.FACT_NRO',
                	  		'C.ESTADO'))
                ->joinLeft(array('P' => 'CLIENTE'), 'C.COD_CLIENTE = P.COD_CLIENTE')
                ->join(array('M' => 'PRODUCTO'), 'C.COD_PRODUCTO = M.COD_PRODUCTO')
                ->where("C.ESTADO = ?", 'PE');
                // ->where("C.FACT_NRO = ?", 0);


			if ($Obj['codigocliente'] != null && $Obj['codigomesa'] == null) {	
                $select->where("C.COD_CLIENTE = ?", $Obj['codigocliente']);
            }
            if ($Obj['nombrecliente'] != null) {
                $select->where("P.CLIENTE_DES like '%". $Obj['nombrecliente'] ."%'");
            }
            if ($Obj['codigomesa'] != null && $Obj['codigocliente'] == null && $Obj['nombrecliente'] == null) {
                $select->where("C.COD_MESA = ?", $Obj['codigomesa']);
            }
            $result = $db->fetchAll($select);
        
       	$option = array();
	    foreach ($result as $value) {
	        $option1 = array(	'COD_KARRITO'=> $value ['COD_KARRITO'],
								'KAR_FECH_MOV'=> $value ['KAR_FECH_MOV'],
								'COD_CLIENTE'=> $value ['COD_CLIENTE'],
								'CLIENTE_DES'=> $value ['CLIENTE_DES'],
								'COD_MESA'=> $value ['COD_MESA'],
								'COD_PRODUCTO'=> $value ['COD_PRODUCTO'],
								'PRODUCTO_DESC'=> $value ['PRODUCTO_DESC'],
                                'KAR_CANT_PRODUCTO'=> $value ['KAR_CANT_FACTURAR'],
								'KAR_CANT_FACTURAR'=> $value ['KAR_CANT_FACTURAR'],
                                'KAR_PRECIO_PRODUCTO'=> $value ['KAR_PRECIO_FACTURAR'],
                                'KAR_PRECIO_FACTURAR'=> $value ['KAR_PRECIO_FACTURAR'],
                                'COD_IMPUESTO'=> $value ['COD_IMPUESTO'],
								'MONTO_IMPUESTO'=> $value ['MONTO_IMPUESTO'],
								'COD_MOZO'=> $value ['COD_MOZO'],
								'FACT_NRO'=> $value ['FACT_NRO'],
								'ESTADO'=> $value ['ESTADO']);
	        array_push($option, $option1);
	        }
            if(!$option){
                $option = array('result'=>'void'); 
            }	        
        	echo json_encode($option);
    }
    
    
    public function clientedataAction() {
//        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('P' => 'CLIENTE'))
                ->order(array('P.CLIENTE_DES'));
        $result = $db->fetchAll($select);

        echo json_encode($result);
    }
    
    public function productodataAction() {
//        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('P' => 'PRODUCTO'))
                ->where("P.COD_PRODUCTO_TIPO = ?", 2)
                ->order(array('P.PRODUCTO_DESC'));
//        print_r($select);die();
        $result = $db->fetchAll($select);

        echo json_encode($result);
    }
    
public function validaclientedataAction() {
//        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $datos = $this->getRequest()->getParam("parametro");
        $getvalue = $datos["value"];
        $getreference = $datos["reference"];
        switch ($getreference) {
            case 'cod':
                $where = "P.COD_CLIENTE=" . $getvalue;
                break;
            case 'ruc':
                $where = "P.CLIENTE_RUC=" . $getvalue;
                break;
            case 'name':
                $where = "P.CLIENTE_DES= '" . $getvalue . "'";
                break;
        }

        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('P' => 'CLIENTE'), array('P.COD_CLIENTE', 'P.CLIENTE_DES', 'P.CLIENTE_RUC'))
                ->where($where);
        $result = $db->fetchAll($select);
//        print_r($result);
        $option = array();
        foreach ($result as $value) {
            $razonsocial = utf8_encode(trim($value ['CLIENTE_DES']));
            $codproverdor = $value ['COD_CLIENTE'];
            $rucproveedor = $value ['CLIENTE_RUC'];
            $option = array("cod" => $codproverdor, "name" => $razonsocial, "ruc" => $rucproveedor);
        }
//        echo $option;
        echo json_encode($option);
    }
    
public function guardarAction() {

//		$this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $dataVenta = json_decode($this->getRequest()->getParam("dataVenta"));
        $dataVentaDetalle = json_decode($this->getRequest()->getParam("dataVentaDetalle"));
        $dataVentaPago = json_decode($this->getRequest()->getParam("dataVentaPago"));
       try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
           // insertamos la cabecera
	            $data = array(
					'FAC_NRO' =>0,
					'COD_CLIENTE' =>$dataVenta->codcliente,
					'FAC_FECHA_EMI' =>date('Y-m-d', strtotime($dataVenta->fechaEmision)),
					'FAC_MES' =>(int)(substr($dataVenta->fechaEmision,6,2)),
					'FAC_ANO' =>(int)(substr($dataVenta->fechaEmision,1,4)),
					'FAC_FECH_VTO' =>date('Y-m-d', strtotime($dataVenta->fechaVencimiento)),
					'FAC_MONTO_TOTAL' =>(float)($dataVenta->montoTotal),
					'ESTADO' =>'C',
					'CONTROL_FISCAL' =>$dataVenta->controlFiscal             
	            );
	            $insert = $db->insert('FACTURA', $data);
	            $factura_nro = $db->lastInsertId();
	            $i = 0;
                foreach ($dataVentaDetalle as $fila) {
                    
                 	$data = array(
		                'FAC_NRO'=>$factura_nro,
						'FAC_DET_ITEM'=>++$i,
						'COD_PRODUCTO'=>$fila->COD_PRODUCTO,
                        'FAC_DET_CANTIDAD' =>$fila->KAR_CANT_FACTURAR,
						'FAC_DET_TOTAL'=>$fila->KAR_PRECIO_FACTURAR
		            );
		            $insertDetalle = $db->insert('FACTURA_DETALLE', $data);
                    
                    $dataImpuesto = array(
                        'FAC_NRO'=>$factura_nro,
                        'FAC_IMPUESTO_ITEM'=>$i,
                        'COD_IMPUESTO'=>(int)$fila->COD_IMPUESTO,
                        'FACT_IMP_MONTO' =>(int)$fila->MONTO_IMPUESTO
                    );
                    $insertDetalleImpuesto = $db->insert('FACTURA_IMPUESTO', $dataImpuesto);
                    
		            if($fila->COD_KARRITO == 0){
		            	$dataKarrito = array(
		            		'COD_KARRITO'=>0,
							'KAR_FECH_MOV'=>date('Y-m-d', strtotime($dataVenta->fechaEmision)),
							'COD_CLIENTE'=>$dataVenta->codcliente,
							'COD_MESA'=>1,
							'COD_PRODUCTO'=>$fila->COD_PRODUCTO,
                            'KAR_CANT_PRODUCTO'=>$fila->KAR_CANT_PRODUCTO,
							'KAR_CANT_FACTURAR'=>$fila->KAR_CANT_PRODUCTO,
                            'KAR_PRECIO_PRODUCTO'=>$fila->KAR_PRECIO_PRODUCTO,
                            'KAR_PRECIO_FACTURAR'=>$fila->KAR_PRECIO_PRODUCTO,
                            'COD_IMPUESTO'=>$fila->COD_IMPUESTO,
							'MONTO_IMPUESTO'=>$fila->MONTO_IMPUESTO,
							'COD_MOZO'=>1,
							'FACT_NRO'=>$factura_nro,
							'ESTADO'=>'PA'
		            	);
		            	$insertkarrito = $db->insert('KARRITO', $dataKarrito);

                         // agragamos al stock los productos dados de alta
                        
                        $select = $db->select()
                                ->from(array('S' => 'STOCK'), array('S.COD_PRODUCTO','S.SALDO_STOCK'))
                                ->distinct(true)
                                ->where("S.COD_PRODUCTO = ?", $fila->COD_PRODUCTO);
                        $resultado_select = $db->fetchAll($select);
                        
                        $existe = ($resultado_select[0]['COD_PRODUCTO'] <> null)?$resultado_select[0]['COD_PRODUCTO']:0;
                        $saldo_producto = $resultado_select[0]['SALDO_STOCK'];

                        $data = array(
                            'COD_PRODUCTO' => $fila->COD_PRODUCTO,
                            'SALDO_STOCK' => ($saldo_producto-((float)$fila->KAR_CANT_PRODUCTO)),
                            'STOCK_FECHA_ACTUALIZA' => ( date("Y-m-d H:i:s"))
                        );
                        if($existe == 0){
                            $upd = $db->insert('STOCK', $data);
                        } else {
                            $where = "COD_PRODUCTO= " . $fila->COD_PRODUCTO;
                            $upd = $db->update('STOCK', $data, $where);
                        }
		            } else if($fila->KAR_CANT_FACTURAR==$fila->KAR_CANT_PRODUCTO){

                        $saldo_cant_facturar=($fila->KAR_CANT_PRODUCTO - $fila->KAR_CANT_FACTURAR);
                        $saldo_precio_facturar=($fila->KAR_PRECIO_PRODUCTO - $fila->KAR_PRECIO_FACTURAR);
		            	$dataKarrito = array(

			                'FACT_NRO'=>$factura_nro,
	                		'KAR_CANT_FACTURAR'=>$saldo_cant_facturar,
                            'KAR_PRECIO_FACTURAR'=>$saldo_precio_facturar,
                            'ESTADO'=>'PA'
		           	 	);
		            	$where = "COD_KARRITO= " . $fila->COD_KARRITO;
		            	$updatekarrito = $db->update('KARRITO', $dataKarrito, $where);  
		            }else{
                        //si no se cancela el item se calcula la diferencia 
                        $saldo_cant_facturar=($fila->KAR_CANT_PRODUCTO - $fila->KAR_CANT_FACTURAR);
                        $saldo_precio_facturar=($fila->KAR_PRECIO_PRODUCTO - $fila->KAR_PRECIO_FACTURAR);
                        
                        if ($fila->COD_IMPUESTO == 5) {
                               $impuesto_pendiente = (int)(($saldo_precio_facturar * 5) / 105);
                            }
                        if ($fila->COD_IMPUESTO == 10) {
                             $impuesto_pendiente = (int)(($saldo_precio_facturar * 10) / 110);
                        }
                        $dataKarrito = array(
                            'FACT_NRO'=>$factura_nro,
                            'KAR_CANT_FACTURAR'=>$saldo_cant_facturar,
                            'KAR_PRECIO_FACTURAR'=>$saldo_precio_facturar,
                            'MONTO_IMPUESTO'=>$impuesto_pendiente
                            
                        );
                        $where = "COD_KARRITO= " . $fila->COD_KARRITO;
                        $updatekarrito = $db->update('KARRITO', $dataKarrito, $where);  
                    }
                	  
                }

                foreach ($dataVentaPago as $value) {
                    $data_ingreso = array(
                        'COD_MOV_CAJA' => 0,
                        'COD_CAJA' => (int)$value->CODIGO_CAJA,
                        'FECHA_HORA_MOV' => date('Y-m-d H:i:s'),
                        'MONTO_MOV' => (float)($value->MONTO_PAGO),
                        'COD_TIPO_MOV' => 2,
                        'FACTURA_MOV' => $factura_nro,
                        'TIPO_FACTURA_MOV' => 'V',
                        'OBSERVACION_MOV' => 'Factura Venta: '.$factura_nro,
                        'TIPO_MOV' => $value->FORMA_PAGO
                    );
                    $insertEgreso = $db->insert('MOV_CAJA', $data_ingreso); 

                    $data_pago = array(
                        'COD_PAGO_CLIENTE' => 0,
                        'FAC_NRO' => $factura_nro,
                        'MONTO_PAGO' => $value->MONTO_PAGO,
                        'NRO_CHEQUE' => $value->NRO_CHEQUE,
                        'DES_BANCO' => $value->DES_BANCO,
                        'ESTADO_PAGO' => 'T'
                    );
                    $insertEgreso_pago = $db->insert('PAGO_CLIENTE', $data_pago); 


                }
                $db->commit();
                //imprime factura
                $var_nombrearchivo = 'factura';
                $path_tmp = './tmp/';
                $orientation='P';
                $unit='mm';
                $format='A4';    
                $parametros = $factura_nro;
                if(!isset($pdf))
                  $pdf= new PDFFacturaPY($orientation,$unit,$format,$parametros);
                $pdf->AliasNbPages();
                $pdf->AddPage();
                $pdf->Body();

                $file = basename($var_nombrearchivo."_".date('Ymdhis'));
                $file .= '.pdf';
                //Guardar el PDF en un fichero
                $pdf->Output($path_tmp.$file, 'F');
                $pdf->close();
                unset($pdf);              
                echo json_encode(array("result" => "EXITO","archivo" => $file));
             //echo json_encode(array("result" => "EXITO"));
       } catch (Exception $e) {
       		$db->rollBack();
            echo json_encode(array("result" => "ERROR", "code" => $e->getCode(),"mensaje" => $e->getMessage()));
            
        }
   }
   
public function modaleditarAction() {
//        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $getNumeroInterno = ($this->getRequest()->getParam("NumeroInterno"));
//        $getNumeroInterno = trim($NumeroInterno["NumeroInterno"]);

        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('C' => 'FACTURA_DETALLE'), 
                      array('C.FAC_DET_ITEM',
                            'C.COD_PRODUCTO',
                            'M.PRODUCTO_DESC',
                            'C.FAC_DET_CANTIDAD',
                            'C.FAC_DET_TOTAL'
                           ))
                ->join(array('M' => 'PRODUCTO'), 'C.COD_PRODUCTO = M.COD_PRODUCTO')
                ->where("C.FAC_NRO = ?", $getNumeroInterno);
//        print_r($select);
//        die();
        $result = $db->fetchAll($select);
        $option = array();
        foreach ($result as $value) {
            $option1 = array(   'FAC_DET_ITEM'=> $value ['FAC_DET_ITEM'],
                                'COD_PRODUCTO'=> $value ['COD_PRODUCTO'],
                                'COD_PRODUCTO'=> $value ['COD_PRODUCTO'],
                                'PRODUCTO_DESC'=> $value ['PRODUCTO_DESC'],
                                'FAC_DET_CANTIDAD'=> $value ['FAC_DET_CANTIDAD'],
                                'FAC_DET_TOTAL'=> $value ['FAC_DET_TOTAL']);
            array_push($option, $option1);
            }
            if(!$option){
                $option = array('result'=>'void'); 
            }           
            echo json_encode($option);
    }

    public function anularventaAction(){
        $this->_helper->viewRenderer->setNoRender(true);
        $codigoFactura = json_decode($this->getRequest()->getParam("codigofactura"));
        
        try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();

                $caja_abierta=self::verificacaja($codigoFactura);

                 if($caja_abierta){

                    $data = array(
                        'ESTADO' => 'A'
                    );  
                    $where = "FAC_NRO = " . $codigoFactura;
                    $upd = $db->update('FACTURA', $data, $where);

                    // devolvemos lo facturado a karrito
                    $select_fac_det = $db->select()
                        ->from(array('C' => 'FACTURA_DETALLE'), 
                              array('C.COD_PRODUCTO',
                                    'C.FAC_DET_CANTIDAD',
                                    'C.FAC_DET_TOTAL',
                                    'M.FACT_IMP_MONTO' ))
                        ->join(array('M' => 'FACTURA_IMPUESTO'), 'C.FAC_DET_ITEM = M.FAC_IMPUESTO_ITEM AND C.FAC_NRO = M.FAC_NRO')
                        ->where("C.FAC_NRO = ?", $codigoFactura);
                    $result_fac_det = $db->fetchAll($select_fac_det);
                    
                    foreach ($result_fac_det as $value) {
                        $select_karrito = $db->select()
                            ->from(array('C' => 'KARRITO'), 
                                    array(  'C.COD_KARRITO', 
                                            'C.KAR_CANT_PRODUCTO',
                                            'C.KAR_CANT_FACTURAR',
                                            'C.KAR_PRECIO_FACTURAR',
                                            'C.MONTO_IMPUESTO',
                                            'C.ESTADO'))
                            ->where("C.FACT_NRO = ?", $codigoFactura)
                            ->where("C.COD_PRODUCTO = ?", $value['COD_PRODUCTO']);
                        $result_karrito = $db->fetchAll($select_karrito);
                        
                        if($result_karrito[0]['ESTADO'] == 'PE'){
                            $dataKarrito = array(
                                'KAR_CANT_FACTURAR'=>($result_karrito[0]['KAR_CANT_FACTURAR']+$value['FAC_DET_CANTIDAD']),
                                'KAR_PRECIO_FACTURAR'=>($result_karrito[0]['KAR_PRECIO_FACTURAR']+$value['FAC_DET_TOTAL']),
                                'MONTO_IMPUESTO'=>($result_karrito[0]['MONTO_IMPUESTO']+$value['FACT_IMP_MONTO']),
                                'ESTADO'=>'PE'
                             );
                            $devolver_saldo = false;
                        }else{
                            $dataKarrito = array(
                                'ESTADO'=>'AN'
                             );
                             $devolver_saldo = true;
                        }
                        $where_karrito = "COD_KARRITO = " . $result_karrito[0]['COD_KARRITO'];
                        $updatekarrito = $db->update('KARRITO', $dataKarrito, $where_karrito);  

                        // devolvemos saldo stock
                        if($devolver_saldo){
                            $select_stock = $db->select()
                                ->from(array('S' => 'STOCK'), array('S.COD_PRODUCTO','S.SALDO_STOCK'))
                                ->distinct(true)
                                ->where("S.COD_PRODUCTO = ?", $value['COD_PRODUCTO']);
                            $resultado_stock = $db->fetchAll($select_stock);
                        
                            $saldo_producto = $resultado_stock[0]['SALDO_STOCK'];

                            $data_stock = array(
                                'SALDO_STOCK' => ($saldo_producto+$value['FAC_DET_CANTIDAD']),
                                'STOCK_FECHA_ACTUALIZA' => ( date("Y-m-d H:i:s"))
                            );   
                            $where_stock = "COD_PRODUCTO= " . $value['COD_PRODUCTO'];
                            $upd = $db->update('STOCK', $data_stock , $where_stock);

                        }
                        
                    }
                    // anulamos pagos
                    $data_pago = array('ESTADO_PAGO' => 'A');
                        $where_pag = "FAC_NRO = ".$codigoFactura;
                    $upd_pago = $db->update('PAGO_CLIENTE', $data_pago, $where_pag);

                    $data_mov = array('ESTADO' => 'A');
                        $where_mov = "FACTURA_MOV = ".$codigoFactura. " AND COD_TIPO_MOV = 2";
                    $upd_mov = $db->update('MOV_CAJA', $data_mov, $where_mov);

                    $db->commit();
                    echo json_encode(array("result" => "EXITO"));
                } else{
                    echo json_encode(array("result" => "CERRADA"));
                }
                
            }catch (Exception $e) {
                echo json_encode(array("result" => "ERROR","errotname" => $e->getTrace()));
                $db->rollBack();
            
            }
    }

        public function verificacaja($codigoFactura){

        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                 ->from(array('C' => 'CAJA'), array('COUNT(C.COD_CAJA)'))
                  ->join(array('M' => 'MOV_CAJA'), 'C.COD_CAJA = M.COD_CAJA')
                 ->where("M.FACTURA_MOV = ?", $codigoFactura)
                 ->where("M.COD_TIPO_MOV = ?", 2)
                 ->where("C.FECHA_HORA_CIERRE IS NULL");
        // print_r($select);
        $result = $db->fetchAll($select);
        
        if($result[0]['COUNT(C.COD_CAJA)'] > 0){
           return true;
        }else {
           return false;
        }
    }

        public function ventasusuarioAction() {
        
        $this->_helper->viewRenderer->setNoRender ( true );
        $parametrosNamespace = new Zend_Session_Namespace ( 'parametros' );
        $parametrosNamespace->unlock ();      
        
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                 ->from(array('C' => 'CAJA'), array('distinct(C.COD_USUARIO_CAJA)','COD_CAJA'))
                 ->where("C.COD_USUARIO_CAJA = ?", $parametrosNamespace->cod_usuario)
                 ->where("C.FECHA_HORA_CIERRE IS NULL");
        $result = $db->fetchAll($select);
       
        $arrResult=array("COD_USUARIO_CAJA" => $result[0] ['COD_USUARIO_CAJA'], 
                        "USERNAME" => $parametrosNamespace->username, 
                        "NOMBRE_APELLIDO" => $parametrosNamespace->desc_usuario,
                        "COD_CAJA" => $result[0] ['COD_CAJA']
                        );
        $parametrosNamespace->lock ();
        echo json_encode($arrResult);
        
     }

     public function getcontrolfiscalAction(){
        $this->_helper->viewRenderer->setNoRender ( true );
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                 ->from(array('C' => 'CONTROL_FISCAL'), array('C.NRO_CONTROL_FISCAL'))
                 ->where("C.COD_CONTROL_FISCAL = ?", 1);
        $result = $db->fetchAll($select);
        echo json_encode(array("result" => $result[0]['NRO_CONTROL_FISCAL']));
     }
  
}