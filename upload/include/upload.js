var attaIdx = 0;
var IsIE;
function add() {

	addfile("idfilespan",attaIdx);
	attaIdx++;
	return false;
}
//----------------------------------------addfile(spanId,index)----------------------------------------------
function addfile(spanId,index)
{
       var strIndex = "" + index;
	   var fileId = "attachfile"+ strIndex;
	   var brId = "idAttachBr" + strIndex;
	   addInputFile(spanId,fileId);

	   addbr(spanId,brId);

	   return;
}
//-------------------------------------------sub function----------------------------------------------------
function addInputFile(spanId,fileId)
{
	  var span = document.getElementById(spanId);
	  if ( span !=null ) {
	                if ( !IsIE ) {
						var fileObj = document.createElement("input");
						if ( fileObj != null ) {
							fileObj.type="file";
							fileObj.name = "user_upload_file[]";
							fileObj.id = fileId;
							fileObj.size="40";
							var clickEvent = "exist('" + fileId + "')";
							fileObj.setAttribute("onclick",clickEvent,0);  
							span.appendChild(fileObj);
						}//if fileObj
					}// !IsIE

					if ( IsIE ) {
						var fileTag = "<input type='file' name='user_upload_file[]' style='border: #000000 1px solid;font-size: 8pt;'>";
						var fileObj = document.createElement(fileTag); 
						span.appendChild(fileObj);
					}//IsIE if
			
	  }//if span
}

function addbr(spanId,brId)
{
	  var span = document.getElementById(spanId);
	  if ( span !=null ) {
			var brObj = document.createElement("br");
			if ( brObj !=null ) {
				brObj.name = brId;
				brObj.id = brId;
				span.appendChild(brObj);
            }//if
     }//if
	 return;
}