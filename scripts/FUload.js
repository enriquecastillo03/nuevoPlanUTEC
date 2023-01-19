function FUload(btn,URL,options){
document.getElementById(btn).onclick=function(){
FUload.prototype.ff=document.createElement('iframe');
FUload.prototype.ff.style.display='none';
document.body.appendChild(FUload.prototype.ff);
FUload.prototype.ff.contentWindow.document.write('<form action="'+URL+'" method="post" enctype="multipart/form-data" ><input type="file" name="userfile" id="userfile" onChange="document.forms[0].submit();"  /></form>');
this.fup=FUload.prototype.ff.contentWindow.document.getElementById('userfile');

if (this.fup.addEventListener){ 
this.fup.addEventListener('change',function(){FUload.prototype.prop.start(FUload.prototype.ff.contentWindow.document.getElementById('userfile').value);},false);
} else { 
 this.fup.attachEvent('onchange',function(){FUload.prototype.prop.start(FUload.prototype.ff.contentWindow.document.getElementById('userfile').value)});
}


if (FUload.prototype.ff.addEventListener){ 
 FUload.prototype.ff.addEventListener('load',function(){var data=FUload.prototype.ff.contentWindow.document.body.innerHTML;document.body.removeChild(FUload.prototype.ff);  FUload.prototype.prop.sucess(data)},false);
 } else { 
           FUload.prototype.ff.attachEvent('onload',function(){FUload.prototype.prop.sucess(FUload.prototype.ff.contentWindow.document.body.innerHTML)});
        }
FUload.prototype.prop={
start:function(callback){},
sucess:function(callback){}
	}
for(key in options) {
 if(FUload.prototype.prop.hasOwnProperty(key)) {
 FUload.prototype.prop[key] = options[key];
  }
}	
this.fup.click();	  


};
 		  
	
}