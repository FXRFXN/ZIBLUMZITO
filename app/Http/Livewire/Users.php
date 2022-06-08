<?php

namespace App\Http\Livewire;
use App\Models\Sale;
use App\Models\User;
use Livewire\Component;
use livewire\WithPagination;

use Livewire\WithFileUploads;
use Spatie\Permission\Models\Role;

class Users extends Component
{

    use WithPagination;
    use WithFileUploads;


    public $name, $phone, $email,$status,$image,$password,$selected_id,$fileLoaded,$profile;
    public $pageTitle, $componentName, $search;
    private $pagination = 3;


    public function paginationView ()
    {
        return 'vendor.livewire.bootstrap';
    }

   public function mount()
   {
       $this->pageTitle = 'Listado';
       $this->componentName = 'Usuarios';
       $this->status ='Elegir';
   }

    public function render()
    {

        if(strlen($this->search) > 0)
        
        $data = User::where('name','like', '%' . $this->search . '%')
        ->select('*')->orderBy('name', 'asc')->paginate($this->pagination);
        else
        $data = User::select('*')->orderBy('name', 'asc')->paginate($this->pagination);


        return view('livewire.users.users',[
            'data' => $data,
            'roles' => Role::orderBy('name', 'asc')->get()
        ])
        ->extends('layouts.theme.app')
        ->section('content');
    }

    public function resetUI()
    {
    $this->name ='';
    $this->email ='';
    $this->password ='';
    $this ->phone ='';
    $this->image ='';
    $this->search ='';
    $this->status='Elegir';
    $this->selected_id  =0;
    $this->resetValidation();
  
}

public function edit(User $user)

{
    $this->selected_id = $user->id;
$this->name=$user->name;
$this->phone=$user->phone;
$this->profile=$user->profile;
$this->status=$user->status;
$this->email=$user->email;


$this->password ='';
$this->emit('show-modal', 'open!');

}

protected $listeners =[
    'deleteRow' => 'Destroy',
    'resetUI'=> 'resetUI'

];

    public function Store()
    {
        $rules=[
            'name' =>'required|min:3',
            'email'=>'required|unique:users|email',
            'status'=>'required|not_in:Elegir',
         
            'password'=>'required|min:3'

        ];


        $messages =[
            'name.required'=>'Ingresa el nombre',
            'name.min'=>'El nombre de usuario debe tener almenos 3 caracteres',
            'email.required'=>'Ingresa el correo',
            'email.email'=>'Ingresa un correo valido',
            'email.unique'=>'El email ya existe en el sistemas',
            'status.required'=>'Selecciona un estado de usuario',
            'status.not_in'=>'Selecciona el estado',
        
            'password.required'=>'Ingresa la contraseña',
            'password.min'=>'La contraseña debe tener almenos 3 caracteres',
            
        ];
        $this->validate($rules,$messages);
        
        $user =User::Create([
            'name'=>$this->name,
            'email'=>$this->email,
            'phone'=>$this->phone,
            'status'=>$this->status,
           
            'password'=>bcrypt($this->password)

           

        ]); 
        $user->syncRoles($this->profile);

         if($this->image)
         {
             $customFileName =uniquid() . '_.' .$this->image->extension();
             $this->image->storeAs('public/users',$customFileName);
             $user->image =$customFileName;
             $user->save();
    
         }
         $this->resetUI();
         $this->emit('user-added','Usuario Registrado');



    }
    public function Update()
    {

        $rules=[
            'email'=>"required|email|unique:users,email,{$this->selected_id}",
            'name' =>'required|min:3',
           
            'status'=>'required|not_in:Elegir',
            'profile'=>'required|not_in:Elegir',
            'password'=>'required|min:3'

        ];


        $messages =[
            'name.required'=>'Ingresa el nombre',
            'name.min'=>'El nombre de usuario debe tener almenos 3 caracteres',
            'email.required'=>'Ingresa el correo',
            'email.email'=>'Ingresa un correo valido',
            'email.unique'=>'El email ya existe en el sistemas',
            'status.required'=>'Selecciona un estado de usuario',
            'status.not_in'=>'Selecciona el estado',
            'profile.required'=>'Selecciona el perfil/rol del usuario',
            'profile.not_in'=>'Selecciona un perfil deistinto',
            'password.required'=>'Ingresa la contraseña',
            'password.min'=>'La contraseña debe tener almenos 3 caracteres',
            
        ];
        $this->validate($rules,$messages);
        $user = User::find($this->selected_id);
        $user->update([
            'name'=>$this->name,
            'email'=>$this->email,
            'phone'=>$this->phone,
            'status'=>$this->status,
            'profile'=>$this->profile,
            'password'=>bcrypt($this->password)

    
        ]);
      $user->syncRoles($this->profile);
       

        if($this->image)
        {
            $customFileName =uniquid() . '_.' .$this->image->extension();
            $this->image->storeAs('public/users',$customFileName);
            $imageTemp =$user->image;

            $user->image =$customFileName;
            $user->save();

   
        }
        $this->resetUI();
        $this->emit('user-updated','Usuario actualizado');


        

    }



        public function Destroy(User $user)
        {

            if($user){
                $sales = Sale::where('user_id', $user->id)->count();
                if($sales > 0){
                    $this->emit('user_withsales','No es posible eliminar el usuario porque tiene ventas registradas');
                }else{
                    $user->delete();
                    $this->resetUI();
                    $this->emit('user_deleted','usuario eliminado');
                }
            }
        }

}
    
