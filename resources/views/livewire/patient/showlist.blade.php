<div class=" flex flex-col">
	<div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
		<div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
			<div class="shadow overflow-hidden border-b border-grey-200 sm:rounded-lg">
				<div class="md:grid md:grid-cols-6">
					
					<div class="bg-white px-4 py-3 border-t border-grey-200 sm:px-6 md:col-span-4 flex">
						<input wire:model="search" type="text" name="" class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="Buscar">
						@if($search != '')
						<x-jet-secondary-button wire:click="clearFilter" wire:loading.attr="disabled" class="form-input rounded-md shadow-sm mt-1 block ">
				            {{ __('X') }}
				        </x-jet-secondary-button>
				        @endif
					</div>
					<div class="bg-white px-4 py-3 sm:px-6 md:col-span-1">
						<select wire:model="perPage" class="form-input rounded-md shadow-sm mt-1 block border-none text-gray-500 text-sm">
							<option value="5">5 por página</option>
							<option value="10">10 por página</option>
							<option value="15">15 por página</option>
							<option value="25">25 por página</option>
							<option value="50">50 por página</option>
							<option value="100">100 por página</option>
						</select>
					</div>
					<div class="bg-white px-4 py-3 border-t border-grey-200 sm:px-6 md:col-span-1">
						<x-jet-button wire:loading.attr="disabled" class="float-right" wire:click="create()">
				            {{ __('Agregar') }}
				        </x-jet-button>
					</div>
				</div>
				<table class="min-w-full divide-grey-200 border-collapse">
				    <thead>
				        <tr>
				            <th>Nombre</th>
				            <th>Fecha de nacimiento</th>
				            <th>Género</th>
				            <th>Fecha de registro</th>
				            <th></th>
				        </tr>
				    </thead>
				    <tbody>
				    	@forelse($patients as $patient)
				    		<tr>
				    			<td class="text-center">{{$patient->name}}</td>
				    			<td class="text-center">{{$patient->birthday}}</td>
				    			<td class="text-center">{{$patient->gender}}</td>
				    			<td class="text-center">{{$patient->created_at}}</td>
				    			<td>
				    				<button type="button" class="btn mx-2" wire:click="edit({{$patient->id}})"><i class="fa fa-edit"></i> </button>
				    				<button type="button" class="btn mx-2" wire:click="$emit('triggerDelete', {{$patient->id}})"><i class="fa fa-trash"></i> </button>
				    			</td>
				    		</tr>
				    	@empty
				            <tr>
				                <td colspan="5" class="text-center">
				                @if($search!='')
				            	No se encontraron registros para la busqueda <strong>'{{$search}}'</strong> en la página {{$page}} al mostrar {{$perPage}} registros por página.
				            	@else
				            	No se encontraron registros en la base de datos.
				            	@endif
				            	</td>
				            </tr>
			            @endforelse
				    </tbody>
				</table>
				<div class="bg-white px-4 py-3 border-t border-grey-200 sm:px-6">
					{{$patients->links('pagination::tailwind')}}
				</div>
			</div>
		</div>
	</div>
	@if($isOpen)
	<div class="fixed z-100 w-full h-full bg-gray-500 opacity-75 top-0 left-0"></div>
    <div class="fixed z-101 w-full h-full top-0 left-0 overflow-y-auto">
        <div class="table w-full h-full py-6">
            <div class="table-cell text-center align-middle">
                <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="bg-white rounded-lg text-left overflow-hidden shadow-xl">
                        <form>
                            <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
	                            <div class="flex flex-wrap -mx-3 mb-6">
	                        		<div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
	                        			<div class="md:w-full">
		                        			<div class="flex flex-wrap bg-white px-4 py-3 sm:px-6 md:col-span-4 flex">
			                                	<label for="nameInput" class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
			                                	<input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nameInput" placeholder="Nombre del paciente" wire:model="name">
			                                	@error('name') <span class="text-red-500">{{ $message }}</span>@enderror
		                                	</div>
		                                	<div class="flex flex-wrap bg-white px-4 py-3 sm:px-6 md:col-span-4 flex">
			                                	<label for="birthdayInput" class="block text-gray-700 text-sm font-bold mb-2">Fecha de nacimiento:</label>
			                                	<input type="date" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="birthdayInput" placeholder="Cédula profesional" wire:model="birthday">
			                                	@error('birthday') <span class="text-red-500">{{ $message }}</span>@enderror
			                                </div>
			                                <div class="flex flex-wrap bg-white px-4 py-3 sm:px-6 md:col-span-4 flex">
			                                	<label for="genderInput" class="block text-gray-700 text-sm font-bold mb-2">Genero:</label>
			                                	<select type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="genderInput" placeholder="Especialidad" wire:model="gender">
			                                		<option value="" selected="">Seleccione el género</option>
			                                		<option value="Masculino">Masculino</option>
			                                		<option value="Femenino">Femenino</option>
			                                	</select>
			                                	@error('gender') <span class="text-red-500">{{ $message }}</span>@enderror
			                                </div>
			                            </div>
	                                </div>
	                                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0">
	                                	<h5>Citas programadas:</h5>
                                		<ul class="list-disc">
                                		@foreach($appointments as $appointment)
                                			<li>El {{$appointment->date}} a las {{$appointment->hour}}</li>
                                		@endforeach
                                		</ul>
	                                </div>
	                            </div>
                            </div>
                            <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
	                            <span class="flex w-full sm:ml-3 sm:w-auto">
	                                <button wire:click.prevent="store()" type="button" class="inline-flex bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Guardar</button>
	                            </span>
	                            <span class="mt-3 flex w-full sm:mt-0 sm:w-auto">
	                                <button wire:click="closeModal()" type="button" class="inline-flex bg-white hover:bg-gray-200 border border-gray-300 text-gray-500 font-bold py-2 px-4 rounded">Cancelar</button>
	                            </span>
                            </div>
                        </form> 
                    </div>
                </div>
            </div>
        </div>
    </div>
	@endif
</div>
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.js"></script>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function () {

        @this.on('triggerDelete', companyId => {
            Swal.fire({
                title: '¿Está seguro?',
                text: 'El registro del paciente será borrado , al igual que las citas programadas con el.',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '¡Sí eliminar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.value) {
                    @this.call('delete',companyId)
                } else {
                    console.log("Canceled");
                }
            });
        });
    })
</script>
@endpush