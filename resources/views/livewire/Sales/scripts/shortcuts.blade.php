<script>
 var listener = new window.keypress.Listener();
    listener.simple_combo("f9", function() {
        console.log('f9');
    livewire.emit('saveSale')
    });

        listener.simple_combo("f8", function() {
        document.getElementById('hiddenTotal').value='',
        document.getElementById('cash').focus()
        })
            listener.simple_combo("f4", function() {
     var total = parseFloat(document.getElementById('hiddenTotal').value)
     if(total > 0){
         Confirm(0, 'clearCart', 'Seguro que deseas eliminar las ventas')
     }else
     {
         noty('agrega productos a la venta')
     }
       
            });


</script>