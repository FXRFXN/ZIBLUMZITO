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
    //definición de variables 
public $name, $barcode, $cost, $price, $stock, $alerts,
       $categoryid, $search, $image, $selected_id, $pageTitle, $componentName;
         //variable privadas
private $pagination = 5;

 //función de paginación retorna a otra página de la misma, despues de tener más de 5 productos listados
public function paginationview()
{
    return'vendor.livewire.bootstrap';
 }
 
//función para cambio de nombre del encabezado
 public function mount()
    {
        //this llama el título de la página y del nombre del componente 
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
    
 /*la función store permite recibir y validar los mensajes de alerta en los cuales se definen 
    las reglas de creación o actualización, y los mensajes de alerta*/
  
    public function Store()
    {
        //reglas
        
    $rules = [
            'name'=> 'required|unique:products|min:3',
            'cost' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'alerts' => 'required',
            'categoryid' => 'required|not_in:Elegir'
            
           //mensajes
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
       //this llama las variables rules y messages y pasa estos valores a las variable category y crea un nuevo registro
 
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

         
        //Añadir una imagen imagen 
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

    /*funcion editar, le pasamos el valor de id a la función edit.record busca entre las categorías y através de un array
busca el id. $this llama al nombre de la variable record y devuelve un nombre
selecciona un id que se guarda en la variable record
busca una imagen
return recibe la llamada edit y retorna los scripts que estan definidos en el .blade*/

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
     /*función actualizar: atraves de esta funcion se define en las variables las reglas 
    y mensajes que se mostraran al usuario a la hora de actualizar.*/


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
 
         //this se encarga de leer las variables y las reglas 
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
/* la condicion if se encarga de evaluar si la condicion se cumple y si esta es correcta se
concatena y busca la extension de la imagen, en este caso se encarga de verificar si la imagen es de tipo
png, jpg, uan ves se evalua esto, el metodo this llama a la variable image y la imagen seleccionada 
se busca en la carpeta public dentro de una subcarpeta llamda categories y atraves de la variable customFilename
se carga el nombre que tiene por defecto la imagen.  */
         
      
         if($this->image)
         {

         $customFileName = uniqid() . '_.' . $this->image->extension();
         $this->image->storeAs('public/products', $customFileName);
         $product->image = $customFileName;
         $imageTemp = $product->image;
        
         
         $product->save();
         
           /*si no se encuentra imagen entonces se evalua de nuevo la condicion y se concatena la variable */
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


         //funcion eliminar: permite eliminar una categoria. atraves de la variable category se encarga de evaluar y buscar
/*el id del elemento a eliminar, cuando el id de la categoria es encontrada entonces se ejecuta el metodo delete
que eliminara el id de la categoria y por lo tanto se eliminara la categoria junto con su imagen asignada
la imegen se eliminara desde la carpeta raiz*/   
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
