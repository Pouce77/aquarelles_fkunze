
function mySubmit(id)
{
    var objData = {};
        var objData = ( $(id).is(':checked') ) ? 1 : 0;
 
    $.ajax({
        url: "/comment/validate/"+id,
        data: {
          'id': id,
          'validate' : objData
      },
        dataType : 'json',
        type: 'POST'
    });
}