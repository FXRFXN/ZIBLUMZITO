<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

class Products extends Component
{

    use WithFileUploads;
    use WithPagination;

public $name, $barcode, $cost, $price, $stock, $alerts,
       $categoryid, $search, $image, $selected_id, $pageTitle, $componentName;
private $pagination = 5;


public function paginationview()
{
    return'vendor.livewire.bootstrap';
 }
 

 public function mount()
    {
     $this->pageTitle ='Listado';
     $this->componentName ='Productos';
     $this->categoryid ='Elegir';


    }


    public function render()
    {

        if(strlen($this->search) > 0 )
        $products = Product::join('categories as c','c.id','products.category_id' )

        ->select('products.*','c.name as category')
        ->where('products.name', 'like', '%'. $this -> search. '%')
        ->orwhere('products.barcode', 'like', '$'. $this->search. '%')
        ->orwhere('c. name', 'like', '%'. $this->search. '%')
        ->orderby('products.name', 'asc')
        ->paginate($this->pagination);
      
    
        else 
        $products = Product::join('categories as c','c.id','products.category_id' )
        ->select('products.*','c.name as category')
        ->orderBy('products.name','asc')
        ->paginate($this->pagination);

        return view('livewire.products.products', [
            'data' => $products,
            'categories' =>Category::orderBy('name', 'asc')->get()
        ])
        ->extends('layouts.theme.app')
        ->section('content') ;
    }

    public function Store()
    {
        
    $rules = [
            'name'=> 'required|unique:products|min:3',
            'cost' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'alerts' => 'required',
            'categoryid' => 'required|not_in:Elegir'
           
     ]; 
      $messages = [
          'name.required' =>'Nombre de la categoria es requerida',
          'name.unique' => 'El nombre de la categoria es existente, porfavor intente con otra',
          'name.min' => 'El nombre de la categoria debe tener almenos 3 caracteres',
          'cost.required' =>'Nombre de la categoria es requerida',
          'price.required' => 'El nombre de la categoria es existente, porfavor intente con otra',
          'stock.required' => 'El nombre de la categoria debe tener almenos 3 caracteres',
          'alerts.required' =>'Nombre de la categoria es requerida',
          'categoryid.not_in' => 'El nombre de la categoria es existente, porfavor intente con otra',
          
      ];
 
           $this->validate($rules, $messages);


           $product = Product::create([
            
               'name' => $this->name,
               'cost' => $this->cost,
               'price' => $this->price,
               'barcode' => $this->barcode,
               'stock' => $this->stock,
               'alerts' => $this->alerts,
               'category_id' => $this->categoryid
              
           ]);

         
      
         if($this->image)
         {

         $customFileName = uniqid() . '_.' . $this->image->extension();
         $this->image->storeAs('public/products', $customFileName);
         $category->image = $customFileName;
         $category->save();
         }

         $this->resetUI();
         $this->emit('product-added', 'Producto Registrada');

    }


    public function Edit(Product $product)
    {
       
        
        $this-> selected_id = $product->id;
        $this-> name = $product->name;
        $this-> barcode = $product->barcode;
        $this->cost = $product->cost;
        $this->price = $product->price;
        $this->stock = $product->stock;
        $this->alerts = $product->alerts;
         $this->category_id = $product->category_id;
         $this-> image = null;


        $this->emit('show-modal', 'show modal!');

    }


    public function Update()
    {
        
    $rules = [
            'name'=> "required|min:3|unique:products,name,{$this->selected_id}",
            'cost' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'alerts' => 'required',
            'categoryid' => 'required|not_in:Elegir'
           
     ]; 
     
      $messages = [
          'name.required' =>'Nombre de la categoria es requerida',
          'name.unique' => 'El nombre de la categoria es existente, porfavor intente con otra',
          'name.min' => 'El nombre de la categoria debe tener almenos 3 caracteres',
          'cost.required' =>'Nombre de la categoria es requerida',
          'price.required' => 'El nombre de la categoria es existente, porfavor intente con otra',
          'stock.required' => 'El nombre de la categoria debe tener almenos 3 caracteres',
          'alerts.required' =>'Nombre de la categoria es requerida',
          'categoryid.not_in' => 'El nombre de la categoria es existente, porfavor intente con otra',
          
      ];
 
           $this->validate($rules, $messages);

           $product = Product::find($this->selected_id);
            $product->update([         
               'name' => $this->name,
               'cost' => $this->cost,
               'price' => $this->price,
               'barcode' => $this->barcode,
               'stock' => $this->stock,
               'alerts' => $this->alerts,
               'category_id' => $this->categoryid
              
           ]);

         
      
         if($this->image)
         {

         $customFileName = uniqid() . '_.' . $this->image->extension();
         $this->image->storeAs('public/products', $customFileName);
         $product->image = $customFileName;
         $imageTemp = $product->image;
        
         
         $product->save();
         
         
         if($imageTemp !=null){

           
            if(file_exists('storage/products/'. $imageTemp )) {
                unlink ('storage/products/'. $imageTemp );
            
         }
        }
        

        }

         $this->resetUI();
         $this->emit('product-updated', 'Producto Registrado');

    }




    public function resetUI()
    {
        $this->name = '';
        $this->barcode = '';
        $this->cost = '';
        $this->price = '';
        $this->stock = '';
        $this->alerts = '';
        $this->search = '';
        $this->categoryid = 'Elegir';
        $this->image = null;
        $this->selected_id = 0;
        
        
        
    }
            protected $listeners =['deleteRow' => 'Destroy'];


            
    public function Destroy(Product $product)
    {
      
        //dd($category);
        $imageTemp = $product->image;
        $product->delete();

        if($imageTemp !=null){
           if(file_exists('storage/products/'. $imageTemp)){
               unlink('storage/products/'. $imageTemp);
             
    

        }
        $this->resetUI();
        $this->emit('product-deleted', 'Categoria eliminada');


        
    }
}







}
