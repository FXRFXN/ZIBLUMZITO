<div>
    {{-- Care about people's approval and you will be their prisoner. --}}

    
        <div class="row sales layout-top-spacing">
            <div class="col-sm-12">
            <div class="widget widget-chart-one">
            <div class="widget-heading">
            <h4 class="card-title">
            <b>{{ $componentName}} | {{$pageTitle}}</b>
            </h4>
             
            <ul class="tabs tab-pills">
            
            <li>
            <a href="javascript:void(0)" class="tabmenu bg-dark" data-toggle="modal" data-target="
            #theModal">Agregar</a>
            </li>
            
            </ul>
            
            </div>
            @include('common.searchbox')
            <div class="widget-content">
            
            <div class="table-reponsive">
            <table class="table table-bordered table striped mt-1">
            <thead class="text-white" style="background: #3B3F5C">
            <tr>
                <th class="table-th text-white">ID</th>
                <th class="table-th text-white">DESCRIPCION</th>
                <th class="table-th text-white">ACTION</th>
            </tr>
            </thead>
            <tbody>
    
            @foreach ($permisos as $permiso)
                <tr>
                    
                       <td> <h6>{{$permiso->id}}</h6></td>
                        <td class="text-center">
                       <h6>{{$permiso->name}}</h6>
                    </td>
            
                    <td class="text-center"> 
                        <a href="javascript:void(0)" 
                        button type="button"
                        wire:click="Edit({{$permiso->id}})"
                        class="btn btn-dark mtmobile" title="Editar registro">
                            EDITAR </button>
                        </a>
            
                        <a href="javascript:void(0)" 
                        button type="button"
                        onclick="Confirm('{{$permiso->id}}')"  
                        class="btn btn-dark" title="Eliminar registro">
                        ELIMINAR</button>
                        </a>
            
            
                    </td> 
                </tr>
                @endforeach
            </tbody>
            </table>
            {{$permisos->links()}}
            </div>
            
            </div>
            </div>
            </div>
            @include('livewire.permisos.form')
            
            </div>
            
            
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    window.livewire.on('permiso-added', Msg =>{
                        $('#theModal').modal('hide')
                        
                    })
    
                    window.livewire.on('permiso-updated', Msg =>{
                        $('#theModal').modal('hide')
                        noty(Msg)
                    })
    
    
                    window.livewire.on('permiso-deleted', Msg =>{
             
                        noty(Msg)
                    })
    
                    window.livewire.on('permiso-exists', Msg =>{
                       
                        noty(Msg)
                    })
                    window.livewire.on('permiso-error', Msg =>{
                      
                        noty(Msg)
                    })
    
                    window.livewire.on('hide-modal', Msg =>{
                        $('#theModal').modal('hide')
                     
                    })
    
                    window.livewire.on('show-modal', Msg =>{
                        $('#theModal').modal('show')
                        
                    })
    
                   
                });
    
    
                function Confirm(id)
       
       { 
         
            swal({
               title: 'CONFIRMAR',
               text: '¿CONFIRMAS ELIMINAR EL REGISTRO?',
               type: 'warning',
               showCancelButton: true,
               cancelButtonText: 'Cerrar',
               cancelouttoncolor: '#fff',
               confirmButtonColor: '#383F5C',
               confirmButtonText: 'Aceptar'
    
            }).then(function(result){
                if(result.value){
                    window.livewire.emit('destroy', id)
                    swal.close()
                }
    
            })
       }
            </script>
            
    
