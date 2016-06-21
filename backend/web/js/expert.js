jQuery(function ($) {

    //抽取专家
	$("#chouqu").bind('click',function(){
		var url = $("#postUrl").val();
		//var typeId = $("#caigou-typeid");
		var type2 = $("#type2");
		var num = $("#num");
		var box = $("#msgBox");
    	var type = box.find('.type2'); //已抽取的项目
    	var nowType = "";
    	var status = true;
    	$.each(type,function(i,item){
    		nowType = type.eq(i).val();
    		console.log(nowType);
    		if(nowType == type2.val()){
    			status = false;
    			return false;
    		}
    	});
    	if(status === false){
    		alert("你已经选择了该类别的专家！");
    		return false;
    	}

		if(type2.val() == ""){
			alert("请选择类别！");
			return false;
		}
		if(isNaN(num.val())!=false || parseInt(num.val())<=0){
			alert("请输入正确的抽取人数！");
			return false;
		}

		
		$.post(url,{"type2":type2.val(),"num":num.val()},function(data){
			if(data.status == 1){
				var html = htmlToTable(data);
				box.append(html);
			}else{
				alert(data.content);
			}
			
			type2.val("");
			num.val("3");
		},'json');
	});
	
	//重新抽取
    $("#msgBox").on('click','.btn-xs',function(){
    	var thisLi = $(this).parent("li");
    	var zjId = thisLi.attr('id').substring(3);
    	var type = $(this).parent("li").siblings('input.type2').val();
		var changeUrl = $("#changeUrl").val();
		var siblingsLi = thisLi.siblings('li');
		var existId = null;
		$.each(siblingsLi,function(i,item){
			existId = siblingsLi.eq(i).attr("id").substring(3);
    		zjId += ","+existId; 
    	});

    	$.post(changeUrl,{"type":type,"zjId":zjId},function(data){
			if(data.status == 1){
				var li = "<li class='list-group-item' id='zj-"+data.content.id+"'>";
					li += data.content.name+"("+data.content.phone+")";
					li += "<button type='button' class='btn btn-primary btn-xs badget'>重新抽取</button>";
					li += "<input type='hidden' name='zjId["+type+"][]' value='"+data.content.id+"'>";
					//li += "<input type='hidden' name='zjName["+type+"][]' value='"+data.content.name+"'>";
					//li += "<input type='hidden' name='zjPhone["+type+"][]' value='"+data.content.phone+"'>";
					li += "</li>";
				thisLi.replaceWith(li);
				thisLi.remove();
			}else{
				alert(data.content);
			}
			
		},'json');
    });
    
    //删除
    $("#msgBox").on('click',".tab-delete",function(){
    	if(confirm("确定移除该类别的专家吗？")){
    		self = $(this);
    		setTimeout(
    			"self.closest('table.table').remove()",200
    		);
    	}
    });

    //表带提交检测  id="zhuanjia" 专家抽取提交
    $("#zjSubmit").click(function(){
    	var inp = $("#msgBox").find("table.table").attr("class");
    	if(inp){
    		$("#zhuanjia").submit();
    	}else{
    		alert("请点击<添加专家>,选择专家！");
    	}
    	
    });

});



//专家信息table
function htmlToTable(arr){
	var html = li = "";
		html = "<table class='table table-striped table-bordered' >";
		html += 	"<tbody>";
		html += 		"<tr>";
		html += 			"<th width='110'>类别</th>";
		html += 			"<td>"+arr.type+"<span class='tab-delete'>X</span></td>";
		html += 		"</tr>";
		html += 		"<tr>";
		html += 			"<th width='110'>信息</th>";
		html += 			"<td>";
		html += 				"<ul class='list-group'>";
		html += 					"<input type='hidden' name='type[]' class='type2' value='"+arr.type+"'>";
		$.each(arr.content,function(inden,item){
			li += "<li class='list-group-item' id='zj-"+item.id+"'>";
			li += item.name+"("+item.phone+")";
			li += "<button type='button' class='btn btn-primary btn-xs badget'>重新抽取</button>";
			li += "<input type='hidden' name='zjId[]' value='"+item.id+"'>";
			//li += "<input type='hidden' name='zjName["+arr.type+"][]' value='"+item.name+"'>";
			//li += "<input type='hidden' name='zjPhone["+arr.type+"][]' value='"+item.phone+"'>";
			li += "</li>";
		});
		
		html += li;
		html +=					"</ul>";
		html +=				"</td>";
		html +=			"</tr>";
		html +=		"</tbody>";
		html +=	"</table>";
		return html;
}