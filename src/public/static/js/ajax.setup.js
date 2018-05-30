$(function () {
	$.ajaxSetup({
	    dataType: 'json',
	    headers: {
	    	'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	    },
	    statusCode: {
	        401: function() {            
	        	location.href = route('login');
	        },
	        403: function() {
	            alert('You do not have permissions to perform this operation.')
	        },
	        404: function() {
	            alert('No data.');
	        },
	        422: function(xhq) {
	        	var message = xhq.responseText;
	        	if(xhq.responseJSON){
	        		message = '';
	        		$.each(xhq.responseJSON.errors, function(){
	        			message += this.join('\n');
	        		});
	        	}
	            alert(message);
	        },
	        500: function() {
	            alert('Whoops, looks like something went wrong.');
	        }
	    }
	});	
});