function placeOrder() {
    var orderString = document.getElementById('orderString').value;

    $.ajax({
        url: './place-etrade-order.php',
        data: {orderString: orderString},
        async: true, 
        dataType: 'html',
        success:  function (data) {
          var returnedObject = data; 
          alert("everthing ok");  

        },
        error: function (xhr, ajaxOptions, thrownError) {
          console.log("there was an error in calling place-etrade-order.php");
          alert("there was an error in calling place-etrade-order.php");
        }

    });

}