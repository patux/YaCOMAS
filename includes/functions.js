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
 
//function for forcing form submission
function checkEnter(e){
var characterCode
     if(e && e.which){
     e = e
     characterCode = e.which
     }
     else{
     e = event
     characterCode = e.keyCode
     }   
     if(characterCode == 13){
     document.forms[0].submit()
     return false
     }
return true
    
}
 
//function for suppressing form submission
function noEnter(e){
var characterCode
     if(e && e.which){
     e = e
     characterCode = e.which
     }
     else{
     e = event
     characterCode = e.keyCode
     }   
     if(characterCode == 13){
     return false
     }
     else{
     return true
     }
}
 
 
//function for adding html p tag to textarea immediately when return key is pressed
function addP(e,ta){
var characterCode
//var ta = document.textareaForm.textareaElement
 
     if(e && e.which){
     e = e
     characterCode = e.which
     }
     else{
     e = event
     characterCode = e.keyCode
     }
     
     if(characterCode == 13){
        //if(nn4){
        ta.value += "<P>%0D%0A"
        ta.value=unescape(ta.value)
        ta.select();ta.focus();
        return false
        //}
        //else{
        //ta.value += "<P>"
        //ta.select()//;ta.focus();
        //return false
        //}  
     }
     else{
        //if(nn4){
        ta.value += String.fromCharCode(characterCode)
        ta.select();ta.focus();
        return false
        //}
        //else{
        //return true
        //}
    }
}
