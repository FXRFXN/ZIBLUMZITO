<div wire:ignore.self class="modal fade" id="theModal" tabindex="-1" data-keyboard="false" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header bg-dark">

        <h5 class="modal-title text-black" >
            <b>{{ $componentName }} </b> | {{ $selected_id > 0 ? 'EDITAR' : 'CREAR'}}
        </h5>
<h6 class="text-center text-warning" wire:loading>PORFAVOR ESPERE</h6>
      </div>
      <div class="modal-body">


        <div class="col-sm-12">

          <div class="input-group">
          <div class="input-group-prepend">
              <span class="input-group-text">
                  <span class="fas fa-edit">
          
                  </span>
              </span>
          
          </div>
          <input type="text" wire:model.lazy="roleName" clas="form-control" placeholder="ej: cursos"> 
          </div>
          @error('roleName') <span class="text-danger er">{{$message}}</span> @enderror
          </div>
      </div>
      <div class="modal-footer">
      <button type="button" wire:click.prevent="resetUI()" 
      class="btn btn-dark close-btn text-info" data-dismiss="modal">CERRAR</button>  

 
      @if($selected_id < 1)
      <button type="button" wire:click.prevent="CreateRole()"
       class="btn btn-dark close-modal">GUARDAR</button>  
      @else 
      <button type="button" wire:click.prevent="UpdateRole()" 
      class="btn btn-dark close-modal" >ACTUALIZAR</button>  
      @endif


      </div>
    </div>
  </div>
</div>