<?php

class Ventas_FacturaController extends Zend_Controller_Action {

    public function init() {
       
    }

    public function indexAction() {
        //$this->_helper->viewRenderer->setNoRender ( true );
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
                ->join(array('P' => 'CLIENTE'), 'C.COD_CLIENTE = P.COD_CLIENTE');
                

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
							'C.KAR_PRECIO_PRODUCTO',
							'C.COD_MOZO',
							'C.FACT_NRO',
                	  		'C.ESTADO'))
                ->joinLeft(array('P' => 'CLIENTE'), 'C.COD_CLIENTE = P.COD_CLIENTE')
                ->join(array('M' => 'PRODUCTO'), 'C.COD_PRODUCTO = M.COD_PRODUCTO')
                ->where("C.ESTADO = ?", 'PE')
                ->where("C.FACT_NRO = ?", 0);


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
								'KAR_CANT_PRODUCTO'=> $value ['KAR_CANT_PRODUCTO'],
								'KAR_PRECIO_PRODUCTO'=> $value ['KAR_PRECIO_PRODUCTO'],
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
       try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
           // insertamos la cabecera
	            $data = array(
					'FAC_NRO' =>0,
					'COD_CLIENTE' =>$dataVenta->codcliente,
					'FAC_FECHA_EMI' =>$dataVenta->fechaEmision,
					'FAC_MES' =>(int)(substr($dataVenta->fechaEmision,6,2)),
					'FAC_ANO' =>(int)(substr($dataVenta->fechaEmision,1,4)),
					'FAC_FECH_VTO' =>$dataVenta->fechaVencimiento,
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
						'FAC_DET_ITEM'=>$i++,
						'COD_PRODUCTO'=>$fila->COD_PRODUCTO,
						'FAC_DET_TOTAL'=>$fila->KAR_PRECIO_PRODUCTO
		            );
		            $insertDetalle = $db->insert('FACTURA_DETALLE', $data);
		            if($fila->COD_KARRITO == 0){
		            	$dataKarrito = array(
		            		'COD_KARRITO'=>0,
							'KAR_FECH_MOV'=>$dataVenta->fechaEmision,
							'COD_CLIENTE'=>$dataVenta->codcliente,
							'COD_MESA'=>1,
							'COD_PRODUCTO'=>$fila->COD_PRODUCTO,
							'KAR_CANT_PRODUCTO'=>$fila->KAR_CANT_PRODUCTO,
							'KAR_PRECIO_PRODUCTO'=>$fila->KAR_PRECIO_PRODUCTO,
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
                        $saldo_producto = ($resultado_select[0]['SALDO_STOCK']>0)?$resultado_select[0]['SALDO_STOCK']:0;
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
		            } else {
		            	$dataKarrito = array(
			                'FACT_NRO'=>$factura_nro,
	                		'ESTADO'=>'PA'
		           	 	);
		            	$where = "COD_KARRITO= " . $fila->COD_KARRITO;
		            	$updatekarrito = $db->update('KARRITO', $dataKarrito, $where);  
		            }
                	  
                }
                $db->commit();
             
             echo json_encode(array("result" => "EXITO"));
       } catch (Exception $e) {
       		$db->rollBack();
            echo json_encode(array("result" => "ERROR", "code" => $e->getCode(),"mensaje" => $e->getMessage()));
            
        }
   }
   
public function modaleditarAction() {
//        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $getNumeroInterno = json_decode($this->getRequest()->getParam("NumeroInterno"));
//        $getNumeroInterno = trim($NumeroInterno["NumeroInterno"]);

        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('K' => 'KARRITO'), 
                array(	'K.COD_KARRITO',
						'K.KAR_FECH_MOV',
						'K.COD_CLIENTE',
                		'C.CLIENTE_DES',
						'K.COD_MESA',
						'K.COD_PRODUCTO',
                		'P.PRODUCTO_DESC',
						'K.KAR_CANT_PRODUCTO',
						'K.KAR_PRECIO_PRODUCTO',
						'K.COD_MOZO',
						'K.FACT_NRO',
						'K.ESTADO'))
               	->joinInner(array('P' => 'PRODUCTO'), 'K.COD_PRODUCTO = P.COD_PRODUCTO')
                ->joinInner(array('C' => 'CLIENTE'), 'C.COD_CLIENTE = K.COD_CLIENTE')
                ->where("K.FACT_NRO = ?", $getNumeroInterno);
//        print_r($select);
//        die();
        $result = $db->fetchAll($select);
        echo json_encode($result);
    }

    public function anularventaAction(){
        $this->_helper->viewRenderer->setNoRender(true);
        $codigoFactura = json_decode($this->getRequest()->getParam("codigofactura"));
        
        try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();

                     $data = array(
                        'ESTADO' => 'A'
                        );  
                        $where = "FAC_NRO = " . $codigoFactura;
                        $upd = $db->update('FACTURA', $data, $where);
                        $db->commit();
                echo json_encode(array("result" => "EXITO"));
            }catch (Exception $e) {
                echo json_encode(array("result" => "ERROR","errotname" => $e->getMessage()));
                $db->rollBack();
            
            }
    }
  
}