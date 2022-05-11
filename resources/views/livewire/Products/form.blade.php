@include('common.modalHead')
<div>                                                 
<div class="row">       
<div class="col-sm-12 col-md-8">
<div class="form-group">
<label >Nombre</label>
<input type="text" wire:model.lazy="name" class="form-control" placeholder="ej: Torta de pollo" >
@error('name') <span class="taxt-danger er">{{ $message}}</span>@enderror
</div>
</div>

<div class="col-sm-12 col-md-4">
<div class="form-group">
<label >Código de barras</label>
<input type="text" wire:model.lazy="barcode" class="form-control" placeholder="ej: 123456" >
@error('barcode') <span class="text-danger er">{{ $message }}</span>@enderror


</div>
</div>


<div class="col-sm-12 col-md-4">
<div class="form-group">
<label >Costo de elaboracion</label>
<input type="text" data-type='currency' wire:model.lazy="cost" class="form-control" placeholder="ej: 20" >
@error('cost') <span class="text-danger er">{{ $message }}</span>@enderror


</div>
</div>



<div class="col-sm-12 col-md-4">
<div class="form-group">
<label >Precio de venta</label>
<input type="text"data-type='currency' wire:model.lazy="price" class="form-control" placeholder="ej: 30" >
@error('price') <span class="text-danger er">{{ $message }}</span>@enderror


</div>
</div>

<div class="col-sm-12 col-md-4">
<div class="form-group">
<label >Stock</label>
<input type="number" wire:model.lazy="stock" class="form-control" placeholder="ej: 100" >
@error('stock') <span class="text-danger er">{{ $message }}</span>@enderror


</div>
</div>

<div class="col-sm-12 col-md-4">
<div class="form-group">
<label >Inventario restante</label>
<input type="number" wire:model.lazy="alerts" class="form-control" placeholder="ej: 20" >
@error('alerts') <span class="text-danger er">{{ $message }}</span>@enderror


</div>
</div>

<div class="col-sm-12 col-md-4">
<div class="form-group">
<label >Categoria</label> 
<select wire:model ='categoryid'class="form-control">
    <option value="Elegir" disabled>Elegir</option>
    @foreach($categories as $category)

    <option value="{{$category->id}}" >"{{$category->name}}"</option>
    @endforeach
</select>
@error('categoryid') <span class="text-danger er">{{ $message}}</span>@enderror
</div>
</div>


<div class="col-sm-12 col-md-8">
<div class="form-group custom-file">
<imput type="file" class="custom-file-input form-control" wire:model="image"
accept="image/x-png, image/gif, image/jpeg"
>
<label class="custom-file-label">Imágen {{$image}}</label>
@error('image') <span class="text-danger er">{{ $message}}</span>@enderror
</div>
</div>

</div>








@include('common.modalFooter')