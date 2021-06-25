<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>QR - Compartir</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        


        <link rel="stylesheet" href="{{URL::to('css/bootstrap.min.css')}}">
        <script src="{{URL::to('js/jquery-3.5.1.slim.min.js')}}"></script>
        <script src="{{URL::to('js/bootstrap.bundle.min.js')}}"></script>

        <style>
            html,body{
                margin:0px;
                height:100%;
            }
            .fondo{
                min-height: 100%;
                background-repeat: no-repeat;
                background-size: cover;
                background-image: url({{URL::to('img/fondo.png')}});
            }
            .bg-card {
                background-color: #f39522!important;
            }
        </style>

    </head>
    <body class="antialiased">
        <div class="container-fluid px-4 py-3 fondo">
          <div class="row">
                @foreach($videos as $file)
                    <div class="card col-md-3 col-lg-3 col-sm-6 mb-2 p-0 px-1 bg-card border-0" data-url="{{route('uploadFile', ['code' => $file->code])}}" data-extension="{{$file->extension}}" data-qr="https://chart.apis.google.com/chart?cht=qr&chs=400x400&chld=L|0&chl=https://apps.cinteractivo.mx/photoboot/visor.php?code={{$file->code}}.{{$file->extension}}" data-src="{{$file->path}}">
                    @if($file->extension == 'mp4')
                        <video autoplay loop muted>
                            <source src="{{$file->path}}" type="video/mp4">
                        </video>
                    @elseif($file->extension == 'jpg' || $file->extension == 'jpeg')
                        <img src="{{$file->path}}">   
                    @elseif($file->extension == 'gif')
                        <img src="{{$file->path}}"> 
                    @endif
                    </div>
                @endforeach
          </div>
        </div>
        <div class="modal fade" id="myModal" tabindex="-1">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content bg-card">
                    <!-- <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div> -->
                    <div class="modal-body">
                        <div class="row">
                            <video autoplay loop muted id="videoModal" class="jqFile col-md-6 col-sm-6 d-none">
                                <source src="" type="video/mp4">
                            </video>
                            <img id="imageModal" src="" class="jqFile col-md-6 col-sm-6 d-none">
                            <div class="col-md-6 col-sm-6 container">
                                <div class="row align-items-center mt-5 pt-5">
                                    <img src="img/default.png" id="qrModal" class="col-md-12 mt-5">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-center">
                        <div class="col-lg-6 col-md-6 col-sm-6"> 
                            <button type="button" class="btn btn-block btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            let registrarVideos = function(){
                fetch("{{route('readFiles')}}").then(function(response) {
                    if(response.ok){
                        // console.log(response);
                        return response.json();
                    } else {
                        console.log('Respuesta de red OK pero respuesta HTTP no OK');
                    }
                })
                .catch(function(error) {
                    console.log('Hubo un problema con la petición Fetch:' + error.message);
                }).then(function(json){
                    console.log(json.procesados);
                    if(json.procesados.length){
                        console.log(json.procesados);
                        location.reload();
                    }
                });
            }
            setInterval(function(){
                registrarVideos();
            }, 5000);
            // registrarVideos();
            $('.card').off('click').on('click', function(){
                $('#myModal').modal('show');
                let srcfile = $(this).attr('data-src');
                let srcQr = $(this).attr('data-qr');
                let urlUpload = $(this).attr('data-url');
                let ext = $(this).attr('data-extension');
                let qr = document.getElementById("qrModal");
                //if(ext == 'mp4'){
                    $('#videoModal, #imageModal').addClass('jqFile col-md-6 col-sm-6 d-none');
                    let id = ext == 'mp4' ? 'videoModal' : 'imageModal';
                    let file = document.getElementById(id);
                    file.setAttribute('src', srcfile);
                    $(file).removeClass('d-none');
                console.log(srcfile, srcQr, ext, id);
                ///}else{
                   // $('#imageModal').attr('src', srcfile);
                //}
                qr.setAttribute('src', srcQr);
                // fetch(urlUpload).then(function(response) {
                //     if(response.ok) {
                //         // console.log(response);
                //         return response.json();
                //     } else {
                //         console.log('Respuesta de red OK pero respuesta HTTP no OK');
                //     }
                // })
                // .catch(function(error) {
                //     console.log('Hubo un problema con la petición Fetch:' + error.message);
                // }).then(function(json){
                //     console.log(json);
                //     if(json.length){

                //     }
                // });
                
            });
        </script>
    </body>
</html>
