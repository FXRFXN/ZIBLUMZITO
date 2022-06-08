<?php

namespace App\Http\Livewire;

use Livewire\Component;

use App\Models\Denomination;
use Livewire\WithPagination;
use Livewire\WithFileUploads;

use Illuminate\Support\Facades\Storage;

class Coins extends Component

{

    use WithFileUploads;
    use WithPagination;

    public $type,$value, $search, $image, $selected_id, $pageTitle;
    private $pagination = 5;
//función para cambio de nombre del encabezado
    public function mount()
    {
            //this llama el título de la página y del nombre del componente 
     $this->pageTitle ='Listado';
     $this->componentName ='Denominaciones';
     $this->type ='Elegir';



    }
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
        $data =Denomination::where('type', 'like', '%'. $this->search. '%')->paginate($this->pagination);
        else 
        $data = Denomination::orderBy('id', 'desc')->paginate($this->pagination);

        return view('livewire.denominations.coins', ['data' => $data])
        ->extends('layouts.theme.app')
        ->section('content') ;
    }

    /*funcion editar, le pasamos el valor de id a la función $record busca entre las denominaciones y através de un array
busca el id. $this llama al nombre de la variable record y devuelve un nombre
selecciona un id que se guarda en la variable record

return recibe la llamada edit y retorna los scripts que estan definidos en el .blade*/

    public function Edit($id)
    {
        $record = Denomination::find($id, ['id', 'type','value','image']);
        
        $this-> type = $record->type;
        $this-> value = $record->value;
        $this-> selected_id = $record->id;
        $this-> image = null;

        $this->emit('show-modal', 'show modal!');

    }
 /*la función store permite recibir y validar los mensajes de alerta en los cuales se definen 
    las reglas de creación o actualización, y los mensajes de alerta*/
  
    public function Store()
    {
        
    $rules = [
            'type'=> 'required|not_in:Elegir', 
            'value'=> 'required|unique:denominations'
     ]; 
      $messages = [
          'type.required' =>'El tipo es requerido',
          'type.not_in' => 'Elige un valor para el tipo',
          'value.required' => 'Valor requerido',
          'value.unique' => 'Valor existente'
      
      ];
 
           $this->validate($rules, $messages);


           $denomination = Denomination::create([
            
            
               'type' => $this->type,
               'value' => $this->value
           ]);

         
        
         if($this->image)
         {

         $customFileName = uniqid() . '_.' . $this->image->extension();
         $this->image->storeAs('public/denominations', $customFileName);
         $denomination->image = $customFileName;
         $denomination->save();
         }

         $this->resetUI();
         
         $this->emit('item-added', 'denominacion  Registrada');

    }

    /*función actualizar: atraves de esta funcion se define en las variables las reglas 
    y mensajes que se mostraran al usuario a la hora de actualizar.*/
    public function Update()
    {
        $rules = [
            'type' => 'required|not_in:Elegir',
            'value' => "required|unique:denominations,value {$this->selected_id}"

        ];

        $messages = [
            'type.required' => 'El tipo es requerido',
            'type.not_in' => 'elige un tipo de valor',
            'value.required' => 'Valor requerido',
            'value.unique' => 'El valor ya existe'
        ];
        
        $this->validate($rules, $messages);

        $denomination = Denomination::find($this->selected_id);
        
        $denomination->update([
            
                'type'=> $this->type,
                'value'=> $this->value
        ]);


        if($this->image)
        {
        $customFileName = uniqid() . '_.' . $this->image->extension();
        $this->image->storeAs ('public/denominations', $customFileName);
        $imageName = $denomination->image;

      
        $denomination->image = $customFileName;
        $denomination->save();
        
        if($imageName !=null)
        {
        if (file_exists('storage/denominations'. $imageName))
        {
        unlink ('storage/denominations'. $imageName);
        }
        
         }
       
    }
    
    $this->resetUI();
    $this->emit('item-updated', 'Denominacion Actualizada');
}


    public function resetUI()
    {
        $this->type ='';
        $this->value ='';
         $this->image = null;
         $this->search ='';
        $this->selected_id =0;

    }
/*Los oyentes son un par clave->valor donde la clave es el evento para escuchar
y el valor es el método para llamar al componente */
    protected $listeners =[
    'deleteRow' => 'Destroy'
    ];
    //funcion eliminar: permite eliminar una denominacion. atraves de la variable category se encarga de evaluar y buscar
/*el id del elemento a eliminar, cuando el id de la denominacion es encontrada entonces se ejecuta el metodo delete
que eliminara el id de la categoria y por lo tanto se eliminara la categoria junto con su imagen asignada
la imegen se eliminara desde la carpeta raiz*/

    public function Destroy(Denomination $denomination)
    {
      
        $imageName = $denomination->image;
        $denomination->delete();

        if($imageName !=null){
            unlink('storage/denominations/' .$imageName);


        }
        $this->resetUI();
        $this->emit('item-deleted', 'Denominacion eliminada');


        
    }
}
 