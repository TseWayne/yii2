if (typeof mihaildev == "undefined" || !mihaildev) {
	var mihaildev = {};
}

mihaildev.elFinder = {
	zonge:0,
	navid:0,
	navlist: {},
	openCheck: function(){return true;},
	openManager: function(options){
		if(this.openCheck()===false) return;
		
		var params = "menubar=no,toolbar=no,location=no,directories=no,status=no,fullscreen=no";
		if(options.width == 'auto'){
			options.width = $(window).width()/1.5;
		}

		if(options.height == 'auto'){
			options.height = $(window).height()/1.5;
		}

		params = params + ",width=" + options.width;
		params = params + ",height=" + options.height;
		
		if(this.navid>0 && typeof(this.navlist[this.navid]) != "undefined"){
			var nav = this.navlist[this.navid];
			for(var i=0;i<nav.length;i++)
			options.url += "&navlist[]="+nav[i];
		}
		
		if(options.zonge>0) this.zonge = options.zonge;
		if(settingMoney!= "undefined" && settingUploads!= "undefined" && this.zonge>=settingMoney){
			for(var i=0;i<settingUploads.length;i++)
				options.url += "&navlist[]="+settingUploads[i];
		}

		//console.log(params);
		var win = window.open(options.url, 'ElFinderManager' , params);
		win.focus()
	},
	functions: {},
	register: function(id, func){
		this.functions[id] = func;
	},
	callFunction: function(id, file){
		return this.functions[id](file, id);
	},
	functionReturnToInput: function(file, id){
		jQuery('#' + id).val(file.url);
		return true;
	}
};


