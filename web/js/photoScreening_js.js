
$( document ).ready(function() {
    $('input:radio[name="edit"]').change(
        function () {
            if (this.checked && this.value == 'delete') {
                $("#deletereason").show();
            }
            else {
                $("#deletereason").hide();
            }
        });
    $('#form').submit(function (event) {
        var errors = false;
        if($('input:radio[name="edit"]:checked').val()=="delete")
        {
            if($("input[name='deleteReason[]']").is(":checked")==false)
            {
                errors = true;
                alert("please select atleast 1 reason");
            }
        }

        if (errors == true) {
            event.preventDefault();
        }
    });
});

