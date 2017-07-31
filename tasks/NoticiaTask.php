<?php

use Phalcon\Cli\Task;
use Illuminate\Database\Capsule\Manager as Capsule;
use GuzzleHttp\Client;

class NoticiaTask extends Task
{
    public function mainAction(array $params)
    {
        $noticias= Capsule::table('noticiasAnual as n')
		->join('periodicos as p', 'n.Periodico', '=', 'p.idPeriodico')
		->join('circulacion as c','p.circulacion', '=', 'c.idCirculacion')
		->join('periodo','p.periodo', '=', 'periodo.idPeriodo')
		->join('contenidoPeriodico','p.id_contenido', '=', 'contenidoPeriodico.id_contenido')
		->join('tipoCaptura','p.id_Tcaptura', '=', 'tipoCaptura.id_captura')
		->join('regiones','p.id_region', '=', 'regiones.idRegion')
		->join('columnistasPeriodico', 'p.idPeriodico', '=', 'columnistasPeriodico.id_Periodico')
		->select(Capsule::raw('CAST(n.Seccion AS CHAR CHARACTER SET utf8) as iSeccClv'))
		->addSelect(Capsule::raw('CAST(n.Categoria AS CHAR CHARACTER SET utf8) as iCateClv'))
		->addSelect('n.NumeroPagina as cNotiPagiNum')
		->addSelect(Capsule::raw('CAST(n.PaginaPeriodico AS CHAR CHARACTER SET utf8) as iNotiRealPagiNum'))
		->addSelect('n.Autor as cNotiAutoNom')
		->addSelect('n.Titulo as cNotiTituTxt')
		->addSelect('n.Encabezado as cNotiEncaTxt')
		->addSelect('n.Texto as cNotiCuerTxt')
		->addSelect(Capsule::raw('CAST(n.Periodico AS CHAR CHARACTER SET utf8) as iMediClv'))
		->addSelect('n.estatus as cMoniSts')
		->addSelect(Capsule::raw('CAST(p.tipo AS CHAR CHARACTER SET utf8) as iMediTipoClv'))
		->addSelect('c.tipo as cCircTpo')
		->addSelect('periodo.periodo as cPrdoTpo')
		->addSelect('contenidoPeriodico.Contenido as cContTpo')
		->addSelect('tipoCaptura.Captura as cCaptTpo')
		->addSelect('regiones.region as cRegiTpo')
		->addSelect(Capsule::raw('CAST(p.Pais AS CHAR CHARACTER SET utf8) as iPaisClv'))
		->addSelect(Capsule::raw('CAST(p.Estado AS CHAR CHARACTER SET utf8) as iEsdoClv'))
		->addSelect(Capsule::raw('CAST(p.Municipio AS CHAR CHARACTER SET utf8) as iMuniClv'))
		->addSelect('columnistasPeriodico.id_Columnista as iColuClv')
		->addSelect(Capsule::raw('0 as iClnaClv'))
		->addSelect(Capsule::raw('1 as iCnteClv'))
		->addSelect('n.Fecha as daNotiCaptFec')
		->addSelect(Capsule::raw('CAST(n.idCapturista AS CHAR CHARACTER SET utf8) as cUsuaID'))
		->whereBetween('n.Fecha',array($params[0], $params[1]))
		->get();
	foreach ($noticias as $sakura) {
	if(strlen($sakura->cNotiCuerTxt)>=25000){
			$new= Array();
			$nami=chunk_split($sakura->cNotiCuerTxt, 25000, '|');
			$nami = substr($nami, 0, -1);
			$arr = explode('|', $nami);
			foreach ($arr as $value) {
				array_push($new,['cNotiCuerTxt'=>$value]);
			}

			$sakura->cNotiCuerTxt="";
			$sakura->ttNotiReco=array(['cImagNom'=>$sakura->cNotiPagiNum.'.jpg','cImagCoorVal'=>'[]']);
			$sakura->ttNotiDetl=$new;

		}
		$sakura->ttNotiReco=array(['cImagNom'=>$sakura->cNotiPagiNum.'.jpg','cImagCoorVal'=>'[]']);
		$datos=array('dsNoticias'=>array('ttNotiMstr'=>[$sakura]));	
		$url = 'http://192.168.3.153/gamiab/rest/serviciosab/noticias';

		$headers = [
			'Accept' => 'application/json',
		];
		


		$client = new client();
		$res = $client->post($url, [
			'headers' => $headers, 
			'json' => $datos,
		]);
		$response=$res->getBody()->getContents();
		$response=json_decode($response, true);
		$error=$response['response']['siNoticia']['dsNoticias']['ttNotiMstr'][0]['lErro'];
		if (!$error)
		{
			echo 'I love you'.PHP_EOL;
		}
		elseif($response['response']['siNoticia']['dsNoticias']['ttNotiMstr'][0]['cErroDes']=='La Nota Principal para éste medio impreso ya fue capturada.'){
			echo 'Kernel Panic [error fatal de sistema]'.PHP_EOL;
			$arch = fopen('panic/exists/'.time().'.json', "w") or die("kernel panic file!");
			//fwrite($arch, $datos.PHP_EOL);
			fwrite($arch, json_encode($response, JSON_PRETTY_PRINT));
			fclose($arch);
		}
		else{
			echo 'Kernel Panic [error fatal de sistema]'.PHP_EOL;
			$arch = fopen('panic/'.time().'.json', "w") or die("kernel panic file!");
			//fwrite($arch, $datos.PHP_EOL);
			fwrite($arch, json_encode($response, JSON_PRETTY_PRINT));
			fclose($arch);
		}
	}

	}

}
