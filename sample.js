/*
	sample.js
	
	@license http://www.apache.org/licenses/LICENSE-2.0
*/

// jQuery required

function getThroughTor(uri, callBack){
	$.post( "http://localhost:8000/", { uri: uri })
	.done(function( data ) {
		if (typeof callBack == 'function') callBack(data);
	});
}