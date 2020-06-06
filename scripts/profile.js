$(document).ready(function(){
  // Shows upload button, logout onclick profile image
  $('#profile_pic').click(function(){
    var btn = $(this).data('button');
    if(btn == 'close')
    {
      $('#profileModal').css('display', 'block');
      $(this).data('button', 'open');
    }
    else{
      $('#profileModal').css('display', 'none');
      $(this).data('button', 'close');
    }
  });
});


function ajaxcall(id){

	var uid = id;
  var reply = document.getElementById(id);

	reply.innerHTML = "processing...";


	var xhttp = new XMLHttpRequest();

	xhttp.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){

				reply.innerHTML = this.responseText;
			    reply.disabled = true;
		}

	};

	try{
	xhttp.open("GET", "friends.php?uid="+uid ,true);
	xhttp.send();
	}
	catch(e){
		reply.innerHTML = e;
  }
}
/* --------------------------------------------------------- */
