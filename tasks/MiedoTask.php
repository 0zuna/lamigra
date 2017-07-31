<?php

use Phalcon\Cli\Task;
use Illuminate\Database\Capsule\Manager as Capsule;
use GuzzleHttp\Client;

class MiedoTask extends Task
{
    public function mainAction()
    {
        $medios= Capsule::table('periodicos')
		->join('periodo', 'periodicos.periodo', '=', 'periodo.idPeriodo')
		->join('contenidoPeriodico', 'periodicos.id_contenido', '=', 'contenidoPeriodico.id_contenido')
		->join('tipoCaptura', 'periodicos.id_Tcaptura', '=', 'tipoCaptura.id_captura')
		->join('circulacion', 'periodicos.circulacion', '=', 'circulacion.idCirculacion')
		->join('regiones', 'periodicos.id_region', '=', 'regiones.idRegion')
		->join('seccionesPeriodico', 'periodicos.idPeriodico', '=', 'seccionesPeriodico.id_periodico')
		->select('periodicos.idPeriodico as iMediClv')
		->addSelect('periodicos.Nombre as cMediNom')
		->addSelect('periodicos.Tiraje as iMediTiraNum')
		->addSelect('periodicos.webpage as cMediSitiWeb')
		->addSelect('periodicos.Nombre as cMediTestDir')
		->addSelect('periodicos.tipo as iMediTipoClv')
		->addSelect('periodo.periodo as cPrdoTpo')
		->addSelect('contenidoPeriodico.Contenido as cContTpo')
		->addSelect('tipoCaptura.Captura as cCaptTpo')
		->addSelect('circulacion.tipo as cCircTpo')
		->addSelect('regiones.region as cRegiTpo')
		->addSelect('periodicos.Cobertura as cCobeTpo')
		->addSelect('periodicos.gratuito as lMediGrat')
		->addSelect('periodicos.MedidaM as dMediTamaNum')
		->addSelect('periodicos.EMail as cMediMailDir')
		->addSelect('periodicos.PorcH as dMediHombPje')
		->addSelect('periodicos.PorcM as dMediMujePje')
		->addSelect(Capsule::raw('0 as iMediDispHrs'))
		->addSelect('periodicos.Calleynumero as cMediCallNom')
		->addSelect('periodicos.Colonia as cMediColoNom')
		->addSelect(Capsule::raw('CAST(periodicos.CodigoPostal AS UNSIGNED) as iMediCodiPost'))
		->addSelect('periodicos.Telefono as cMediTeleNum')
		->addSelect('periodicos.Fax as cMediFaxNum')
		->addSelect(Capsule::raw('"0" as cMediDomiNum'))
		->addSelect(Capsule::raw('1 as iCnteClv'))
		->addSelect('periodicos.Pais as iPaisClv')
		->addSelect('periodicos.Estado as iEsdoClv')
		->addSelect('periodicos.Municipio as iMuniClv')
		->addSelect('periodicos.activo as lMediSts')
		->addSelect(Capsule::raw('CONCAT("thumb-",periodicos.idPeriodico,".jpg") as cMediLogoDir'))
		->addSelect('periodicos.mediaPagina as dMediPromPags')
		->groupBy('periodicos.idPeriodico')
		->selectRaw('GROUP_CONCAT(seccionesPeriodico.id_seccion) as cSeccList')
		->addSelect('periodicos.costoXmodulo as dModuCos')
		->addSelect('periodicos.cantidadModulos as iModuCan')
		->addSelect('periodicos.cm2Modulo as dModuCm2')
		->addSelect('periodicos.cm2Pagina as dPagiCm2')
		->get();
//	echo json_encode($medios, JSON_PRETTY_PRINT);
//	echo substr_count( $medios[0]->cSeccList, ',');
	foreach ($medios as $sakura) {
		$miedo=['dsMediosImpresos'=>['ttMediMstr'=>[$sakura]]];
		/*$client = new client();
		$res = $client->post($url, [
			'headers' => ['Accept'=>'application/json'], 
			'json' => $miedo,
		]);
		$response=$res->getBody()->getContents();
		$response=json_decode($response, true);
		if (!$error)
		{
			echo 'I love you'.PHP_EOL;
		}
		else{
			echo 'Kernel Panic [error fatal de sistema]'.PHP_EOL;
			$arch = fopen('panic/medios/'.time().'.json', "w") or die("kernel panic file!");
			fwrite($arch, json_encode($datos,JSON_PRETTY_PRINT).PHP_EOL);
			fwrite($arch, json_encode($response, JSON_PRETTY_PRINT));
			fclose($arch);
		}*/
	}

	}

}
