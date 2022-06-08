<?php

namespace App\Http\Livewire;

use DB;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Roles extends Component
{
    use WithPagination;
    
    public $roleName, $search, $selected_id, $pageTitle, $componentName;
    private $pagination = 5;


    public function paginationView()

    {
            return'vendor.livewire.bootstrap';
    }

/*atraves de este metodo se añaden los nombres que reciben las paginas de cada apartado*/
    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName= 'Roles';
    }
    /*función de las condiciones de la paginación. através del if condición y se evalua si una condicion es verdadera 
o falsa si la paginación es mayor a 5 entonces se agrega una segunda lista, si no se cumple esta condición entonces evalua 
los datos de la categoria y no crea una nueva paginacion*/
    public function render()
    {

        if(strlen($this-> search) > 0)
        $roles = Role::where('name', 'like', '%' . $this->search . '%')->paginate($this->pagination);
        else
        $roles = Role::orderBy('name', 'asc')->paginate($this->pagination);
         


        return view('livewire.roles.roles', [
            'roles' => $roles
        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }

/*Metodo que permite la creacion de un nuevo rol*/
    public function CreateRole()
    {
        //las reglas se definen pora que el usuario llene los campos correctament
    $rules = ['roleName'=> 'required|min:2|unique:roles,name'];
    //mensajes: estas son las alertas que los usuarios veran al no llenar un campo o repetirlo
    
      $messages = [
          'roleName.required' =>'El nombre del rol es requerido',
          'roleName.unique' => 'El rol ya existe, intente con otro',
          'roleName.min' => 'El nombre del rol debe tener almenos 2 caracteres'
      
      ];
   //this llama las variables rules y messages y pasa estos valores a las variable category y crea un nuevo registro
           $this->validate($rules, $messages);

        Role::create(['name'=> $this->roleName]);

      $this->emit('role-added', 'se registro un nuevo rol con exito');
      $this->resetUI();
}


public function Edit( Role $role)
{
    $role = Role::find($id);

    $this->selected_id = $role->id;
    $this->roleName = $role->name;
//modal-evento a emitir
$this->emit('show-modal', 'show modal');

}


public function UpdateRole()
{

    $rules = ['roleName'=> 'required|min:2|unique:roles,name,{$this->selected_id}'];
    //mensajes
    
      $messages = [
          'roleName.required' =>'El nombre del rol es requerido',
          'roleName.unique' => 'El rol ya existe, intente con otro',
          'roleName.min' => 'El nombre del rol debe tener almenos 2 caracteres'
      
      ];
   //this llama las variables rules y messages y pasa estos valores a las variable category y crea un nuevo registro
$this->validate($rules, $messages);
$role=Role::find($this->selected_id);
$role->name=$this->roleName;
$role->save();

$this->emit('role-updated ', 'se actualizo el rol con exito');
$this->resetUI();


}

protected $listeners = ['destroy' => 'Destroy'];
/*Metodo que permite eliminar al usuario registrado anteriormente */
public function Destroy($id)
{
    $permissionsCount = Role::find($id)->permissions->count();
    if($permissionsCount > 0)
    {
        $this->emit('role-error', 'No se puede eliminar porque tiene permisos asociados');
        return;
    }
    Role::find($id)->delete();
    $this->emit('role-deleted', ' se elimino el rol con exito');
}
public function resetUI()
{
    $this->roleName = '';
    $this->search = '';
    $this->selected_id =0;
    $this->resetValidation(); 
}

}