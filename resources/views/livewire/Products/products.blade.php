
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
    <th class="table-th text-white">DESCRIPCION</th>
    <th class="table-th text-white">CODIGO DE BARRAS</th>
    <th class="table-th text-white">CATEGORIA</th>
    <th class="table-th text-white">PRECIO</th>
    <!-- <th class="table-th text-white">STOCK</th>-->
   <!-- <th class="table-th text-white">INV.MIN</th> -->
 
    <th class="table-th text-white">ACCIONES</th>
</tr>
</thead>
<tbody>

@foreach($data as $product)
    <tr>
        
           <td> <h6>{{$product->name}}</h6></td>
           <td> <h6>{{$product->barcode}}</h6></td>
           <td> <h6>{{$product->category}}</h6></td>
           <td> <h6>{{$product->price}}</h6></td>
          <!--  <td> <h6>{{$product->stock}}</h6></td>-->
           <!-- td> <h6>//{{$product->alerts}}</h6></>-->
         






         

        <td class="text-center">
          
            <a href="javascript:void(0)" 
            button type="button"wire:click.prevent="Edit({{$product->id}})"
             class="btn btn-dark mtmobile" title="Edit">EDITAR</button>
                
            </a>

            <a href="javascript:void(0)"
            button type="button" onclick="Confirm('{{$product->id}}')" 
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
@include('livewire.products.form')
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
 
      window.livewire.on('product-added', msg =>{
          $('#theModal').modal('hide')
      });  
      window.livewire.on('product-updated ', msg =>{
          $('#theModal').modal('hide')  
         });   
            window.livewire.on('product-deleted', msg =>{
         
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
