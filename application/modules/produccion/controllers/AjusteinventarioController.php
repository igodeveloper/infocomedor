<?php

class Produccion_AjusteinventarioController extends Zend_Controller_Action {

    public function init() {
         $parametrosNamespace = new Zend_Session_Namespace('parametros');
        $parametrosNamespace->unlock();
            if(!$parametrosNamespace->username){
                $r = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
                $r->gotoUrl('/menus/menu')->redirectAndExit();
            }
        $parametrosNamespace->lock();
        
    }

    public function indexAction() {
        //$this->_helper->viewRenderer->setNoRender ( true );
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
        $parametrosNamespace->lock();
        $datos = $this->getRequest()->getParam("data");
        $Obj = json_decode($datos);

        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('S' => 'INVENTARIO'), 
                    array(
                    'S.COD_INVENTARIO',
                    'S.COD_PRODUCTO',
                    'P.PRODUCTO_DESC',
                    'TP.TIPO_PRODUCTO_DESCRIPCION',
                    'S.INVENTARIO_FECHA',
                    'S.INVENTARIO_ENTRADA',
                    'S.INVENTARIO_SALIDA',
                    'S.INVENTARIO_SALDO',
                    'UM.DESC_UNIDAD_MEDIDA',
                    'S.ESTADO'))
                ->join(array('P' => 'PRODUCTO'), 'S.COD_PRODUCTO = P.COD_PRODUCTO')
                ->join(array('TP' => 'TIPO_PRODUCTO'), 'P.COD_PRODUCTO_TIPO = TP.COD_TIPO_PRODUCTO')
                ->join(array('UM' => 'UNIDAD_MEDIDA'), 'UM.COD_UNIDAD_MEDIDA = P.COD_UNIDAD_MEDIDA');     
                
//        die($select);
                $select->order(array('S.COD_INVENTARIO DESC','S.COD_PRODUCTO'));
        if ($Obj != null) {
            if ($Obj->tipoproducto !== null || $Obj->tipoproducto.length == 0 ) {
                $select->where("upper(TP.TIPO_PRODUCTO_DESCRIPCION) like upper('%".$Obj->tipoproducto."%')");
            }
            if ($Obj->producto !== null || $Obj->producto.length == 0 ) {
                $select->where("upper(P.PRODUCTO_DESC) like upper('%".$Obj->producto."%')");
            }
                  
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
            if($item['ESTADO'] == 'N'){$estado = "NO";}
                else{$estado = "SI";}
            $arrayDatos ['cell'] = array(
                null,
                $item['COD_INVENTARIO'],
                $item['COD_PRODUCTO'],
                $item['PRODUCTO_DESC'],
                $item['TIPO_PRODUCTO_DESCRIPCION'],
                $item['INVENTARIO_FECHA'],
                $item['INVENTARIO_ENTRADA'],
                $item['INVENTARIO_SALIDA'],
                $item['INVENTARIO_SALDO'],
                $item['DESC_UNIDAD_MEDIDA'],
                $estado
            );
            $arrayDatos ['columns'] = array(
                null,
                "COD_INVENTARIO",
                "COD_PRODUCTO",
                "PRODUCTO_DESC",
                "TIPO_PRODUCTO_DESCRIPCION",
                "INVENTARIO_FECHA",
                "INVENTARIO_ENTRADA",
                "INVENTARIO_SALIDA",
                "INVENTARIO_SALDO",
                "DESC_UNIDAD_MEDIDA",
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
	
   
   public function printpdfAction() {
   	 	$this->_helper->viewRenderer->setNoRender(true);
     // include auto-loader class
		require_once 'Zend/Loader/Autoloader.php';
	// register auto-loader
		$loader = Zend_Loader_Autoloader::getInstance();

		try {
			// create PDF
			$pdf = new Zend_Pdf();
			
			// create A4 page
			$page = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_LETTER);
			
			// define font resource
			$font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
			
			//prueba ancho y largo
			$width  = $page->getWidth();
			$height = $page->getHeight();
			
			//Color de la linea
			$page->setLineColor(new Zend_Pdf_Color_Rgb(0,0,0));
			//Linea superior Horizontal
			$page->drawLine(38, $height-38, ($width-38), $height-38);
			//Linea inferior Horizontal
			$page->drawLine(38, 38, ($width-38), 38);
			//left line vertical
			$page->drawLine(38, 38, 38, $height-38);
			//right line vertical
			$page->drawLine($width-38, $height-38, $width-38, 38);
			
			//Tamanho de letra, color, y titulo
			$page->setFont($font, 14)
			->setFillColor(new Zend_Pdf_Color_Rgb(1, 0, 0))
			->drawText('INFOCOMEDOR', 250, $height-75);
			
			// Linea bajo el titulo
			$page->drawLine(50, $height-78, ($width-50), $height-78);
			
			$listado = new Zend_Session_Namespace('listado');
			$listado->unlock();
			$y = 100;
			$i=0;
			// Hacemos la cabecera
			
			$page->setFont($font, 14)
					 ->setFillColor(new Zend_Pdf_Color_Rgb(0, 0, 0))
					 ->drawText('Item', 40, $height-$y)
					 ->drawText('Producto', 90, $height-$y)
					 ->drawText('Inventario', 400, $height-$y);
					 $y = $y+20;
					 
			if($listado->inventario == 0){
				foreach ($listado->data as $fila) {
					$i++;	
					$page->setFont($font, 12)
					 ->setFillColor(new Zend_Pdf_Color_Rgb(0, 0, 0))
					 ->drawText($i.'-', 40, $height-$y)
					 ->drawText($fila->descripcionproducto, 90, $height-$y)
					 ->drawText('_____', 400, $height-$y);
					 $y = $y+20;
				}
			}else{
				$y = $y-20;
				$page->setFont($font, 14)
					 ->setFillColor(new Zend_Pdf_Color_Rgb(0, 0, 0))
					 ->drawText('Existencia', 290, $height-$y)
					 ->drawText('Diferencia', 500, $height-$y);
				$y = $y+20;
				foreach ($listado->data as $fila) {
					$i++;	
					$page->setFont($font, 12)
					 ->setFillColor(new Zend_Pdf_Color_Rgb(0, 0, 0))
					 ->drawText($i.' - ', 40, $height-$y)
					 ->drawText($fila->descripcionproducto, 90, $height-$y)
					 ->drawText($fila->saldo, 290, $height-$y)
					 ->drawText($fila->saldos, 400, $height-$y)
					 ->drawText($fila->diferencia, 500, $height-$y);
					 $y = $y+20;
				}
			
			}
			$listado->lock();
			// add page to document
			$pdf->pages[] = $page;
			$name = 'inventario'. date("Ymd").date("H").date("i").date("s").'.pdf';
	
				foreach($pdf->pages As $key => $page){
					$page->drawText("Page " . ($key+1) . " of " . count($pdf->pages), 260, 50);                      
				}
			header('Content-type: application/pdf');
			$pdf->save($name);
			echo json_encode(array("result" => "EXITO","url" => $name));
		} catch (Zend_Pdf_Exception $e) {
			die ('PDF error: ' . $e->getMessage());  
		} catch (Exception $e) {
			die ('Application error: ' . $e->getMessage());    
		}
	}
   
public function modalinventarioAction() {

//		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
        $nro_inventario = $this->getRequest()->getParam("nro_inventario");
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from(array('S' => 'INVENTARIO'), 
                	array(
                	'S.COD_PRODUCTO',
                    'P.PRODUCTO_DESC',
                	'P.COD_UNIDAD_MEDIDA',
                	'UM.ISO_UNIDAD_MEDIDA',
                	'S.INVENTARIO_ENTRADA',
                	'S.INVENTARIO_SALIDA',
                    'S.INVENTARIO_SALDO'))
                ->join(array('P' => 'PRODUCTO'), 'S.COD_PRODUCTO = P.COD_PRODUCTO')
                ->join(array('TP' => 'TIPO_PRODUCTO'), 'P.COD_PRODUCTO_TIPO = TP.COD_TIPO_PRODUCTO')
                ->join(array('UM' => 'UNIDAD_MEDIDA'), 'UM.COD_UNIDAD_MEDIDA = P.COD_UNIDAD_MEDIDA');
                $select->where("S.COD_INVENTARIO = ?", $nro_inventario); 
        $result = $db->fetchAll($select);
        $option = array();
        foreach ($result as $value) {
            $option1 = array(
                "codproducto" => $value ['COD_PRODUCTO'],
                "descripcionproducto" => $value ['PRODUCTO_DESC'],
                "codUnidadMedida" => $value ['COD_UNIDAD_MEDIDA'],
                "unidadmedida" => $value ['ISO_UNIDAD_MEDIDA'],
                "saldo" => $value ['INVENTARIO_ENTRADA'],
            	"saldos" => $value ['INVENTARIO_SALIDA'],
                "DIFERENCIA" => $value['INVENTARIO_SALDO']
            );
            array_push($option, $option1);
        }

        echo json_encode($option);
   }
   
    public function guardarAction() {

//      $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $data_grilla = json_decode($this->getRequest()->getParam("dataGrilla"));
        $data_inventario = json_decode($this->getRequest()->getParam("inventario"));
        
        try {
                $db = Zend_Db_Table::getDefaultAdapter();
                $db->beginTransaction();
                $listado = new Zend_Session_Namespace('inventario-ajuste');
                $listado->unlock();
                $list_data = array();
                foreach ($data_grilla as $fila) {
                    $select = $db->select()
                                    ->from(array('S' => 'STOCK'), array('S.COD_PRODUCTO','S.SALDO_STOCK'))
                                    ->distinct(true)
                                    ->where("S.COD_PRODUCTO = ?", $fila->codproducto);
                    $resultado_select = $db->fetchAll($select);
                            
                    $existe = ($resultado_select[0]['COD_PRODUCTO'] <> null)?$resultado_select[0]['COD_PRODUCTO']:0;
                    $saldo_producto = $resultado_select[0]['SALDO_STOCK'];
                    if((float)$fila->saldos == 0){
                        $fila->DIFERENCIA = 0;
                        $fila->saldo = 0;
                    }

                    $data = array(
                        'COD_PRODUCTO' => $fila->codproducto,
                        // 'SALDO_STOCK' => ($saldo_producto-((float)$fila->DIFERENCIA)),
                        'SALDO_STOCK' => (((float)$fila->saldo) - ( (float) $fila->DIFERENCIA) ),
                        'STOCK_FECHA_ACTUALIZA' => ( date("Y-m-d H:i:s"))
                    );
                     
                    if($existe == 0){
                        $upd = $db->insert('STOCK', $data);
                    } else {
                        // print_r($data);
                        $where = "COD_PRODUCTO= " . $fila->codproducto;
                        $upd = $db->update('STOCK', $data, $where);
                    }
                    $marcarInventario = array(
                            'ESTADO'  => 'S'
                    );
                    $where = 'COD_INVENTARIO = '.$data_inventario.' and COD_PRODUCTO = '.$fila->codproducto;
                    $upd = $db->update('INVENTARIO', $marcarInventario, $where);
                    array_push($list_data, $fila);
                }
                $listado->data = $list_data;
            $listado->lock();
            $db->commit();
            echo json_encode(array("result" => "EXITO"));
       } catch (Exception $e) {
            $db->rollBack();
            echo json_encode(array("result" => "ERROR", "code" => $e->getCode(),"mensaje" => $e->getMessage()));
            
        }
   }


}