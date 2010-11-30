function focus_on_first ()
{
    var form = document.forms[0];
    if (form != null && form.elements[0] != null)
    {
        for (var i = 0; i < form.elements.length; ++i)
        {
            var field = form.elements[i];
            if (field.type!="hidden" && (field.type=="text" || field.type=="textarea"))
            {
                field.focus();
                break;
            }
        }
    }
}
function showModal(){
	document.getElementById('modal_dialog').className="visible";
	document.getElementById('modal_dialog_shadow').className="visible";
}
function hideModal(){
	document.getElementById('modal_dialog').className="hidden";
	document.getElementById('modal_dialog_shadow').className="hidden";
	document.getElementById('modal_dialog').innerHTML="";
}
