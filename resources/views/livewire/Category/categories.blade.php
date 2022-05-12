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
    <thead class="text-white" style="background: #3B3F5C">
    <tr>
    <th class="table-th text-white">DESCRIPCION</th>
    <th class="table-th text-white">IMAGEN</th>
    <th class="table-th text-white">ACCIONES</th>
    
</tr>
</thead>
<tbody>
    @foreach($categories as $category)
    <tr>
      
              <td><h6>{{$category->name}}</h6></td>


            <td class="text-center">

            <span>
                <img src="{{ asset('storage/categories/' .$category->imagen) }}" 
                alt="Imagen" height="70" width="80" class="rounded">
            </span>
                </td>

     

        <td class="text-center">
           <a href="javascript:void(0)" 
            button type="button" wire:click="Edit({{ $category->id}})"
            class="btn btn-dark mtmobile" title="Edit">EDITAR</button>
                
            </a>

            <a href="javascript:void(0)" 
            button type="button"
            onclick="Confirm('{{$category->id}}', 
            '{{$category->products->count()}}')"
            class="btn btn-dark" title="Delete">ElIMINAR</button>
              
                <i class="fas fa-trash"></i>
            </a>
          

        </td>
    </tr>
    @endforeach

</tbody>
</table>
 {{$categories->links()}}
</div>

</div>
</div>
</div>
@include('livewire.category.form')

</div>


<script>
    
    document.addEventListener('DOMContentLoaded', function() {
        
      window.livewire.on('show-modal', msg =>{
          $('#theModal').modal('show')
      })
     
      window.livewire.on('category-added', msg =>{
          $('#theModal').modal('hide')
      })
      window.livewire.on('category-updated ', msg =>{
          $('#theModal').modal('hide')
      })

    })

    function Confirm(id, products)
   
    { 
        if(products > 0)
        {
            swal('NO SE PUEDE ELIMINAR LA CATEGORIA')
            return;
        }
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
