<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use App\Models\Media;


class InicioController extends Controller
{
    //
    private $ruta = './media';
    private $urlExtern = 'https://apps.cinteractivo.mx/photoboot/';

    public function index(){
    	$videos = Media::orderByRaw('uploaded_at - created_at DESC')->get();
    	return view('welcome', ['videos' => $videos]);
    }

    public function readFiles($ruta = ''){
    	if($ruta == ''){
    		$ruta =  $this->ruta;
    		echo "Archivos procesados:";
    	}
    	if (is_dir($ruta)){
	        // Abre un gestor de directorios para la ruta indicada
	        $gestor = opendir($ruta);
	        echo "<ul>";

	        // Recorre todos los elementos del directorio
	        while (($file = readdir($gestor)) !== false)  {
	                
	            $ruta_completa = $ruta . "/" . $file;

	            // Se muestran todos los archvios y carpetas excepto "." y ".."
	            if ($file != "." && $file != ".." && $file != 'register') {
	                // Si es un directorio se recorre recursivamente
	                if (is_dir($ruta_completa)) {
	                    echo "<li>" . $file . "</li>";
	                    $this->readFiles($ruta_completa);
	                } else {
	                    echo "<li>" . $file . "</li>";
	                    $this->registerFile($ruta_completa, $file);
	                }
	            }
	        }
	        
	        // Cierra el gestor de directorios
	        closedir($gestor);
	        echo "</ul>";
	    } else {
	        echo "No es una ruta de directorio valida<br/>";
	    }
    }

    private function getCode($length, $seed){    
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "0123456789";

        mt_srand($seed);      // Call once. Good since $application_id is unique.

        for($i=0;$i<$length;$i++){
            $token .= $codeAlphabet[mt_rand(0,strlen($codeAlphabet)-1)];
        }
        return 'FE'. $token;
    }

    public function registerFile($pathFile, $namefile){
    	// dd($file);
    	$code = '';
    	do{
		    $code = $this->getCode(5, date('si'));
		    // $code = $code;// . substr(strftime("%Y", time()),2);
		    $mediaExist = Media::where('code', $code)->get();
		    // dd($mediaExist->count());
		}while($mediaExist->count());
		$destiny = $this->ruta . "/register/video/";
		 // dd($pathFile, $destiny . $namefile);
		copy($pathFile, $destiny . $namefile);
		unlink($pathFile);
    	$appointment = Media::create([
    		'path' => "$destiny$namefile",
    		'code' => $code,
    		'type' => '2', //1: imagen, 2:video
    		'status' => '1', // 1: registrado, 2:
    	]);
    	\QrCode::size(500)
            ->format('png')
            ->generate($this->urlExtern.'visor.php?code='.$code, public_path("qr/$code.png"));
    }

    public function uploadFile($code){
    	$file = Media::where('code', $code)->first();
    	//dd($file->toArray());
    	$response = Http::attach(
		    'attachment', file_get_contents($file->path), "$code.mp4"
		)->post($this->urlExtern.'saveFile.php', [
			'data' => $file
		]);
    	$file->uploaded_at = date('Y-m-d H:i:s');
    	$file->save();
    	// dd($response);
    	return response()->json(['code' => $code, 'ok' => true, $response]);
    }
    public function saveFile(Request $request){
    	dd($request);
    }
}
