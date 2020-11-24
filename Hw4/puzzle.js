// define puzzle constructor
var Puzzle = function(option){
	this._init(option);
}
Puzzle.prototype = {
	// 初始化数据
	_init:function(option){
		// 当前等级
		this.leverNow = option.leverNow <=2 ? 2 : option.leverNow || 2;
		// 游戏等级(行，列存储)
		this.leverArr = [this.leverNow,this.leverNow];
		// 图片原始索引
		this.imgOrigArr = [];
		// 打乱的图片索引
		this.imgRandomArr = [];

	
		this.btnObj = document.getElementById('btn');

		this.inputObj = document.getElementById('file');	

		this.imgArea = document.getElementById('imgArea');

		this.gameStartBtnObj = document.getElementById('gameStart');

		this.gameAreaObj = document.getElementById('gameArea');

		this.imgCells = '';


		this.imgAreaWidth = option.imgAreaSize<=360? 360 : option.imgAreaSize || 360;
	
		this.imgAreaHeight = option.imgAreaSize<=360? 360 : option.imgAreaSize || 360;


		this.gameAreaObj.style.width = this.imgAreaWidth+'px';
		this.gameAreaObj.style.height = this.imgAreaHeight+this.gameStartBtnObj.offsetHeight+30+'px'; 

	
		this.cellWidth = 120;

		this.cellHeight = 120;

	
		this.hasStart = 0;

	
		this.sel = null;

	
		this.imgUrl = '';
	},

	gameStart:function(){

		this.getImageUrl();
	},

	getImageUrl:function(){	

		var self = this;

		this.btnObj.onclick = function(){
			self.inputObj.click();
		}



		this.inputObj.onchange = function(){

			//document.forms["upload"].submit();
            /*
			$.ajax({
			type : "post",
			url : "up.php",
			data :  new FormData($("#upload")[0]),
			processData : false,
			contentType : false,
			success : function(data){
				if (data=="error") {

					alert("文件上传失败!  只支持png/jpg/gif 格式并且小于2mb");
				}else{
				alert("文件上传成功!");
			    		$.get("up.php?data=1",function(result){

            			if(result=="1"){
            			
            			}else{
            				 self.imgUrl = result;
            		    	 self.imgSplit();
            			}
            		   
            		  });
			}}
		});*/

			

			
		};
	
	},

	imgSplit:function(){

		this.imgArea.innerHTML = '';

		var _cell = '';


		var count = 0;
		for (var i = 0, l =this.leverArr[0]; i<l ;i++) {
		
			for (var j = 0,l =this.leverArr[1]; j<l ;j++) {
				//original array
				//this.leverArr[0] = 3  this.leverArr[1] = 3
				this.imgOrigArr.push(i*this.leverArr[0] + j);
			
				_cell = document.createElement('div');

				_cell.id = count;
				count++;
				_cell.className = "imgCell";
				_cell.index = i*this.leverArr[0] + j;
				_cell.style.width = this.cellWidth+'px';
				_cell.style.height = this.cellHeight+'px';
				_cell.style.left =  j*this.cellWidth+'px';
				_cell.style.top = i*this.cellHeight + 'px';

				_cell.style.backgroundImage= "url("+this.imgUrl+")";

				_cell.style.backgroundSize = this.leverArr[1]+'00%';

				_cell.style.backgroundPosition = (-j)*this.cellWidth + 'px ' + (-i)*this.cellHeight+'px';

				_cell.style.backgroundOrigin = "border-box";
				this.imgArea.appendChild(_cell);
			}
		}


		this.imgCells = document.querySelectorAll('.imgCell');


		this.btnObj.style.left= -this.btnObj.offsetWidth+'px';

		this.gameAreaObj.style.left = '50%';
		this.gameAreaObj.style.transform= 'translateX(-50%)';

		document.body.style.overflowY = "auto";

		this.gameStartBtnObj.onclick = this.clickHandle();
	},

	clickHandle:function(){

		var _self = this;
		return function(){

			if(_self.hasStart == 0){
				_self.hasStart = 1;
				_self.randomArr();
				_self.cellOrder();
				for(var i = 0,l = _self.imgCells.length;i<l;i++){
					_self.imgCells[i].onclick = function(){
						if(_self.sel === null){
							_self.sel = this.index;
							this.style.border = "2px solid red";
						}else{
							_self.imgCells.forEach(function(element){
								element.style.border = "1px solid #fff";
							});
							if(this.index === _self.sel){
								_self.sel = null;
								return ;
							}else{

								_self.cellExchange(_self.sel,this.index);
							
								var ids ="";

								$(".imgCell").each(function(){
										var id =$(this).attr("id");
										ids=ids+id+"-"
								});

						$.get("up.php?data=2&arr="+ids,function(result){
		   
		        });
							}
							_self.sel = null;
						}

					}
				}
			}
		}
	},
	// 打乱图片索引
	randomArr:function(){
		// 清空乱序数组
		this.imgRandomArr = [];
		// 判断原来的数组是否和乱序数组一样
		var _flag = true;
		// 遍历原始索引
		for(var i=0,l=this.imgOrigArr.length;i<l;i++){
			// 获取从0到数组长度之间的一个索引值
			var order = Math.floor(Math.random()*this.imgOrigArr.length);
			// 如果乱序数组中没有值就直接添加
			// 否则就在这个乱序数组中找对应的随机数的索引，找不到就添加,找到就继续随机
			if(this.imgRandomArr.length>0){
				while(this.imgRandomArr.indexOf(order) >-1){
					order = Math.floor(Math.random()*this.imgOrigArr.length);
				}
			}
			this.imgRandomArr.push(order);
		}

		// 判断乱序数组和原始数组是否一样
		if(this.imgRandomArr.length === this.imgOrigArr.length){
			// 遍历数组
			for(var i=0,l=this.imgOrigArr.length;i<l;i++){
				if(this.imgRandomArr[i] != this.imgOrigArr[i]){
					_flag = false;
					break;
				}else{
					_flag = true;
				}
			}
		}else{
			_flag = true;
		}

		// 返回值为true的话 就代表原始数组和乱序数组一致，重新打乱数组
		if(_flag){
			this.randomArr();
		}
	},
	//让小格子根据乱序数组移动到对应的位置
	cellOrder:function(){
		// 拿到当前的this
		var _self = this;
		// 遍历所有的小格子
		
		this.imgCells.forEach(function(element,index){
			element.id = _self.imgRandomArr[index];
			element.style.left = _self.imgRandomArr[index] % _self.leverArr[1] * _self.cellWidth+'px';
			element.style.top = Math.floor(_self.imgRandomArr[index] / _self.leverArr[1]) * _self.cellHeight+'px';
			
		});
	},
	// 交换两次点击的图片位置
	cellExchange:function(from,to){
		// 因为图片此时的排序是根据 以图片的索引值为索引
		// 在乱序的数组中根据相对应的索引取出的值作为当前图片的排序位置的
		// 因此根据from to这两个值作为索引，就能在乱序数组中得到当前图片是第几张

		// 求出from的图片 是第几行第几列
		// 当前是第几张图片 / 一行多少列 然后取整 就是当前属于第几行
		var _fromRow = Math.floor(this.imgRandomArr[from] / this.leverArr[1]);
		// 当前是第几张图片 % 一行多少列 然后取余 就是当前属于第几列
		var _fromCol = this.imgRandomArr[from] % this.leverArr[1];
		// 求出to的图片 是第几张第几列
		var _toRow = Math.floor(this.imgRandomArr[to] / this.leverArr[1]);
		var _toCol = this.imgRandomArr[to] % this.leverArr[1];

		// 移动两张图片
		var idtmp = this.imgCells[from].id;
		var idtmpx = this.imgCells[to].id;


		this.imgCells[from].id = idtmpx;
		this.imgCells[from].style.left = _toCol*this.cellWidth + 'px';
		this.imgCells[from].style.top = _toRow*this.cellHeight + 'px';

		this.imgCells[to].id = idtmp;
		this.imgCells[to].style.left = _fromCol*this.cellWidth + 'px';
		this.imgCells[to].style.top = _fromRow*this.cellHeight + 'px';

		// 将乱序数组中的两个值交换位置
		// 定义一个临时变量来实现交换顺序
		var _temp = this.imgRandomArr[from];
		this.imgRandomArr[from] = this.imgRandomArr[to];
		this.imgRandomArr[to] = _temp;

		//如果乱序数组和原数组一致，则表示拼图已完成
		if(this.imgOrigArr.toString() === this.imgRandomArr.toString()){
			this.success();
		}	
	},
	// success
	success:function(){
		this.hasStart = 0;
		setTimeout(function(){
			alert('Puzzle solved successfully');
			$("#gameStart").click();
		},600);
	}

}



  　

