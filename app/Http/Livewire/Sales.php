<?php

namespace App\Http\Livewire;

use App\Models\Sale;
use App\Models\SaleDetails;
use App\Models\Product;
use Livewire\Component;
use App\Models\SaleDetail;
use App\Models\Denomination;
use Illuminate\Support\Facades\DB;
use Darryldecode\Cart\Facades\CartFacade as Cart;

class Sales extends Component


{

//variables

    public $total,$itemsQuantity, $efectivo, $change;

/*atraves de este metodo definimos los valores que tendran las variables definidas anteriormente */
    public function mount()
    {
         $this->efectivo = 0;
         $this->change = 0;
         $this->change = Cart::getTotal();
         $this->itemsQuantity =Cart::getTotalQuantity();

    }
    /*función de las condiciones de la paginación. através del if condición y se evalua si una condicion es verdadera 
o falsa si la paginación es mayor a 5 entonces se agrega una segunda lista, si no se cumple esta condición entonces evalua 
los datos de la categoria y no crea una nueva paginacion*/
    public function render()
    {
        return view('livewire.sales.sales',[
            'denominations' => Denomination::orderBy('value','desc')->get(),
            'cart' =>Cart::getContent()->sortBy('name')
        ])
        ->extends('layouts.theme.app')
        ->section('content');

    }


/*Esta funcion permite obtener el cambio a dar, se llama a la variable efectivo que servira para obtener el valor de la
compra, el 2do this llama a la  variable cami¿bio y se ejecuta una operacion matematica, se resta el efectivo del total
*/
    public function ACash($value){
        $this->efectivo += ($value  == 0 ? $this->total : $value);
        $this->change = ($this->efectivo - $this->total);

    }
    public function updatedEfectivo($value)
    {
        $efectivoZero = ($value === '' ? 0 : $value);
        $this->change = ($efectivoZero - $this->total);
    }

    protected $listeners =[
        
        'scan-code' => 'scanCode',
        'removeItem' => 'removeItem',
        'clearCart' => 'clearCart',
        'saveSale' => 'saveSale'

    ];

    /*Metodo encargado de capturar el codigo de barras , atraves de este
    se definen varias variables y varias eventos que posteriormente los scripts evaluaran*/
public function scanCode($name, $cant = 1)
{

$product = Product::where('name',$name)->first();
if($product == null || empty($product))
{
    $this->emit('scan-notfun', 'El producto no esta registrado');
}else
if($this-> Incart($product->id))
{
    $this->increaseQuantity($product->id);
    return;
}

if ($product->stock < 1)
{
    $this->emit('no-stock', 'Stock insuficiente');
    return;
}

Cart::add($product-> id, $product->name, $product->price,$cant, $product->image);
$this->total = Cart::getTotal();
$this->emit('scan-ok', 'Prosucto agrgado');
}

//creacion del metodo incart- a este metodo le pasaremos el producto atraves de su id-validara si el id del producto ya existe en el carrito
//se define la variable exist y atraves de ella le pasamos el producto id que se esta mandando desde el metodo scancode
public function Incart($productId)
    {
    $exist = Cart::get($productId); 
    if($exist)
    return true;
    else 
    return false; 



}

//metodo increaseQty actualizara la cantidad de la existencia del producto en el carrito
//le pasamos el producto y la cantidad 
//definimos una variable para manejar los mensajes q retornareos en la vista, el title lo vamos a ir llenando atraves de la accion a realizar
//buscamos el producto atraves del product id
public function increaseQty($product, $cant = 1)
{
        $title= '';
        $product = Product::find($productId);
        $exist = Cart::get($productId);
        if($exist)
        $title = 'Cantidad actualziada';
        else 
        $title = 'producto agregado ';

        if($exist)

        {
            if($product->stock < ($cant + $exist->quantity))
            {
                $this->emit('no-stock', 'Stock insuficiente');
                return; 
            }
        }
//add-actualiza la cantidad e informacion del producto y en caso de que no exista lo inserta
        Cart::add($product->id, + $product->name,$product->price, $cant, $product->image);
        $this->total = Cart::getTotal();
        $this->itemsQuantity = Cart::getTotalQuantity();
        $this->emit('scan-ok', $title);


}

//este metodo remplazara completamente la informacion del proucto dentro del carrito
public function updateQty($productId, $cant = 1)
{
    $title = '';
    $product = Product::find($productId);
    $exist = Cart::get($productId);

    if($exist)
    $title = 'Cantidad actualziada';
    else 
    $title = 'producto agregado ';


    if($exist)
    {
        if($product->stock < $cant )
        {
            $this->emit('no-stock', 'Stock insuficiente');
            return; 
        }
    }


$this->removeItem($productId);
if($cant >  0)
{
    Cart::add ($product->id, $product->name, $product->price, $cant, $product->image);
    $this->total = Cart::getTotal();
    $this->itemsQuantity = Cart::getTotalQuantity();
    $this->emit('scan-ok', $title);



}

    
}


public function removeItem($productId)
{
Cart::remove($productId);
    $this->total = Cart::getTotal();
    $this->itemsQuantity =  Cart::getTotalQuantity();
    $this->emit('scan-ok','Producto eliminado');
}
    
 public function decreaseQty ($productId)
{
    $item = Cart::get($productId);
    Cart::remove($productId);

    $newQty = ($item->quantity) -1;

    if($newQty > 0)
    Cart::add($item->id, $item->name, $item->price, $newQty, $item->attributes[0]);

    $this->total = Cart::getTotal();
    $this->itemsQuantity =  Cart::getTotalQuantity();
    $this->emit('scan-ok','Cantidad actualziada');
}


public function clearCart()

{
    Cart::clear();
    $this->efectivo = 0;
    $this->change = 0;

    $this->total = Cart::getTotal();
    $this->itemsQuantity =Cart::getTotalQuantity();
    $this->emit('scan-ok','Carrito vacio');



}
/*Este es el metodo de guardar la venta atraves de este se guarda la informacion 
de la venta realizada*/
public function saveSale()
{
    if($this->total <=0)
    {
        $this->emit('sale-error','Agrega productos a la venta');
        return;
    }
    
    if($this->efectivo <=0)
    {
        $this->emit('sale-error','Ingresa el efectivo');
        return;
    }
    
    if($this->total > $this->efectivo)
    {
        $this->emit('sale-error','El efectivo debe ser mayor o igual al total');
        return;
    }

    DB::beginTransaction();


    try{

        $sale = Sale::create ([
            'total' => $this->total,
            'items' => $this->itemsQuantity,
            'cash' => $this->efectivo,
            'change' => $this->change,
            'user_id' => Auth()->user()->id
           

        ]); 

        if($sale)
        {
            $items = Cart::getContent();
            foreach ($items as $item){
                SaleDetails::create([
                    'price' =>$item->price,
                    'quantity' =>$item->quantity,
                    'product_id' =>$item->id,
                    'sale' =>$sale->id
                
        
                ]);

                //actualziacion de stock

                $product = Product::find($item->id);
                $product->stock = $product->stock - $item->quantity;
                $product->save();
            }
        }



        DB::commit();
        Cart::clear();
        $this->efectivo=0;
        $this->change=0;


        $this->total = Cart::getTotal();
    $this->itemsQuantity =  Cart::getTotalQuantity();
    $this->emit('sale-ok','Venta registrada exitosamente ');
    $this->emit('print-ticket', $sale->id);

    }catch(Exception $e){
        DB::rollback();
        $this->emit('sale-error', $e->getMessage());
    }
}






}
    





