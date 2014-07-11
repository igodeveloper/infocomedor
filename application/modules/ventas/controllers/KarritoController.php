<?php

class Ventas_KarritoController extends Zend_Controller_Action {

    public function init() {
       
    }

    public function indexAction() {
        //$this->_helper->viewRenderer->setNoRender ( true );
    }
    public function clientdataAction() {
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
                ->order(array('P.PRODUCTO_DESC'));
//        print_r($select);die();
        $result = $db->fetchAll($select);

        echo json_encode($result);
    }
    public function clientvalidatedataAction() {
//        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $datos = $this->getRequest()->getParam("parametro");
        $getvalue = trim($datos["value"]);
        $getreference = $datos["reference"];
        switch ($getreference) {
            case 'documento':
                $where = "P.CLIENTE_RUC=" . $getvalue;
                break;
            case 'nombre':
                $where = "P.CLIENTE_DES= '" . $getvalue . "'";
                break;
        }
        try {
	        $db = Zend_Db_Table::getDefaultAdapter();
	        $select = $db->select()
	                ->from(array('P' => 'CLIENTE'), 
	                	   array('P.COD_CLIENTE', 'P.CLIENTE_RUC', 'P.CLIENTE_DES'))
	                ->where($where);
	        $result = $db->fetchAll($select);
//	        print_r(count($result));die();
	        if( count($result) == 1){
	        	$option = array("cod" => $result [0]['COD_CLIENTE'], 
	        				"name" => trim($result [0]['CLIENTE_DES']), 
	        				"ruc" => $result [0]['CLIENTE_RUC']);
	        } else {
	        	$option = array("error" => 'error');
	        }
        	echo json_encode($option);
        } catch (Exception $e) {
             echo json_encode(array("result" => "ERROR", "mensaje" => $e->getCode(), 
             						"errotname" => $e->getMessage()));
        
        }
    }
//      public function productodataAction() {
// //        $this->_helper->layout->disableLayout();
//         $this->_helper->viewRenderer->setNoRender(true);
//         $db = Zend_Db_Table::getDefaultAdapter();
//         $select = $db->select()
//                 ->from(array('P' => 'PRODUCTO'))
//                 ->order(array('P.PRODUCTO_DESC'));
// //        print_r($select);die();
//         $result = $db->fetchAll($select);

//         echo json_encode($result);
//     }   
    public function guardarAction() {


        $this->_helper->viewRenderer->setNoRender(true);
        $dataCompra = json_decode($this->getRequest()->getParam("dataGrilla"));
        $dataClient = json_decode($this->getRequest()->getParam("dataClient"));
        try {
        	$db = Zend_Db_Table::getDefaultAdapter();
       		$db->beginTransaction();
        	if($dataClient->code > 0 && ($dataClient->table == "null" || $dataClient->table == 0 )){
        			$client = $dataClient->code;
        			$table = 0;
        	} else {
        			$client = 0;
        			$table = $dataClient->table;
        	}
        	foreach ($dataCompra as $value) {
        			 $data = array(
	               		'COD_KARRITO' => 0,
						'KAR_FECH_MOV' => date("Y-m-d H:i:s"),
						'COD_CLIENTE' => $client,
						'COD_MESA' => $table, 
						'COD_PRODUCTO' => $value->codproducto, 
						'KAR_CANT_PRODUCTO' => (float)$value->cantidad,
                        'KAR_CANT_FACTURAR' => (float)$value->cantidad,
                        'KAR_PRECIO_PRODUCTO' => (float)$value->total,
						'KAR_PRECIO_FACTURAR' => (float)$value->total,
						'COD_MOZO' => 1, 
						'FACT_NRO' => 0,
        			 	'ESTADO' => 'PE'
        				);	
		            	$upd = $db->insert('KARRITO', $data);
            
             // agragamos al stock los productos dados de alta
                
                $select = $db->select()
                        ->from(array('S' => 'STOCK'), array('S.COD_PRODUCTO','S.SALDO_STOCK'))
                        ->distinct(true)
                        ->where("S.COD_PRODUCTO = ?", $value->codproducto);
                $resultado_select = $db->fetchAll($select);
                
                $existe = ($resultado_select[0]['COD_PRODUCTO'] <> null)?$resultado_select[0]['COD_PRODUCTO']:0;
                $saldo_producto = $resultado_select[0]['SALDO_STOCK'];
                $data = array(
                    'COD_PRODUCTO' => $value->codproducto,
                    'SALDO_STOCK' => ($saldo_producto-((float)$value->cantidad)),
                    'STOCK_FECHA_ACTUALIZA' => ( date("Y-m-d H:i:s"))
                );
                if($existe == 0){
                    $upd = $db->insert('STOCK', $data);
                } else {
                    $where = "COD_PRODUCTO= " . $value->codproducto;
                    $upd = $db->update('STOCK', $data, $where);
                }
            }
            $db->commit();
                echo json_encode(array("result" => "EXITO"));
		    }catch (Exception $e) {
            	echo json_encode(array("result" => "ERROR","errotname" => $e->getMessage()));
            	$db->rollBack();
		    
		    }
    }
    
	public function listarAction() {

        $this->_helper->viewRenderer->setNoRender(true);
        $datos = $this->getRequest()->getParam("dataJsonBusqueda");
        $Obj = json_decode($datos);

        $parametrosNamespace = new Zend_Session_Namespace('parametros');
        $parametrosNamespace->unlock();
        $cantidadFilas = $this->getRequest()->getParam("rows");
        if (!isset($cantidadFilas)) {
            $cantidadFilas = 10;
        }
        $parametrosNamespace->cantidadFilas = $cantidadFilas;
        $page = $this->getRequest()->getParam("page");
        if (!isset($page)) {
            $page = 1;
        }

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
							'C.COD_MOZO',
							'C.FACT_NRO',
                	  		'C.ESTADO'))
                ->joinLeft(array('P' => 'CLIENTE'), 'C.COD_CLIENTE = P.COD_CLIENTE')
                ->join(array('M' => 'PRODUCTO'), 'C.COD_PRODUCTO = M.COD_PRODUCTO');
//                ->join(array('F' => 'FORMA_PAGO'), 'C.COD_FORMA_PAGO = F.COD_FORMA_PAGO');

//        die($select);
	if ($Obj != null) {
//          print_r($Obj);
//          die();

            if ($Obj->codcliente != null) {
                $select->where("C.COD_CLIENTE = ?", $Obj->codcliente);
            }
            if ($Obj->namecliente != null) {
                $select->where("P.CLIENTE_DES like '%". $Obj->namecliente ."%'");
            }
            if ($Obj->codmesa != null) {
                $select->where("C.COD_MESA = ?", $Obj->codmesa);
            }
            if ($Obj->estado != null) {
                $select->where("C.ESTADO = ?", $Obj->estado);
            }
//            print_r($select);
//            die();
            $result = $db->fetchAll($select);
        } else {
            $result = $db->fetchAll($select);
        }
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/bootstrap.js');
        $this->view->headScript()->appendFile($this->view->baseUrl() . '/js/gridCompra.js');
        $pagina = self::obtenerPaginas($result, $cantidadFilas, $page);
        echo $this->_helper->json($pagina);
    }
    
private function obtenerPaginas($result, $cantidadFilas, $page) {
        $this->_paginator = Zend_Paginator::factory($result);
        $this->_paginator->setItemCountPerPage($cantidadFilas);
        $this->_paginator->setCurrentPageNumber($page);
        $pagina ['rows'] = array();
        foreach ($this->_paginator as $item) {
			$cliente_des = ($item['CLIENTE_DES']== null )?' - ': $item['CLIENTE_DES'];
			$cliente_cod = ($item['COD_CLIENTE']== null )?' - ': $item['COD_CLIENTE'];
			$mesa_cod = ($item['COD_MESA']== 0 )?' - ': $item['COD_MESA'];
			
			if($item['ESTADO'] == 'PE'){
				$estado_factura = 'Pendiente';
			}else if($item['ESTADO'] == 'PA'){
				$estado_factura = 'Facturado';
			} else{
				$estado_factura = 'Anulado';
			}
            $arrayDatos ['cell'] = array(
             	$item['COD_KARRITO'],
				$item['KAR_FECH_MOV'],
				$cliente_cod,
				$cliente_des,
				$mesa_cod,
				$item['COD_PRODUCTO'],
				$item['PRODUCTO_DESC'],
                $item['KAR_CANT_PRODUCTO'],
				$item['KAR_CANT_FACTURAR'],
                $item['KAR_PRECIO_PRODUCTO'],
				$item['KAR_PRECIO_FACTURAR'],
				$item['COD_MOZO'],
				$item['FACT_NRO'],
				$estado_factura
				                
            );
            $arrayDatos ['columns'] = array(
              
                			'COD_KARRITO',
							'KAR_FECH_MOV',
							'COD_CLIENTE',
            				'CLIENTE_DES',
							'COD_MESA',
							'COD_PRODUCTO',
							'PRODUCTO_DESC',
                            'KAR_CANT_PRODUCTO',
            				'KAR_CANT_FACTURAR',
                            'KAR_PRECIO_PRODUCTO',
							'KAR_PRECIO_FACTURAR',
							'COD_MOZO',
							'FACT_NRO',
            				'ESTADO'
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

        $parametrosNamespace = new Zend_Session_Namespace('parametros');
        $parametrosNamespace->unlock();
        $parametrosNamespace->listadoCompras = $pagina ['rows'];
        $parametrosNamespace->lock();

        return $pagina;
    }
    
 public function anularpedidoAction() {


        $this->_helper->viewRenderer->setNoRender(true);
        $codigokarrito = json_decode($this->getRequest()->getParam("codigokarrito"));
        
        try {
        	$db = Zend_Db_Table::getDefaultAdapter();
       		$db->beginTransaction();

        			 $data = array(
        			 	'ESTADO' => 'AN'
        				);	
        				$where = "COD_KARRITO = " . $codigokarrito;
	           					            	$upd = $db->update('KARRITO', $data, $where);
						
                $db->commit();
                echo json_encode(array("result" => "EXITO"));
		    }catch (Exception $e) {
            	echo json_encode(array("result" => "ERROR","errotname" => $e->getMessage()));
            	$db->rollBack();
		    
		    }
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
                                                       'P.COD_UNIDAD_MEDIDA', 'U.ISO_UNIDAD_MEDIDA','S.SALDO_STOCK','P.PRECIO_VENTA'))
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
            $option = array("cod" => $codProducto, "descripcion" => $descripcionProducto, 
                            "unimedcod" => $uniMedidaCod, "unimeddesc" => $uniMedidaDesc, "saldo" => $saldo_producto, "precioventa" =>$precio_venta );
        }
        echo json_encode($option);
    }

   

}