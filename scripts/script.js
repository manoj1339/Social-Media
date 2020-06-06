//jquery

$(document).ready(function(){

  fetch_post();
  fetch_friend();
  message_read('something');

  // update chat history for real time chatting
/*
  setInterval(function(){
    fetch_friend();
    update_chat_history();
    update_time();
    message_read('something');
  }, 5000);*/

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

  // post textarea and post buttons
  //input file
  $('#postImage').on('change', function(){
    var file = $(this).val();
    filename = file.split('\\').pop();
    $('#postImageFileName').html('<span>'+filename+'<i id="postImageClose" class="fa fa-times" aria-hidden="true"></i></span>');

    $('#postImageClose').on('click', function(){
      $('#postImage').val('');
      $('#postImageFileName').html('');
    });
  });

  // onclick post button
  $('#postSubmitBtn > button').on('click', function(){
    var post_msg_body = $('#postMsgBody').val();
    var post_image = $('#postImage')[0].files[0];
    var formData = new FormData();
    formData.append('postFile', post_image);
    formData.append('postMsgBody', post_msg_body);

    if($.trim(post_msg_body) != ''){
      $.ajax({
        url: 'compose_post.php',
        method: 'post',
        processData:false,
        contentType:false,
        data:formData,
        success:function(data){
          $('#postResponseBox').html('<span>'+data+'</span>');
          $('#postResponseBox > span').fadeOut(4000);
          $('.emojionearea-editor').text('');
          $('#postImageFileName').html('');
          fetch_post();
        }
      });
    }
    else
    {
      $('#postResponseBox').html('<span>Post empty ! Type something</span>');
      $('#postResponseBox > span').fadeOut(4000);
    }

  });


});// document.ready ends here

// function to hide and show comment box
function displaycomment(postid)
{
  $('#comment'+postid).slideToggle(500);
}

// hide logout on window click
window.addEventListener('mouseup', function(event){
  var box = document.getElementById('profileModal');

  if(event.target != box && event.target.parentNode != box){
    $('#profileModal').css('display', 'none');
    $(this).data('button', 'close');
  }
}, true);

/* Function to like post */
function like(postId)
{
  var postId = postId;
  $.ajax({
    url: 'like.php',
    method: 'post',
    data:{postId:postId},
    success:function(data){
      $('#like'+postId).toggleClass('liked');
      $('#like'+postId).siblings().html(data);
    }
  });
}

/* Function to dislike post */
function dislike(postId)
{
  var postId = postId;
  $.ajax({
    url: 'dislike.php',
    method: 'post',
    data:{postId:postId},
    success:function(data){
      $('#dislike'+postId).toggleClass('disliked');
      $('#dislike'+postId).siblings().html(data);
    }
  });
}

/* Function to fetch post on user's homepage */
var commentTextarea = $(".allCommentTextarea").emojioneArea();
function fetch_post(){
  $.ajax({
    url: 'fetch_post.php',
    method: 'post',
    data:{post:'fetch', increment:0},
    success:function(data){

      $('#timeline').html(data);
      $(".displayComments").css('display', 'none');
      $(".allCommentTextarea").emojioneArea({
        pickerPosition: 'top',
        placeholder: 'Type your comment...'
      });
      commentTextarea[0].emojioneArea.setText(''); // clear input

    }
  });
}

/*  Function to fetch comments in comment box */
function fetch_all_user_comments(post){
  var postId = post;
  $.ajax({
    url: 'fetch_user_comments.php',
    method: 'post',
    data:{postId:postId},
    success:function(data){
      $('#comment'+postId+' > .commentScrollBox').html(data);
    }
  });
}

/* Function to insert comment */
function insert_comment(post){
  var postid = post;
  var commentVal = $('#'+postid).val();

  if($.trim(commentVal) != '')
  {
    $.ajax({
      url: 'insert_comment.php',
      method: 'post',
      data:{postId:postid, comment:commentVal},
      success:function(data){
        fetch_all_user_comments(postid);
        $('#comments'+postId).siblings().html(data);
      }
    });
  }

}

/*  Function to create chat dialog box */

function make_chat_dialog_box(to_user_id, to_user_name){

  var modal_content = `<div id="chatDialog`+to_user_id+`" title="`+to_user_name+`">
      <div class="chatDialog">
         <div class="chatHistory" id="chatDialogHistory`+to_user_id+`" data-uname="`+to_user_name+`" data-uid="`+to_user_id+`">

         </div>
         <form onsubmit="return false;">
           <textarea name="chatMessage" id="textarea`+to_user_id+`" class="chatMessage" cols="30" rows="10"></textarea>
           <input type="submit" name="submit" value="Send" class="sendBtn" onclick="insert_chat('`+to_user_name+`', `+to_user_id+`)"/>
         </form>
         <button class="clearChatHistory" data-uid="`+to_user_id+`" data-uname="`+to_user_name+`">Clear History</button>
      </div>
  </div>`;

  $('#modelWrapper').html(modal_content);


}


// function to fetch chat history
function fetch_chat_history(to_user_name, to_user_id){
  var to_user_name = to_user_name;
  var to_user_id= to_user_id;
  $.ajax({
    url: 'chat_friend.php',
    method: 'post',
    data: {to:to_user_name},
    success: function(data){
      $('#chatDialogHistory'+to_user_id).html(data);
    }
  })
}



// update chat history every 5 seconds
function update_chat_history(){

   $('.chatHistory').each(function(){
      fetch_chat_history($(this).data('uname'), $(this).data('uid'));
   });

}

// function to check whether user online or offline
function update_time(){
  $.ajax({
    url: 'update_user_time.php',
    method: 'post',
    data: {time: 'time'},
    success: function(data){

    }
  });
}

// function to fetch freind $friend_list
function fetch_friend(){
  $.ajax({
    url: "fetch_friend_list.php",
    method: 'post',
    data: {friend:'friend'},
    success:function(data){
      $('#wrapper').html(data);
      button_events();
    }
  });
}

// insert chat message into database
function insert_chat(to_user_name, to_user_id){
  var message = $('#textarea'+to_user_id).val();
  var to_user = to_user_name;
  var to_id = to_user_id;
  $.ajax({
    url: 'chat_msg_send.php',
    method: 'post',
    data: {to:to_user, msg:message},
    success: function(data){
       $('#textarea'+to_user_id).val("");
       fetch_chat_history(to_user, to_id);
       $('#chatDialogHistory'+to_user_id).animate({ scrollTop: $('#chatDialogHistory'+to_user_id).prop("scrollHeight")}, 500);
    }
  });
}

/* Button event for making chat dialog box */
function button_events(){
  // opens chat dialog on button click event
  $('button.message').on('click', function(){
    var to_user_id = $(this).attr('id');
    var to_user_name = $(this).data('user');

    make_chat_dialog_box(to_user_id, to_user_name);
    fetch_chat_history(to_user_name, to_user_id);
    message_read(to_user_name);

    $('#chatDialog'+to_user_id).dialog({
      autoOpen: false,
      modal: true,
      width:  $(window).width() > 400 ? 400 : '100%',
      height: $(window).height() > 550 ? 550 : $(window).height(),
      draggable: false,
      close: function(event, ui)
        {
            $(this).dialog('destroy').remove();
            message_read(to_user_name);
        }
    });

    $('#chatDialog'+to_user_id).dialog('open');
    $('#chatDialogHistory'+to_user_id).animate({ scrollTop: $('#chatDialogHistory'+to_user_id).prop("scrollHeight")}, 500);

    $('button.clearChatHistory').on('click', function(){
      var uid = $(this).data('uid');
      var uname = $(this).data('uname');
      $.ajax({
        url: 'clear_chat_history.php',
        method: 'post',
        data:{from:uname},
        success:function(data){
          fetch_chat_history(uname, uid);
        }
      });
    });

  });

}

/* Function to insert message read in database */
function message_read(from_user){
  var from_user = from_user;
  $.ajax({
    url: 'msg_read.php',
    method: 'post',
    data:{from:from_user},
    success:function(data){
      $('#totalUnreadMessages').html(data);
    }
  });
}

/* Function Sent freind request */

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



/*  Function to Accept and Reject friend Request */

function request(id, accept){

	var uid = id;
    var reply = document.getElementById(id);

	reply.innerHTML = "processing...";


	var xhttp = new XMLHttpRequest();

	xhttp.onreadystatechange = function(){
		if(this.readyState == 4 && this.status == 200){

				reply.innerHTML = this.responseText;

		}

	};

	try{
	xhttp.open("GET", "accept.php?accept="+accept+"&id="+uid ,true);
	xhttp.send();
	}
	catch(e){
		reply.innerHTML = e;
  }
}

/* ---------------------------------------------------------- */
