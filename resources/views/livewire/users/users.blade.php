<div>
    <div class="row sales layout-top-spacing">
        <div class="col-sm-12">
        <div class="widget widget-chart-one">
        <div class="widget-heading">
        <h4 class="card-title">
        <b>{{$componentName}} | {{$pageTitle}}</b>
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
            <th class="table-th text-white">USUARIO</th>
            <th class="table-th text-white">TELEFONO</th>
            <th class="table-th text-white">EMAIL</th>
            <th class="table-th text-white">PERFIL</th>
            <th class="table-th text-white">ESTADO</th>
            <th class="table-th text-white">IMAGEN</th>
            <th class="table-th text-white">ACTION</th>
        </tr>
        </thead>
        <tbody>
            @foreach($data as $r)
            <tr>
                
                   <td><h6>{{$r->name}}</h6></td>
                   <td class="text-center"> <h6>{{$r->phone}}</h6></td>
                   <td class="text-center"> <h6>{{$r->email}}</h6></td>
                   <td class="text-center"> 
                       <span class="badge {{$r->status == 'Active' ? 'badge-success' : 'badge-danger'}} text-uppercase"> {{$r->status}}
                       </span>
                   </td>
                   <td class="text-center text-uppercase" > <h6>{{$r->profile}}</h6></td> 
                   



                    <td class="text-center">
                        @if($r->image !=null)
                        <img src="{{asset('storage/users/'. $r->image)}}" alt="imagen"  class="car-img-top-img-fluid">
                        @endif
                    <span>
                       
                    </span>
                </td>
        
                <td class="text-center">
                    <a href="javascript:void(0)" 
                    button type="button"
                    wire:click="edit({{$r->id}})"
                    class="btn btn-dark mtmobile" title="Edit">
                       EDITAR</button>
                    </a>
        
                    <a href="javascript:void(0)" 
                    button type="button"
                    onclick="Confirm('{{$r->id}}')"
                    class="btn btn-dark" title="Delete">
                     ELIMINAR</button>
                    </a>
        
        
                </td>
            </tr>
            @endforeach
        </tbody>
        </table>
        {{$data->links()}}
        </div>
        
        </div>
        </div>
        </div>
        @include('livewire.users.form')
        </div>
        
        
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                window.livewire.on('user-added', Msg =>{
                    $('#theModal').modal('hide')
                    noty(Msg)

                });
                window.livewire.on('user-updated', Msg =>{
                    $('#theModal').modal('hide')
                    noty(Msg)

                });
                window.livewire.on('user-deleted', Msg =>{
                   
                    noty(Msg)

                });
                window.livewire.on('hide-modal', Msg =>{
                    $('#theModal').modal('hide')
                  
                });
                window.livewire.on('show-modal', Msg =>{
                    $('#theModal').modal('show')
                  
                });
                window.livewire.on('user-withsales', Msg =>{
                   noty(Msg)
                   

                });
                
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
                 window.livewire.emit('deleteRow', id)
                 swal.close()
             }

         });

         } 
        </script>
        
</div>
