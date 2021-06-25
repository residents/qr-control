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
    	$procesados = [];
    	if($ruta == ''){
    		$ruta =  $this->ruta;
    	}
    	if (is_dir($ruta)){
	        // Abre un gestor de directorios para la ruta indicada
	        $gestor = opendir($ruta);
	        // Recorre todos los elementos del directorio
	        while (($file = readdir($gestor)) !== false)  {
	            $ruta_completa = $ruta . "/" . $file;
	            if ($file != "." && $file != ".." && $file != 'register') {
	                // Si es un directorio se recorre recursivamente
	                if (is_dir($ruta_completa)) {
	                    $this->readFiles($ruta_completa);
	                } else {
                        $extension = explode('.', $file);
                        $cantidad =sizeof($extension);
                        $extension_correcta = $cantidad >= 1 && $extension[$cantidad-1] == 'mp4' ? 'mp4' : false; 
                        // Se muestran todos los archvios y carpetas excepto "." y ".." 
                        if($extension_correcta){
    	                    $procesados[] = $this->registerFile($ruta_completa, $file);
                        }
	                }
	            }
	        }
	        // Cierra el gestor de directorios
	        closedir($gestor);
	        // echo "</ul>";
	    }
	    return response()->json(['procesados' => $procesados]);
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
		$destiny = $this->ruta . "/new/video/";
        $media = Media::where('path', $pathFile)->get();
        if($media->count() == 0){
        	$code = '';
        	do{
        	    $code = $this->getCode(5, date('si'));
        	    // $code = $code;// . substr(strftime("%Y", time()),2);
        	    $mediaExist = Media::where('code', $code)->get();
        	    // dd($mediaExist->count());
        	}while($mediaExist->count());
        	 // dd($pathFile, $destiny . $namefile);
        	//copy($pathFile, $destiny . $namefile);
        	//unlink($pathFile);
        	$media = Media::create([
        		'path' => $pathFile,
        		'code' => $code,
        		'type' => '2', //1: imagen, 2:video
        		'status' => '1', // 1: registrado, 2:
        	]);
        	$this->uploadFile($code);
        	\QrCode::size(500)
                ->format('png')
                ->generate($this->urlExtern.'visor.php?code='.$code, public_path("qr/$code.png"));
            return ['file' => $namefile, 'status' => 'Procesado'];
        }
        return ['file' => $namefile, 'status' => 'Omitido'];
    }

    public function uploadFile($code){
    	$file = Media::where('code', $code)->whereNull('uploaded_at')->first();
    	if($file){
	    	$response = Http::attach(
			    'attachment', file_get_contents($file->path), "$code.mp4"
			)->post($this->urlExtern.'saveFile.php', [
				'data' => $file
			]);
	    	$file->uploaded_at = date('Y-m-d H:i:s');
	    	$file->save();
	    }
    	return response()->json(['code' => $code, 'ok' => true]);
    }
    public function saveFile(Request $request){
    	dd($request);
    }
}
