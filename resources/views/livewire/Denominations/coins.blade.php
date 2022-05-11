
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

<div class="table-responsive">
<table class="table table-bordered table striped mt-1">
<thead class="text-white" style="background: #3b3f53C">
<tr>
    <th class="table-th text-black">TIPO</th>
    <th class="table-th text-black">VALOR</th>
    <th class="table-th text-black">IMAGEN</th>
    <th class="table-th text-black">ACTION</th>
</tr>
</thead>
<tbody>
    @foreach($data as $coin)
    <tr>
        <td>
           
            <h6>{{$coin->type}}</h6>
            <h6>${{number_format($coin->value,2)}}</h6>

            <td class="text-center"></td>
            <span>
                <img src="{{asset('storage/denominations/' .$coin->imagen) }}" 
                alt="imagen de ejemplo" height="70" width="80" class="rounded">
            </span>
        </td>



        <td class="text-center">
            <a href="javascript:void(0)" 
            button type="button"
            wire:click="Edit({{ $coin->id}})"
            class="btn btn-dark mtmobile" title="Edit">
             EDITAR</button>
            </a>

            <a href="javascript:void(0)" 
            button type="button"
            onclick="Confirm('{{$coin->id}}')"
           
            class="btn btn-dark" title="Delete">
              ELIMINAR
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
@include('livewire.denominations.form')

</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
 
      window.livewire.on('item-added', msg =>{
          $('#theModal').modal('hide')
      });  
      window.livewire.on('item-updated ', msg =>{
          $('#theModal').modal('hide')  
         });   
            window.livewire.on('item-deleted', msg =>{
              
      });  
      window.livewire.on('show-modal', msg =>{
          $('#theModal').modal('show')  
         });  

         window.livewire.on('modal-hide ', msg =>{
          $('#theModal').modal('hide')  
         });  
         
         window.livewire.on('hidden.bs.modal ', function(e){
            $('.er').css('display', 'none')
            

            });
 
         }); 
         function Confirm(id, coins)
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

         })
         
    
       }
    

</script>

