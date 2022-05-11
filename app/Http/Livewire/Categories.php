<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Http\Livewire\Categories;
use Illuminate\Support\Facades\Storage;

class Categories extends Component

{

    use WithFileUploads;
    use WithPagination;
//definición de variables 
    public $name, $search, $image, $selected_id, $pageTitle, $componentName;
    //funcion privada 
    private $pagination = 5;
//función para cambio de nombre del encabezado
    public function mount()
    {
        //this llama el título de la página y del nombre del componente 
     $this->pageTitle ='Listado';
     $this->componentName ='Categorias';


    }
    //función de paginación retorna a otra página de la misma, despues de tener más de 5 productos listados
    public function paginationview()
    {
       return'vendor.livewire.bootstrap';
    }
    
/*función de las condiciones de la paginación. através del if condición y se evalua si una condicion es verdadera 
o falsa si la paginación es mayor a 5 entonces se agrega una segunda lista, si no se cumple esta condición entonces evalua 
los datos de la categoria y no crea una nueva paginacion*/
   
public function render()
    {
        if(strlen($this->search) > 0 )
        $data = Category::where('name', 'like', '%'. $this->search. '%')->paginate($this->pagination);
        else 
        $data = Category::orderBy('id', 'desc')->paginate($this->pagination);

        return view('livewire.category.categories', ['categories' => $data])
        ->extends('layouts.theme.app')
        ->section('content') ;
    }
/*funcion editar, le pasamos el valor de id a la función edit.record busca entre las categorías y através de un array
busca el id. $this llama al nombre de la variable record y devuelve un nombre
selecciona un id que se guarda en la variable record
busca una imagen
return recibe la llamada edit y retorna los scripts que estan definidos en el .blade*/

    public function Edit($id)
    {
        $record = Category::find($id, ['id', 'name', 'image']);
        
        $this-> name = $record->name;
        $this-> selected_id = $record->id;
        $this-> image = null;

        $this->emit('show-modal', 'show modal!');

    }
    /*la función store permite recibir y validar los mensajes de alerta en los cuales se definen 
    las reglas de creación o actualización, y los mensajes de alerta*/
  

    public function Store()
    {
        //reglas
    $rules = [
            'name'=> 'required|unique:categories|min:3'   
    //mensajes
     ]; 
      $messages = [
          'name.required' =>'Nombre de la categoria es requerida',
          'name.unique' => 'El nombre de la categoria es existente, porfavor intente con otra',
          'name.min' => 'El nombre de la categoria debe tener almenos 3 caracteres'
      
      ];
   //this llama las variables rules y messages y pasa estos valores a las variable category y crea un nuevo registro
           $this->validate($rules, $messages);


           $category = Category::create([
            
            
               'name' => $this->name
           ]);

         //imagen 
         $customFileName;
         if($this->image)
         {

         $customFileName = uniqid() . '_.' . $this->image->extension();
         $this->image->storeAs('public/categories', $customFileName);
         $category->image = $customFileName;
         $category->save();
         }

         $this->resetUI();
         //se emite un mensaje, que mas adelante los scripts de las vistas mostraran 
         
         $this->emit('category-added', 'categoria Registrada');

    }

    /*función actualizar: atraves de esta funcion se define en las variables las reglas 
    y mensajes que se mostraran al usuario a la hora de actualizar.*/

    public function Update()
    {
        $rules = [
            'name' => "required|min:3|unique:categories,name, 
            {$this->selected_id}"

        ];

        $messages = [
            'name.required' => 'Nombre de categoría requerido',
            'name.min' => 'El nombre de 1la categoria debe tener al nenos 3 caracteres',
            'name.unique' => 'El nombre de la categoría ya existe'
        ];
        //this se encarga de leer las variables y las reglas 
        
        $this->validate($rules, $messages);
/*la variables category compara la informacion y atraves del this se hace la busqueda del id de la categoria, entonces si el id de la categoria es 
encontrada  se añade el metodo update que permitira actualizar una nueva categoria*/


        $category = Category::find($this->selected_id);
        
        $category->update([
            
                'name'=> $this->name
        ]);
/* la condicion if se encarga de evaluar si la condicion se cumple y si esta es correcta se
concatena y busca la extension de la imagen, en este caso se encarga de verificar si la imagen es de tipo
png, jpg, uan ves se evalua esto, el metodo this llama a la variable image y la imagen seleccionada 
se busca en la carpeta public dentro de una subcarpeta llamda categories y atraves de la variable customFilename
se carga el nombre que tiene por defecto la imagen.  */

        if($this->image)
        {
        $customFileName = uniqid() . '_.' . $this->image->extension();
        $this->image->storeAs ('public/categories', $customFileName);
        $imageName = $category->image;

      /*la variable category llama a la variable image y esta verfica el nombre que recibe la imagen y el metodo save
      guarda la imagen en el sistema */
        $category->image = $customFileName;
        $category->save();
        /*si no se encuentra imagen entonces se evalua de nuevo la condicion y se concatena la variable */
        if($imageName !=null)
        {
        if (file_exists('storage/categories'. $imageName))
        {
        unlink ('storage/categories'. $imageName);
        }
        
         }
       
    }
   
    $this->resetUI();
     /*se emite un mensaje, que mas adelante los scripts de las vistas mostraran  */
    $this->emit('category-updated', 'Categoria Actualizada');
}
//la funcion resetUI 

    public function resetUI()
    {
        $this->name ='';
         $this->image = null;
         $this->search ='';
        $this->selected_id =0;

    }
/*Los oyentes son un par clave->valor donde la clave es el evento para escuchar
y el valor es el método para llamar al componente */
    protected $listeners =[
    'deleteRow' => 'Destroy'
    ];
    
//funcion eliminar: permite eliminar una categoria. atraves de la variable category se encarga de evaluar y buscar
/*el id del elemento a eliminar, cuando el id de la categoria es encontrada entonces se ejecuta el metodo delete
que eliminara el id de la categoria y por lo tanto se eliminara la categoria junto con su imagen asignada
la imegen se eliminara desde la carpeta raiz*/
    public function Destroy($id)
    {
        $category = Category::find($id);
        //dd($category);
        $imageName = $category->image;
        $category->delete();

        if($imageName !=null){
            unlink('storage/categories/' .$imageName);


        }
        $this->resetUI();
        /*se envia el parámetros con una emisión de eventos que posteriormente l javascript se encargara de 
        recibir y mostrar en la pantalla vista por el usuario */
        $this->emit('category-deleted', 'Catgoria eliminada');


        
    }
}
