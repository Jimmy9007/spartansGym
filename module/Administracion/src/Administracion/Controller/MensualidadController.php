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

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Administracion\Formularios\MensualidadForm;
use Administracion\Modelo\Entidades\Mensualidad;
use Administracion\Formularios\SalidaForm;
use Dompdf\Dompdf;

class MensualidadController extends AbstractActionController {

    private $mensualidadDAO;
    private $usuarioDAO;
    private $ventaDAO;
    private $entrnoDAO;

    public function getMensualidadDAO() {
        if (!$this->mensualidadDAO) {
            $sm = $this->getServiceLocator();
            $this->mensualidadDAO = $sm->get('Administracion\Modelo\DAO\MensualidadDAO');
        }
        return $this->mensualidadDAO;
    }

    public function getUsuarioDAO() {
        if (!$this->usuarioDAO) {
            $sm = $this->getServiceLocator();
            $this->usuarioDAO = $sm->get('Administracion\Modelo\DAO\UsuarioDAO');
        }
        return $this->usuarioDAO;
    }

    public function getVentaDAO() {
        if (!$this->ventaDAO) {
            $sm = $this->getServiceLocator();
            $this->ventaDAO = $sm->get('Administracion\Modelo\DAO\VentaDAO');
        }
        return $this->ventaDAO;
    }

    public function getEntrenoDAO() {
        if (!$this->entrnoDAO) {
            $sm = $this->getServiceLocator();
            $this->entrnoDAO = $sm->get('Administracion\Modelo\DAO\EntrenoDAO');
        }
        return $this->entrnoDAO;
    }

    function getFormulario($action = '', $onsubmit = '', $idMensualidad = 0) {
        $required = true;
        if ($action == 'detail') {
            $required = false;
        }
        $form = new MensualidadForm($action, $onsubmit, $required);
        if ($action == 'edit') {
            
        }
        if ($idMensualidad != 0) {
            $mensualidadOBJ = $this->getMensualidadDAO()->getMensualidad($idMensualidad);
            $form->bind($mensualidadOBJ);
        }
        return $form;
    }

//------------------------------------------------------------------------------

    public function indexAction() {
//        $filtro = "mensualidad.estado = 'Eliminado'";
        return new ViewModel(array(
            'mensualidades' => $this->getMensualidadDAO()->getMensualidades()
        ));
    }

    public function indexReporteExcelAction() {
        require_once 'vendor/PHPExcel/PHPExcel.php';
        require_once 'vendor/PHPExcel/PHPExcel/Writer/Excel2007.php';
        $formSalida = new SalidaForm();
        $fecha = date('Y-m-d');
        if ($_GET) {
            $hoy = date('Y-m-d H-i-s');

            $fechaI = $_GET['fechaInicio'];
            $fechaF = $_GET['fechaFin'];
            $reporte = $_GET['r'];


            $formSalida = new SalidaForm();
            $objReader = new \PHPExcel_Reader_Excel2007();

            if ($reporte === 'Mensualidades') {
                $objExcel = $objReader->load('module/Administracion/view/administracion/mensualidad/plantilla_reporte_mensualidad.xlsx');

                $mensualidades = $this->getMensualidadDAO()->getReporteMensualidad($where = "DATE(reportemensualidad.fechaReporte) BETWEEN '" . $fechaI . "' AND '" . $fechaF . "' ");

                $objExcel->setActiveSheetIndex(0);
                $objActSheet = $objExcel->getActiveSheet();
                $objActSheet->setCellValue('E6', $fechaI);
                $objActSheet->setCellValue('I6', $fechaF);

                $objActSheet->setTitle('Reporte Mensualidad');

                $cont = 11;
                $limBorder = 0;
                foreach ($mensualidades as $mensualidad) {
                    $objActSheet->setCellValue('A' . $cont, $mensualidad['reporteOBJ']->getFk_mensualidad_id());
                    $objActSheet->setCellValue('B' . $cont, $mensualidad['usuarioOBJ']->getNombreApellido());
                    $objActSheet->setCellValue('C' . $cont, $mensualidad['clienteempleadoOBJ']->getIdentificacion());
                    $objActSheet->setCellValue('D' . $cont, $mensualidad['reporteOBJ']->getFechaReporte());
                    $objActSheet->setCellValue('E' . $cont, $mensualidad['reporteOBJ']->getFechaFinReporte());
                    $objActSheet->setCellValue('F' . $cont, $mensualidad['reporteOBJ']->getValorReporte());
                    $objActSheet->setCellValue('G' . $cont, $mensualidad['mensualidadOBJ']->getFechaUltPreaviso());

                    $cont ++;
                    $limBorder = $cont - 1;
                }
                $objStyleB = $objActSheet->getStyle('A9');

//                $objActSheet->duplicateStyle($objStyleB, 'A9:K' . $limBorder);

                $file_name = $fechaI . '_' . $fechaF . '-Mensualidades' . '-' . $hoy;
            }

            if ($reporte === 'Ventas') {
                $objExcel = $objReader->load('module/Administracion/view/administracion/mensualidad/plantilla_reporte_venta.xlsx');

                $ventas = $this->getVentaDAO()->getReporteVenta($where = "DATE(venta.fechaVenta) BETWEEN '" . $fechaI . "' AND '" . $fechaF . "' ");

                $objExcel->setActiveSheetIndex(0);
                $objActSheet = $objExcel->getActiveSheet();
                $objActSheet->setCellValue('E6', $fechaI);
                $objActSheet->setCellValue('I6', $fechaF);

                $objActSheet->setTitle('Reporte Ventas');

                $cont = 11;
                $limBorder = 0;
                foreach ($ventas as $venta) {
                    $objActSheet->setCellValue('A' . $cont, $venta['ventaOBJ']->getPk_venta_id());
                    $objActSheet->setCellValue('B' . $cont, $venta['productoOBJ']->getNombreProducto());
                    $objActSheet->setCellValue('C' . $cont, $venta['productoOBJ']->getPrecioCosto());
                    $objActSheet->setCellValue('D' . $cont, $venta['productoOBJ']->getPrecioVenta());
                    $objActSheet->setCellValue('E' . $cont, $venta['productoVentaOBJ']->getCantidadVenta());
                    $objActSheet->setCellValue('F' . $cont, $venta['productoVentaOBJ']->getMonto());
//                    $objActSheet->setCellValue('G' . $cont, $venta['ventaOBJ']->getGanancia());
                    $objActSheet->setCellValue('H' . $cont, $venta['ventaOBJ']->getFechaVenta());


                    $cont ++;
                    $limBorder = $cont - 1;
                }
                $objStyleB = $objActSheet->getStyle('A9');

//                $objActSheet->duplicateStyle($objStyleB, 'A9:K' . $limBorder);

                $file_name = $fechaI . '_' . $fechaF . '-Ventas' . '-' . $hoy;
            }
            if ($reporte === 'Entrenos') {
                $objExcel = $objReader->load('module/Administracion/view/administracion/mensualidad/plantilla_reporte_entreno.xlsx');

                $entrenos = $this->getEntrenoDAO()->getEntrenos($where = "DATE(entreno.fechaHoraEntreno) BETWEEN '" . $fechaI . "' AND '" . $fechaF . "' ");

                $objExcel->setActiveSheetIndex(0);
                $objActSheet = $objExcel->getActiveSheet();
                $objActSheet->setCellValue('E6', $fechaI);
                $objActSheet->setCellValue('I6', $fechaF);

                $objActSheet->setTitle('Reporte Entrenos');

                $cont = 11;
                $limBorder = 0;
                foreach ($entrenos as $entreno) {
                    $objActSheet->setCellValue('A' . $cont, $entreno->getPk_entreno_id());
                    $objActSheet->setCellValue('B' . $cont, $entreno->getNombreUsuario());
                    $objActSheet->setCellValue('C' . $cont, $entreno->getFechaHoraEntreno());
                    $objActSheet->setCellValue('D' . $cont, $entreno->getValor());

                    $cont ++;
                    $limBorder = $cont - 1;
                }
                $objStyleB = $objActSheet->getStyle('A9');

//                $objActSheet->duplicateStyle($objStyleB, 'A9:K' . $limBorder);

                $file_name = $fechaI . '_' . $fechaF . '-Entrenos' . '-' . $hoy;
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="' . $file_name . '.xlsx"');
            header('Cache-Control: max-age=0');

            header('Cache-Control: max-age=1');

            header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
            header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
            header('Cache-Control: cache, must-revalidate');
            header('Pragma: public');

            $objWriter = \PHPExcel_IOFactory::createWriter($objExcel, 'Excel2007');
            $objWriter->save('php://output');
            exit;

            $formSalida->get('fechaInicio')->setValue($fechaI);
            $formSalida->get('fechaFin')->setValue($fechaF);
            $formSalida->get('tipo_reporte')->setValue($reporte);
        } else {
            $formSalida->get('fechaInicio')->setValue($fecha);
            $formSalida->get('fechaFin')->setValue($fecha);
        }
        return new ViewModel(array(
            'formSalida' => $formSalida,
        ));
    }

    public function indexmensualidadusuarioAction() {
        $idUsuario = '';
        if ($sesionUsuario = $this->identity()) {
            $idUsuario = $sesionUsuario->pk_usuario_id;
        }
//        $filtro = "mensualidad.estado = 'Eliminado'";
        return new ViewModel(array(
            'mensualidades' => $this->getMensualidadDAO()->getMensualidadeUsuario($idUsuario)
        ));
    }

    public function indexmensualidadestadisticasAction() {
$anio = (int) $this->params()->fromQuery('anio', date('Y'));
        return new ViewModel(array(
            'mensualidadenero' => $this->getMensualidadDAO()->getTotalMensualidadByFechas($anio . '-01-01', $anio . '-02-01'),
            'mensualidadfebrero' => $this->getMensualidadDAO()->getTotalMensualidadByFechas($anio . '-02-01', $anio . '-03-01'),
            'mensualidadmarzo' => $this->getMensualidadDAO()->getTotalMensualidadByFechas($anio . '-03-01', $anio . '-04-01'),
            'mensualidadabril' => $this->getMensualidadDAO()->getTotalMensualidadByFechas($anio . '-04-01', $anio . '-05-01'),
            'mensualidadmayo' => $this->getMensualidadDAO()->getTotalMensualidadByFechas($anio . '-05-01', $anio . '-06-01'),
            'mensualidadjunio' => $this->getMensualidadDAO()->getTotalMensualidadByFechas($anio . '-06-01', $anio . '-07-01'),
            'mensualidadjulio' => $this->getMensualidadDAO()->getTotalMensualidadByFechas($anio . '-07-01', $anio . '-08-01'),
            'mensualidadagosto' => $this->getMensualidadDAO()->getTotalMensualidadByFechas($anio . '-08-01', $anio . '-09-01'),
            'mensualidadseptiembre' => $this->getMensualidadDAO()->getTotalMensualidadByFechas($anio . '-09-01', $anio . '-10-01'),
            'mensualidadoctubre' => $this->getMensualidadDAO()->getTotalMensualidadByFechas($anio . '-10-01', $anio . '-11-01'),
            'mensualidadnoviembre' => $this->getMensualidadDAO()->getTotalMensualidadByFechas($anio . '-11-01', $anio . '-12-01'),
            'mensualidaddiciembre' => $this->getMensualidadDAO()->getTotalMensualidadByFechas($anio . '-12-01', ($anio + 1) . '-01-01'),
            'ventasenero' => $this->getMensualidadDAO()->getTotalVentaByFechas($anio . '-01-01', $anio . '-02-01'),
            'ventasfebrero' => $this->getMensualidadDAO()->getTotalVentaByFechas($anio . '-02-01', $anio . '-03-01'),
            'ventasmarzo' => $this->getMensualidadDAO()->getTotalVentaByFechas($anio . '-03-01', $anio . '-04-01'),
            'ventasabril' => $this->getMensualidadDAO()->getTotalVentaByFechas($anio . '-04-01', $anio . '-05-01'),
            'ventasmayo' => $this->getMensualidadDAO()->getTotalVentaByFechas($anio . '-05-01', $anio . '-06-01'),
            'ventasjunio' => $this->getMensualidadDAO()->getTotalVentaByFechas($anio . '-06-01', $anio . '-07-01'),
            'ventasjulio' => $this->getMensualidadDAO()->getTotalVentaByFechas($anio . '-07-01', $anio . '-08-01'),
            'ventasagosto' => $this->getMensualidadDAO()->getTotalVentaByFechas($anio . '-08-01', $anio . '-09-01'),
            'ventasseptiembre' => $this->getMensualidadDAO()->getTotalVentaByFechas($anio . '-09-01', $anio . '-10-01'),
            'ventasoctubre' => $this->getMensualidadDAO()->getTotalVentaByFechas($anio . '-10-01', $anio . '-11-01'),
            'ventasnoviembre' => $this->getMensualidadDAO()->getTotalVentaByFechas($anio . '-11-01', $anio . '-12-01'),
            'ventasdiciembre' => $this->getMensualidadDAO()->getTotalVentaByFechas($anio . '-12-01', ($anio + 1) . '-01-01'),
        ));
    }

    public function addAction() {
        $action = 'add';
        $onsubmit = 'return validarGuardar()';
        $form = $this->getFormulario($action, $onsubmit);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $mensualidadOBJ = new Mensualidad($form->getData());
                $this->getMensualidadDAO()->guardar($mensualidadOBJ);
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'mensualidad',
                            'action' => 'index',
                ));
            } else {
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'mensualidad',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form,
        ));
        $view->setTemplate('administracion/mensualidad/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function editAction() {
        $idMensualidad = (int) $this->params()->fromQuery('idMensualidad', 0);
        $action = 'edit';
        $onsubmit = 'return confirm("¿ DESEA GUARDAR ESTE USUARIO ?")';
        $form = $this->getFormulario($action, $onsubmit, $idMensualidad);
        $request = $this->getRequest();
        if ($request->isPost()) {
//            $nombreMensualidad = '';
//            if ($sesionMensualidad = $this->identity()) {
//                $nombreMensualidad = $sesionMensualidad->login;
//            }
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $mensualidadOBJ = new Mensualidad($form->getData());
//                $nombreMensualidad = '';
//                if ($sesionMensualidad = $this->identity()) {
//                    $nombreMensualidad = $sesionMensualidad->login;
//                }
//                $mensualidadOBJ->setModificadoPor($nombreMensualidad);
//                $mensualidadOBJ->setFechaHoraMod(date('Y-m-d H:i:s'));

                $this->getMensualidadDAO()->guardar($mensualidadOBJ);
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'mensualidad',
                            'action' => 'index',
                ));
            } else {
                return $this->redirect()->toRoute('administracion/default', array(
                            'controller' => 'mensualidad',
                            'action' => 'index',
                ));
            }
        }
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/mensualidad/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function detailAction() {
        $idMensualidad = (int) $this->params()->fromQuery('idMensualidad', 0);
        $action = 'detail';
        $onsubmit = 'return false';
        $form = $this->getFormulario($action, $onsubmit, $idMensualidad);
        $view = new ViewModel(array(
            'form' => $form
        ));
        $view->setTemplate('administracion/mensualidad/formulario');
        $view->setTerminal(true);
        return $view;
    }

    public function getMensualidadSeleccionadaAction() {
        $idMensualidad = (int) $this->params()->fromQuery('idMensualidad', 0);
        if (!$idMensualidad) {
            return 0;
        }
        try {
            $mensualidadOBJ = $this->getOpcionDAO()->getOpcion($idMensualidad);
        } catch (\Exception $ex) {
            return 0;
        }
        $formOpcion = new OpcionFormm();
        $formOpcion->bind($mensualidadOBJ);
        $view = new ViewModel(array(
            'formMensualidad' => $formMensualidad,
        ));
        $view->setTerminal(true);
        return $view;
    }

    public function enviarPreavisoAction() {
        $idsMensualidades = $this->params()->fromPost('idsMensualidades', "");
        if ($idsMensualidades == "") {
            return;
        }
        $meses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre',);
        $idsMensualidades = trim($idsMensualidades, ',');
        $partesIds = explode(',', $idsMensualidades);
        foreach ($partesIds as $idMensualidad) {
            $mensualidadOBJ = $this->getMensualidadDAO()->getMensualidad($idMensualidad);
            $usuarioOBJ = $this->getUsuarioDAO()->getUsuario($mensualidadOBJ->getFk_usuario_id());
            $marcadores = array(
                'mesLetras' => $meses[date('n') - 1],
                'dia' => date('d'),
                'anio' => date('Y'),
                'NOM_USU' => $usuarioOBJ->getNOM_USU(),
                'APELL_USU' => $usuarioOBJ->getAPELL_USU(),
                'diaLetras' => $this->getDiaLetras(date('d')),
            );
            $plantilla = file_get_contents('module/Administracion/view/administracion/mensualidad/preavisoPDF.html');
            $html = $this->setMarcadores($plantilla, $marcadores);
            $dompdf = new Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            $docPDF = $dompdf->output();
//        $dompdf->stream('CONTRATO DE VENTA DE SERVICIOS', array('Attachment' => 0));
// first create the parts
            $text = new \Zend\Mime\Part();
            $text->type = \Zend\Mime\Mime::TYPE_TEXT;
            $text->charset = 'utf-8';

//        $fileContents = fopen($somefilePath, 'r');
            $attachment = new \Zend\Mime\Part($docPDF);
            $attachment->type = 'application/pdf';
            $attachment->filename = 'preaviso.pdf';
            $attachment->encoding = \Zend\Mime\Mime::ENCODING_BASE64;
            $attachment->disposition = \Zend\Mime\Mime::DISPOSITION_ATTACHMENT;

// then add them to a MIME message
            $mimeMessage = new \Zend\Mime\Message();
            $mimeMessage->setParts(array($text, $attachment));


            $message = new \Zend\Mail\Message();
            $message->setBody($mimeMessage);
//            $message->setFrom('juridica@dobleclick.net.co');
            $message->setFrom('popgymjn@gmail.com');
            $message->addTo($usuarioOBJ->getCORREO_USU());
            $message->setSubject('POPGYM Vencimiento De Mensualidad');
            $smtpOptions = new \Zend\Mail\Transport\SmtpOptions();
            $smtpOptions->setHost('smtp.gmail.com')
                    ->setConnectionClass('login')
                    ->setName('smtp.gmail.com')
                    ->setConnectionConfig(array(
//                        'username' => 'software@dobleclick.net.co',
//                        'password' => 'd0bl3ClicK',
                        'username' => 'popgymjn@gmail.com',
                        'password' => 'P0pgym123',
                        'ssl' => 'tls',
            ));
            $transport = new \Zend\Mail\Transport\Smtp($smtpOptions);
            $transport->send($message);
            $this->getMensualidadDAO()->setFechaUltPreaviso($idMensualidad);
        }
        return $this->redirect()->toRoute('administracion/default', array(
                    'controller' => 'mensualidad',
                    'action' => 'index',
        ));
    }

    public function setMarcadores($plantilla = '', $marcadores = array()) {
        foreach ($marcadores as $campo => $vlr) {
            $plantilla = str_replace('{' . $campo . '}', $vlr, $plantilla);
        }
        return $plantilla;
    }

    public function getDiaLetras($dia = 0) {
        $dias = array(
            'un',
            'dos',
            'tres',
            'cuatro',
            'cinco',
            'seis',
            'siete',
            'ocho',
            'nueve',
            'diez',
            'once',
            'doce',
            'trece',
            'catorce',
            'quince',
            'dieciseis',
            'diecisiete',
            'dieciocho',
            'diecinueve',
            'veinte',
            'veintiun',
            'veintidos',
            'veintitres',
            'veinticuatro',
            'veinticinco',
            'veintiseis',
            'veintisiete',
            'veintiocho',
            'veintinueve',
            'treinta',
            'treinta y un'
        );
        return $dias[$dia - 1];
    }

}
