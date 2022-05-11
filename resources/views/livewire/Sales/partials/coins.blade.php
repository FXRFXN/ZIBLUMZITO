<div> 
<div class="row nt-9">
<div class="col-sn-12">
<div class="connect-sorting">
<h5 class="text-center mb-2">DENOMINACIONES</h5>
<div class="container">
<div class="row">
@foreach($denominations as $d)
<div class="col-sn nt-2">

<button wire:click.prevent="ACash({{$d->value}})" class="btn btn-dark btn-block den">
{{$d->value > 0 ? '$'. number_format ($d->value, 2, '.', ''): 'Exacto' }}
</button>
</div>
@endforeach
</div>
</div>
<div class="connect-sorting-content mt-4"></div>



<div class="card simple-title-task ui-sortable-handle">
<div class="card-body">
<div class="input-group input-group-nd mb-3">
<div class="input-group-prepend">
<span class="input-group-text input-Ep hideonsm" style="background: #3B3F5C; color:black">

</span>
</div>
<input type="number"  id="cash " wire:model="efectivo" wire.keydown.enter="saveSale" class="form.control text-center"
value="{{$efectivo}}">

<div class="input-group-append">
<span wire:click="$set('efectivo', 0)" class="input-group-text" style="
background: #383F5C; color:white">EFECTIVO
<i class="fas fa-backspace fa-2x"></i>
</span>
</div>
</div>

<h4 class="text-muted">Cambio:${{number_format($change, 2)}}</h4>


<div class="row justify-content-between mt-5">
<div class="col-sm-12 col-nd-12 col-lg-6">

@if($total > 0)
<button onclick= "Confirm('','clearCart','¿SEGURO DE ELIMINAR EL CARRITO?')"
class="btn btn-dark mtmobile">
CANCELAR F4
</button>
@endif
</div>

<div class="col-sm-12 col-md-12 col-lg-6">
@if($efectivo>=$total && $total > 0)
<button wire:click.prevent="saveSale" class="btn btn-dark btn-md btn-block">
GUARDAR F9</button>
@endif
</div>
</div>

</div>





</div>
</div>
</div>
</div>
</div>