
function deleteFile(id, hidden_id){
	var el = document.getElementById("editablebox_"+id);
	el.style.display = 'none';
	var input = document.getElementById(hidden_id);
	input.value = "";
}