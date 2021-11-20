<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Administracion\Controller;

require_once 'vendor/dompdf/autoload.inc.php';
require_once('vendor/tcpdf/tcpdf.php');

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Administracion\Formularios\VentaForm;
use Administracion\Modelo\Entidades\Venta;
use Dompdf\Dompdf;
use TCPDF;

class VentaController extends AbstractActionController {

    private $ventaDAO;
    private $productoDAO;

    public function getVentaDAO() {
        if (!$this->ventaDAO) {
            $sm = $this->getServiceLocator();
            $this->ventaDAO = $sm->get('Administracion\Modelo\DAO\VentaDAO');
        }
        return $this->ventaDAO;
    }

    public function getProductoDAO() {
        if (!$this->productoDAO) {
            $sm = $this->getServiceLocator();
            $this->productoDAO = $sm->get('Administracion\Modelo\DAO\productoDAO');
        }
        return $this->usuarioDAO;
    }

    function getFormulario($action = '', $onsubmit = '', $idVenta = 0) {
        $required = true;
        if ($action == 'detail' || $action == 'buscar') {
            $required = false;
        }
        $form = new VentaForm($action, $onsubmit, $required);
        if ($idVenta != 0) {
            $ventaOBJ = $this->getVentaDAO()->getVenta($idVenta);
            $form->bind($ventaOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function indexAction() {
        return new ViewModel(array(
            'venta' => $this->getVentaDAO()->getVentas()
        ));
    }

    public function registrarAction() {
        $action = 'registrar';
        $onsubmit = 'return registrarVenta()';
        $form = $this->getFormulario($action, $onsubmit);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $ventaOBJ = new Venta($form->getData());
                $ventaOBJ->setFechaVenta(date('Y-m-d H:i:s'));
                $ventaOBJ->setCantidadVenta(0);
                $ventaOBJ->setValorTotal(0);
                $ventaOBJ->setGanancia(0);
                $productos = array();
                $incremental = (int) $this->params()->fromPost('incremental', 0);
                for ($i = 1; $i < $incremental; $i++) {
                    $cantidad = $this->params()->fromPost("cantidad_$i", 0);
                    $idProducto = $this->params()->fromPost("idProducto_$i", 0);
                    $monto = $this->params()->fromPost("monto_$i", 0);
                    $productos[] = array(
                        'pk_producto_id' => $idProducto,
                        'cantidadVenta' => $cantidad,
                        'monto' => $monto,
                    );
                }
                if (count($productos) > 0) {
                    try {
                        $this->getVentaDAO()->guardar($ventaOBJ, $productos);
                        $this->flashMessenger()->addSuccessMessage('LA VENTA FUE REGISTRADA EN SPARTANS');
                    } catch (\Exception $ex) {
                        $this->flashMessenger()->addErrorMessage('SE HA PRESENTADO UN INCONVENIENTE, LA VENTA NO FUE REGISTRADA EN SPARTANS');
                    }
                } else {
                    $this->flashMessenger()->addErrorMessage('SE HA PRESENTADO UN INCONVENIENTE, NO SE ENCONTRARON PRODUCTOS PARA LA VENTA');
                }
                return $this->redirect()->toUrl('index');
            } else {
                $this->flashMessenger()->addErrorMessage('SE HA PRESENTADO UN INCONVENIENTE, LA INFORMACION A REGISTRAR NO ES VALIDA');
            }
            return $this->redirect()->toUrl('index');
        }
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTerminal(true);
        return $view;
    }

    public function anexarArticuloAction() {
        $idVenta = (int) $this->params()->fromRoute('id1', 0);
        if ($idVenta == 0) {
            return $this->redirect()->toRoute('administracion/default', array(
                        'controller' => 'venta',
                        'action' => 'anexarArticulo',
            ));
        }
        $action = 'detail';
        $onsubmit = 'return false';
        $form = $this->getFormulario($action, $onsubmit, $idVenta);
        $view = new ViewModel(array(
            'form' => $form,
            'productos' => $this->getVentaDAO()->getProductosVentas($idVenta),
        ));
        return $view;
    }

    public function setProductoVentaAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $datos = $request->getPost()->toArray();
            $this->getVentaDAO()->anexarProducto($datos);
            return $this->redirect()->toRoute('administracion/default', array(
                        'controller' => 'venta',
                        'action' => 'anexarArticulo',
                        'id1' => $datos['idVentaSelect'],
            ));
        }
        return $this->redirect()->toRoute('administracion/default', array(
                    'controller' => 'venta',
                    'action' => 'anexarArticulo',
        ));
    }

    public function eliminarArticuloAction() {
        $idVenta = (int) $this->params()->fromQuery('idVenta', 0);
        $idProducto = (int) $this->params()->fromQuery('idProducto', 0);
        if ($idVenta != 0 && $idProducto != 0) {
            try {
                $eliminado = $this->getVentaDAO()->eliminarArticuloVenta($idVenta, $idProducto);
                $this->flashMessenger()->addSuccessMessage('EL ARTICULO FUE ELIMINADO DE LA VENTA');
            } catch (Exception $exc) {
                $this->flashMessenger()->addErrorMessage('EL ARTICULO NO FUE ELIMINADO DE LA VENTA');
            }
        } else {
            $this->flashMessenger()->addErrorMessage('EL ARTICULO NO FUE ELIMINADO DE LA VENTA');
        }
        return new JsonModel(array(
            'eliminado' => $eliminado,
        ));
    }

    public function deleteAction() {
        $idVenta = (int) $this->params()->fromQuery('idVenta', 0);
        try {
            $eliminado = $this->getVentaDAO()->eliminar($idVenta);
            $this->flashMessenger()->addSuccessMessage('LA VENTA FUE ELIMINADA CON EXITO');
        } catch (Exception $exc) {
            $this->flashMessenger()->addErrorMessage('LA VENTA NO FUE ELIMINADA DEL SISTEMA');
        }
        return new JsonModel(array(
            'eliminado' => $eliminado,
        ));
    }

    public function anexarProductoAction() {
        $idProducto = (int) $this->params()->fromQuery('idProducto', 0);
        $productoOBJ = $this->getProductoDAO()->getProductos('pk_producto_id=' . $idProducto);

        $form = new ventaForm();
        $form->get('fk_producto_id')->setValue($productoOBJ[0]->getPk_producto_id());


        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTerminal(TRUE);
        return $view;
    }

    public function imprimirAction() {
        $idVenta = (int) $this->params()->fromRoute('id1', 0);
        if ($idVenta == 0) {
            return;
        }
        $ventaOBJ = $this->getVentaDAO()->getVenta($idVenta);
        $productoVenta = $this->getVentaDAO()->getProductosVentas($idVenta);

        $plantilla = new ViewModel(array(
            'productoVenta' => $productoVenta
        ));
        $marcadores = $ventaOBJ->getArrayCopy();
        $plantilla->setTerminal(true);
        $plantilla->setTemplate('administracion/venta/productoVenta');
        $marcadores['ventaPDF'] = $this->getServiceLocator()->get('viewrenderer')->render($plantilla);
        $this->crearPDF($marcadores);
    }

    public function crearPDF($marcadores = array()) {
        $plantilla = file_get_contents('module/Administracion/view/administracion/venta/imprimir.phtml');
        $html = $this->setMarcadores($plantilla, $marcadores);
// create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Jimmy Núñez');
        $pdf->SetTitle('Factura de venta');
        $pdf->SetSubject('Articulos');
        $pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE . '.', PDF_HEADER_STRING);

// set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__) . '/lang/spa.php')) {
            require_once(dirname(__FILE__) . '/lang/spa.php');
            $pdf->setLanguageArray($l);
        }

// ---------------------------------------------------------
// set a barcode on the page footer
        $pdf->setBarcode(date('Y-m-d H:i:s'));

// set font
        $pdf->SetFont('helvetica', '', 11);

// add a page
        $pdf->AddPage();


// -----------------------------------------------------------------------------

        $pdf->SetFont('helvetica', '', 10);

// define barcode style
        $style = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,
            'fitwidth' => true,
            'cellfitalign' => '',
            'border' => true,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 4
        );



// -----------------------------------------------------------------------------
        $pdf->writeHTML($html, true, false, false, false, '');

// -----------------------------------------------------------------------------
// PRINT VARIOUS 1D BARCODES
// CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9.
        $pdf->Cell(0, 0, 'CODE 39 - ANSI MH10.8M-1983 - USD-3 - 3 of 9', 0, 1);
        $pdf->write1DBarcode('CODE 39', 'C39', '', '', '', 18, 0.4, $style, 'N');

        $pdf->Ln();

// CODE 39 + CHECKSUM
        $pdf->Cell(0, 0, 'CODE 39 + CHECKSUM', 0, 1);
        $pdf->write1DBarcode('CODE 39 +', 'C39+', '', '', '', 18, 0.4, $style, 'N');

        $pdf->Ln();

// CODE 39 EXTENDED
        $pdf->Cell(0, 0, 'CODE 39 EXTENDED', 0, 1);
        $pdf->write1DBarcode('CODE 39 E', 'C39E', '', '', '', 18, 0.4, $style, 'N');

        $pdf->Ln();

// CODE 39 EXTENDED + CHECKSUM
        $pdf->Cell(0, 0, 'CODE 39 EXTENDED + CHECKSUM', 0, 1);
        $pdf->write1DBarcode('CODE 39 E+', 'C39E+', '', '', '', 18, 0.4, $style, 'N');

        $pdf->Ln();

// CODE 93 - USS-93
        $pdf->Cell(0, 0, 'CODE 93 - USS-93', 0, 1);
        $pdf->write1DBarcode('TEST93', 'C93', '', '', '', 18, 0.4, $style, 'N');

        $pdf->Ln();

// Standard 2 of 5
        $pdf->Cell(0, 0, 'Standard 2 of 5', 0, 1);
        $pdf->write1DBarcode('1234567', 'S25', '', '', '', 18, 0.4, $style, 'N');

        $pdf->Ln();

// Standard 2 of 5 + CHECKSUM
        $pdf->Cell(0, 0, 'Standard 2 of 5 + CHECKSUM', 0, 1);
        $pdf->write1DBarcode('1234567', 'S25+', '', '', '', 18, 0.4, $style, 'N');

        $pdf->Ln();

// Interleaved 2 of 5
        $pdf->Cell(0, 0, 'Interleaved 2 of 5', 0, 1);
        $pdf->write1DBarcode('1234567', 'I25', '', '', '', 18, 0.4, $style, 'N');

        $pdf->Ln();

// Interleaved 2 of 5 + CHECKSUM
        $pdf->Cell(0, 0, 'Interleaved 2 of 5 + CHECKSUM', 0, 1);
        $pdf->write1DBarcode('1234567', 'I25+', '', '', '', 18, 0.4, $style, 'N');


// add a page ----------
        $pdf->AddPage();

// CODE 128 AUTO
        $pdf->Cell(0, 0, 'CODE 128 AUTO', 0, 1);
        $pdf->write1DBarcode('CODE 128 AUTO', 'C128', '', '', '', 18, 0.4, $style, 'N');

        $pdf->Ln();

// CODE 128 A
        $pdf->Cell(0, 0, 'CODE 128 A', 0, 1);
        $pdf->write1DBarcode('CODE 128 A', 'C128A', '', '', '', 18, 0.4, $style, 'N');

        $pdf->Ln();

// CODE 128 B
        $pdf->Cell(0, 0, 'CODE 128 B', 0, 1);
        $pdf->write1DBarcode('CODE 128 B', 'C128B', '', '', '', 18, 0.4, $style, 'N');

        $pdf->Ln();

// CODE 128 C
        $pdf->Cell(0, 0, 'CODE 128 C', 0, 1);
        $pdf->write1DBarcode('0123456789', 'C128C', '', '', '', 18, 0.4, $style, 'N');

        $pdf->Ln();

// EAN 8
        $pdf->Cell(0, 0, 'EAN 8', 0, 1);
        $pdf->write1DBarcode('1234567', 'EAN8', '', '', '', 18, 0.4, $style, 'N');

        $pdf->Ln();

// EAN 13
        $pdf->Cell(0, 0, 'EAN 13', 0, 1);
        $pdf->write1DBarcode('1234567890128', 'EAN13', '', '', '', 18, 0.4, $style, 'N');

        $pdf->Ln();

// UPC-A
        $pdf->Cell(0, 0, 'UPC-A', 0, 1);
        $pdf->write1DBarcode('12345678901', 'UPCA', '', '', '', 18, 0.4, $style, 'N');

        $pdf->Ln();

// UPC-E
        $pdf->Cell(0, 0, 'UPC-E', 0, 1);
        $pdf->write1DBarcode('04210000526', 'UPCE', '', '', '', 18, 0.4, $style, 'N');

// add a page ----------
        $pdf->AddPage();

// 5-Digits UPC-Based Extension
        $pdf->Cell(0, 0, '5-Digits UPC-Based Extension', 0, 1);
        $pdf->write1DBarcode('51234', 'EAN5', '', '', '', 18, 0.4, $style, 'N');

        $pdf->Ln();

// 2-Digits UPC-Based Extension
        $pdf->Cell(0, 0, '2-Digits UPC-Based Extension', 0, 1);
        $pdf->write1DBarcode('34', 'EAN2', '', '', '', 18, 0.4, $style, 'N');

        $pdf->Ln();

// MSI
        $pdf->Cell(0, 0, 'MSI', 0, 1);
        $pdf->write1DBarcode('80523', 'MSI', '', '', '', 18, 0.4, $style, 'N');

        $pdf->Ln();

// MSI + CHECKSUM (module 11)
        $pdf->Cell(0, 0, 'MSI + CHECKSUM (module 11)', 0, 1);
        $pdf->write1DBarcode('80523', 'MSI+', '', '', '', 18, 0.4, $style, 'N');

        $pdf->Ln();

// CODABAR
        $pdf->Cell(0, 0, 'CODABAR', 0, 1);
        $pdf->write1DBarcode('123456789', 'CODABAR', '', '', '', 18, 0.4, $style, 'N');

        $pdf->Ln();

// CODE 11
        $pdf->Cell(0, 0, 'CODE 11', 0, 1);
        $pdf->write1DBarcode('123-456-789', 'CODE11', '', '', '', 18, 0.4, $style, 'N');

        $pdf->Ln();

// PHARMACODE
        $pdf->Cell(0, 0, 'PHARMACODE', 0, 1);
        $pdf->write1DBarcode('789', 'PHARMA', '', '', '', 18, 0.4, $style, 'N');

        $pdf->Ln();

// PHARMACODE TWO-TRACKS
        $pdf->Cell(0, 0, 'PHARMACODE TWO-TRACKS', 0, 1);
        $pdf->write1DBarcode('105', 'PHARMA2T', '', '', '', 18, 2, $style, 'N');

// add a page ----------
        $pdf->AddPage();

// IMB - Intelligent Mail Barcode - Onecode - USPS-B-3200
        $pdf->Cell(0, 0, 'IMB - Intelligent Mail Barcode - Onecode - USPS-B-3200', 0, 1);
        $pdf->write1DBarcode('01234567094987654321-01234567891', 'IMB', '', '', '', 15, 0.6, $style, 'N');

        $pdf->Ln();

// POSTNET
        $pdf->Cell(0, 0, 'POSTNET', 0, 1);
        $pdf->write1DBarcode('98000', 'POSTNET', '', '', '', 15, 0.6, $style, 'N');

        $pdf->Ln();

// PLANET
        $pdf->Cell(0, 0, 'PLANET', 0, 1);
        $pdf->write1DBarcode('98000', 'PLANET', '', '', '', 15, 0.6, $style, 'N');

        $pdf->Ln();

// RMS4CC (Royal Mail 4-state Customer Code) - CBC (Customer Bar Code)
        $pdf->Cell(0, 0, 'RMS4CC (Royal Mail 4-state Customer Code) - CBC (Customer Bar Code)', 0, 1);
        $pdf->write1DBarcode('SN34RD1A', 'RMS4CC', '', '', '', 15, 0.6, $style, 'N');

        $pdf->Ln();

// KIX (Klant index - Customer index)
        $pdf->Cell(0, 0, 'KIX (Klant index - Customer index)', 0, 1);
        $pdf->write1DBarcode('SN34RDX1A', 'KIX', '', '', '', 15, 0.6, $style, 'N');

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// TEST BARCODE ALIGNMENTS
// add a page
        $pdf->AddPage();

// set a background color
        $style['bgcolor'] = array(255, 255, 240);
        $style['fgcolor'] = array(127, 0, 0);

// Left position
        $style['position'] = 'L';
        $pdf->write1DBarcode('LEFT', 'C128A', '', '', '', 15, 0.4, $style, 'N');

        $pdf->Ln(2);

// Center position
        $style['position'] = 'C';
        $pdf->write1DBarcode('CENTER', 'C128A', '', '', '', 15, 0.4, $style, 'N');

        $pdf->Ln(2);

// Right position
        $style['position'] = 'R';
        $pdf->write1DBarcode('RIGHT', 'C128A', '', '', '', 15, 0.4, $style, 'N');

        $pdf->Ln(2);
// . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .

        $style['fgcolor'] = array(0, 127, 0);
        $style['position'] = '';
        $style['stretch'] = false; // disable stretch
        $style['fitwidth'] = false; // disable fitwidth
// Left alignment
        $style['align'] = 'L';
        $pdf->write1DBarcode('LEFT', 'C128A', '', '', '', 15, 0.4, $style, 'N');

        $pdf->Ln(2);

// Center alignment
        $style['align'] = 'C';
        $pdf->write1DBarcode('CENTER', 'C128A', '', '', '', 15, 0.4, $style, 'N');

        $pdf->Ln(2);

// Right alignment
        $style['align'] = 'R';
        $pdf->write1DBarcode('RIGHT', 'C128A', '', '', '', 15, 0.4, $style, 'N');

        $pdf->Ln(2);
// . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .

        $style['fgcolor'] = array(0, 64, 127);
        $style['position'] = '';
        $style['stretch'] = false; // disable stretch
        $style['fitwidth'] = true; // disable fitwidth
// Left alignment
        $style['cellfitalign'] = 'L';
        $pdf->write1DBarcode('LEFT', 'C128A', 105, '', 90, 15, 0.4, $style, 'N');

        $pdf->Ln(2);

// Center alignment
        $style['cellfitalign'] = 'C';
        $pdf->write1DBarcode('CENTER', 'C128A', 105, '', 90, 15, 0.4, $style, 'N');

        $pdf->Ln(2);

// Right alignment
        $style['cellfitalign'] = 'R';
        $pdf->write1DBarcode('RIGHT', 'C128A', 105, '', 90, 15, 0.4, $style, 'N');

        $pdf->Ln(2);
// . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . . .

        $style['fgcolor'] = array(127, 0, 127);

// Left alignment
        $style['position'] = 'L';
        $pdf->write1DBarcode('LEFT', 'C128A', '', '', '', 15, 0.4, $style, 'N');

        $pdf->Ln(2);

// Center alignment
        $style['position'] = 'C';
        $pdf->write1DBarcode('CENTER', 'C128A', '', '', '', 15, 0.4, $style, 'N');

        $pdf->Ln(2);

// Right alignment
        $style['position'] = 'R';
        $pdf->write1DBarcode('RIGHT', 'C128A', '', '', '', 15, 0.4, $style, 'N');

// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
// TEST BARCODE STYLE
// define barcode style
        $style = array(
            'position' => '',
            'align' => '',
            'stretch' => true,
            'fitwidth' => false,
            'cellfitalign' => '',
            'border' => true,
            'hpadding' => 'auto',
            'vpadding' => 'auto',
            'fgcolor' => array(0, 0, 128),
            'bgcolor' => array(255, 255, 128),
            'text' => true,
            'label' => 'CUSTOM LABEL',
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 4
        );

// CODE 39 EXTENDED + CHECKSUM
        $pdf->Cell(0, 0, 'CODE 39 EXTENDED + CHECKSUM', 0, 1);
        $pdf->SetLineStyle(array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(255, 0, 0)));
        $pdf->write1DBarcode('CODE 39 E+', 'C39E+', '', '', 120, 25, 0.4, $style, 'N');

// ---------------------------------------------------------
//Close and output PDF document
        $pdf->Output('example_027.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+
    }

    public function setMarcadores($plantilla = '', $marcadores = array()) {
        foreach ($marcadores as $campo => $vlr) {
            $plantilla = str_replace('{' . $campo . '}', $vlr, $plantilla);
        }
        return $plantilla;
    }

}
