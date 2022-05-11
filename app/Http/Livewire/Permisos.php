<?php

namespace App\Http\Livewire;

use DB;
use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Permission\Models\permission;
 

class Permisos extends Component
{
    use WithPagination;
    
    public $permissionName, $search, $selected_id, $pageTitle, $componentName;
    private $pagination = 10;


    public function paginationView()

    {
            return'vendor.livewire.bootstrap';
    }


    public function mount()
    {
        $this->pageTitle = 'Listado';
        $this->componentName= 'permisos';
    }

    public function render()
    {

        if(strlen($this-> search) > 0)
        $permisos = Permission::where('name', 'like', '%' . $this->search . '%')->paginate($this->pagination);
        else
        $permisos = Permission::orderBy('name', 'asc')->paginate($this->pagination);
         


        return view('livewire.permisos.permisos', [
            'permisos' => $permisos
        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }


    public function CreatePermission()
    {
        //reglas
    $rules = ['permissionName'=> 'required|min:2|unique:permissions,name'];
    //mensajes
    
      $messages = [
          'permissionName.required' =>'El nombre del permiso es requerido',
          'permissionName.unique' => 'El permiso ya existe, intente con otro',
          'permissionName.min' => 'El nombre del permiso debe tener almenos 2 caracteres'
      
      ];
   //this llama las variables rules y messages y pasa estos valores a las variable category y crea un nuevo registro
           $this->validate($rules, $messages);

        Permission::create(['name'=> $this->permissionName]);

      $this->emit('permiso-added', 'se registroel permiso con exito');
      $this->resetUI();
}


public function Edit(Permission $permiso)
{
  //  $permission = permission::find($id);

    $this->selected_id = $permiso->id;
    $this->permissionName = $permiso->name;
//modal-evento a emitir
$this->emit('show-modal', 'show modal');

}


public function UpdatePermission()
{

    $rules = ['permissionName'=> "required|min:2|unique:permissions,name,{$this->selected_id}"];
    //mensajes
    
      $messages = [
          'permissionName.required' =>'El nombre del permiso es requerido',
          'permissionName.unique' => 'El permiso ya existe, intente con otro',
          'permissionName.min' => 'El nombre del permiso debe tener almenos 2 caracteres'
      
      ];
   //this llama las variables rules y messages y pasa estos valores a las variable category y crea un nuevo registro
$this->validate($rules, $messages);
$permiso = Permission::find($this->selected_id);
$permiso->name=$this->permissionName;
$permiso->save();

$this->emit('permiso-updated ', 'se actualizo el rol con exito');
$this->resetUI();


}

protected $listeners = ['destroy' => 'Destroy'];

public function Destroy($id)
{
    $rolesCount = Permission::find($id)->getRoleNames()->count();
    if(PermissionsCount > 0)
    { 
        $this->emit('permiso-error', 'No se puede eliminar el permiso porque tiene roles asociados');
        return;
    }
    Permission::find($id)->delete();
    $this->emit('permiso-deleted', ' se elimino el permiso con exito');
}
public function resetUI()
{
    $this->permissionName = '';
    $this->search = '';
    $this->selected_id =0;
    $this->resetValidation(); 
}

}