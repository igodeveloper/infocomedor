<?php

class Produccion_ProductofinalController extends Zend_Controller_Action {

    public function init() {
        
    }

    public function indexAction() {
        //$this->_helper->viewRenderer->setNoRender ( true );
    }
	
	public function listarAction() {

        $this->_helper->viewRenderer->setNoRender(true);

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
                ->from(array('S' => 'STOCK'), array('S.COD_PRODUCTO',
                    'P.PRODUCTO_DESC','TP.TIPO_PRODUCTO_DESCRIPCION','S.SALDO_STOCK','UM.DESC_UNIDAD_MEDIDA'))
                ->join(array('P' => 'PRODUCTO'), 'S.COD_PRODUCTO = P.COD_PRODUCTO')
                ->join(array('TP' => 'TIPO_PRODUCTO'), 'P.COD_PRODUCTO_TIPO = TP.COD_TIPO_PRODUCTO')
                ->join(array('UM' => 'UNIDAD_MEDIDA'), 'UM.COD_UNIDAD_MEDIDA = P.COD_UNIDAD_MEDIDA');
//        die($select);
        $result = $db->fetchAll($select);
        $pagina = self::obtenerPaginas($result, $cantidadFilas, $page);
        echo $this->_helper->json($pagina);
    }
    
	public function buscarAction() {

        $this->_helper->viewRenderer->setNoRender(true);

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
        $datos = $this->getRequest()->getParam("dataJsonBusqueda");
        $Obj = json_decode($datos);

        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('S' => 'STOCK'), array('S.COD_PRODUCTO',
                    'P.PRODUCTO_DESC','TP.TIPO_PRODUCTO_DESCRIPCION','S.SALDO_STOCK','UM.DESC_UNIDAD_MEDIDA'))
                ->join(array('P' => 'PRODUCTO'), 'S.COD_PRODUCTO = P.COD_PRODUCTO')
                ->join(array('TP' => 'TIPO_PRODUCTO'), 'P.COD_PRODUCTO_TIPO = TP.COD_TIPO_PRODUCTO')
                ->join(array('UM' => 'UNIDAD_MEDIDA'), 'UM.COD_UNIDAD_MEDIDA = P.COD_UNIDAD_MEDIDA');
//        die($select);

		if ($Obj != null) {
		if ($Obj->descripciontipoproducto !== null || $Obj->descripciontipoproducto.length == 0 ) {
                $select->where("upper(TP.TIPO_PRODUCTO_DESCRIPCION) like upper('%".$Obj->descripciontipoproducto."%')");
            }
		 	if ($Obj->descripcionproducto !== null || $Obj->descripcionproducto.length == 0 ) {
                $select->where("upper(P.PRODUCTO_DESC) like upper('%".$Obj->descripcionproducto."%')");
            }
            
//                    
            $result = $db->fetchAll($select);
        } else {
            $result = $db->fetchAll($select);
        }
     
        $pagina = self::obtenerPaginas($result, $cantidadFilas, $page);
        echo $this->_helper->json($pagina);
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
private function obtenerPaginas($result, $cantidadFilas, $page) {
        $this->_paginator = Zend_Paginator::factory($result);
        $this->_paginator->setItemCountPerPage($cantidadFilas);
        $this->_paginator->setCurrentPageNumber($page);
        $pagina ['rows'] = array();
        foreach ($this->_paginator as $item) {
            $arrayDatos ['cell'] = array(
                
                $item['COD_PRODUCTO'],
                $item['PRODUCTO_DESC'],
               
                $item['SALDO_STOCK'],
               	$item['DESC_UNIDAD_MEDIDA'],
               	$item['TIPO_PRODUCTO_DESCRIPCION']
                
            );
            $arrayDatos ['columns'] = array(
              
            	"COD_PRODUCTO",
                "PRODUCTO_DESC",
                
            	"SALDO_STOCK",
            	"DESC_UNIDAD_MEDIDA",
            	"TIPO_PRODUCTO_DESCRIPCION"
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
   public function productvalidationdataAction() {
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

        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('P' => 'PRODUCTO'), array('P.COD_PRODUCTO', 'P.PRODUCTO_DESC', 
                									   'P.COD_UNIDAD_MEDIDA', 'U.ISO_UNIDAD_MEDIDA',
                                                       'S.SALDO_STOCK','P.PRECIO_VENTA'))
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
public function guardarAction() {

//		$this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $data_grilla = json_decode($this->getRequest()->getParam("dataGrilla"));
        $data_grilla_length = json_decode($this->getRequest()->getParam("dataGrillaLength"));
       	
        try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->beginTransaction();
            
            // VERIFICAMOS SI EXISTE EL PRODUCTO PARA ACTUALIZAR O INSERTAR
            foreach ($data_grilla as $fila) {	
           
            	$select = $db->select()
                		->from(array('S' => 'STOCK'), array('S.COD_PRODUCTO','S.SALDO_STOCK'))
                		->distinct(true)
                		->where("S.COD_PRODUCTO = ?", $fila->codproducto);
                $resultado_select = $db->fetchAll($select);
                
                $existe = ($resultado_select[0]['COD_PRODUCTO'] <> null)?$resultado_select[0]['COD_PRODUCTO']:0;
                $saldo_producto = ($resultado_select[0]['SALDO_STOCK']>0)?$resultado_select[0]['SALDO_STOCK']:0;
	            	$data = array(
		                'COD_PRODUCTO' => $fila->codproducto,
		                'SALDO_STOCK' => ($saldo_producto+$fila->cantidad),
		            	'STOCK_FECHA_ACTUALIZA' => ( date("Y-m-d H:i:s"))
	        		);
	        	// SI EL PRODUCTO EXISTE INSERAMOS SI NO ACTUALIZAMOS
      			if($existe == 0){
		            		$upd = $db->insert('STOCK', $data);
				} else {
					$where = "COD_PRODUCTO= " . $fila->codproducto;
					$upd = $db->update('STOCK', $data, $where);
				}
				$resta_stock = self::disminuyereceta($fila->codproducto,$fila->cantidad,$db);
				
            }
            $db->commit();
            echo json_encode(array("result" => "EXITO"));
       } catch (Exception $e) {
       		$db->rollBack();
            echo json_encode(array("result" => "ERROR", "code" => $e->getCode(),"mensaje" => $e->getMessage()));
            
        }
   }
   
   public function disminuyereceta($cod_producto,$cantidad_producida,$db){
            	
   				$select = $db->select()
                		->from(array('P' => 'PRODUCTO'), 
                				array('RD.COD_PRODUCTO','RD.RECETA_DET_CANTIDAD','S.SALDO_STOCK'))
                		->join(array('RD' => 'RECETA_DETALLE'), 'RD.COD_RECETA = P.COD_RECETA')
                		->joinLeft(array('S' => 'STOCK'), 'S.COD_PRODUCTO = RD.COD_PRODUCTO')
                		->where("P.COD_PRODUCTO = ?", $cod_producto);
                $resultado_select = $db->fetchAll($select);
   			$n = 0;
   			if($resultado_select){
	   				foreach ($resultado_select as $fila) {	
	                    $where= array(
			                'COD_PRODUCTO = ?' => $fila['COD_PRODUCTO']
			            );
	                 	$data = array(
			                'COD_PRODUCTO' => $fila['COD_PRODUCTO'],
	                 		'SALDO_STOCK' => ($fila['SALDO_STOCK']-($fila['RECETA_DET_CANTIDAD']*$cantidad_producida)),
			                'STOCK_FECHA_ACTUALIZA' => date("Y-m-d H:i:s")
			            );
			            if($fila['SALDO_STOCK'] == null){
			            	$n = $db->insert('STOCK', $data);
			            } else {
			            	$n  = $db->update('STOCK', $data, $where);
			            }  
	   				}
	//				 
            }
	return $n;
   }
   
    

}