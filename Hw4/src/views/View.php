<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>puzzle</title>
	<link rel="stylesheet" href="Hw4/src/styles/styles.css" />
	<style type="text/css">
#gameArea{
   position: absolute;
   left: 40%;
   margin-top: 40px;
   transition: all .7s ease;
   /*border-radius: 1px;*/
}
#imgArea{
   position: relative;
   width: 360px;
   height: 360px;
   /*border-radius: 5px;*/
   /*display: inline-block;*/
   background-color: #ffe171;
   /*box-shadow: 0 0 10px #ffe171;*/
}
#imgArea .imgCell {
    border: 1px solid #fff;
    box-sizing: border-box;
    border-radius: 4px;
    position: absolute;
    transition: left .4s ease-in-out, top .4s ease-in-out;
}
#imgArea div{
   position: absolute;
   width: 118px;
   height: 118px;
   text-align: center;
   color: white;
   font-size: 100px;
   /*display: inline-block;*/
   /*box-sizing: border-box;*/
   background-color: #20a6fa;
   border-radius: 4px;
   transition: left .4s ease-in-out, top .4s ease-in-out;
}
#d0 {
    left: 0px;
}
#d1 {
    left: 120px;
}
#d2 {
    left: 240px;
}
#d3 {
    top: 120px;
}
#d4 {
    top: 120px;
    left: 120px;
}
#d5 {
    top: 120px;
    left: 240px;
}
#d6 {
    top: 240px;
}
#d7 {
    left: 120px;
    top: 240px;
}
#d8 {
    left: 240px;
    top: 240px;
}
	</style>
</head>
<body>
	<div id="gameArea">
		<h1>Community Jigsaw</h1>
    	<h2>NewImage: <input type="file" id="file"></h2>
		<div id="imgArea">
			<!--<div id="d0">0</div>-->
			<!--<div id="d1">1</div>-->
			<!--<div id="d2">2</div>-->
			<!--<div id="d3">3</div>-->
			<!--<div id="d4">4</div>-->
			<!--<div id="d5">5</div>-->
			<!--<div id="d6">6</div>-->
			<!--<div id="d7">7</div>-->
			<!--<div id="d8">8</div>-->
		</div>
	</div>
    
</body>
<script type="text/javascript" src="Hw4/src/views/puzzle1.js"></script>
<script>

	window.onload = function(){

		var puzzle = new Puzzle({
			// 当前等级
			leverNow:3,
			// 拼图区域大小
			imgAreaSize:360
		});
		puzzle.gameStart();
	}
	
	// 定义一个puzzle的构造函数
var Puzzle = function(option){
	this._init(option);
}
// 定义原型
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

		// 获取input的DOM元素
		this.inputObj = document.getElementById('file');	
		// 获取到图片存放区域
		this.imgArea = document.getElementById('imgArea');
		// 获取游戏区域的DOM元素
		this.gameAreaObj = document.getElementById('gameArea');
		// 获取每一个小格子的DOM元素
		this.imgCells = '';

		// 获取游戏区域的宽度，最小值为400
		this.imgAreaWidth = option.imgAreaSize;
		// 获取游戏区域的高度，最小值为400
		this.imgAreaHeight = option.imgAreaSize;

		// 计算出每个小格子的宽
		this.cellWidth = this.imgAreaWidth / this.leverArr[1];
		// 计算出每个小格子的宽
		this.cellHeight = this.imgAreaHeight/this.leverArr[0];

		// 定义一把锁,让开始游戏的按钮只能点击一次
		this.hasStart = 0;

		// 定义第一次点击的图片的索引
		this.sel = null;

		// 获取图片的base64编码的地址
		this.imgUrl = '';
	},
	// 游戏开始
	gameStart:function(){
		// 获取图片路径
		this.getImageUrl();
	},
	// 获取图片路径
	getImageUrl:function(){	
		// 拿到当前的this
		var self = this;
		// 让按钮点击模拟input点击
		// this.btnObj.onclick = function(){
		// 	self.inputObj.click();
		// }
		// 监听input的改变事件
		this.inputObj.onchange = function(){
			// 拿到文件数组
			var files = this.files;
			// 创建一个 FileReader对象
			var reader = new FileReader();
			// 拿到图片的base64编码
			reader.readAsDataURL(files[0]);
			// 读取完成时 拿到编码
			reader.onload = function(){
				// 获取图片路径
				self.imgUrl = reader.result;
				// 切割图片
				self.imgSplit();
			}
		};
	},
	// 切割图片
	imgSplit:function(){
		// 清空图片存放区域
		this.imgArea.innerHTML = '';
		// 用来存方动态创建的div元素
		var _cell = '';

		// 行数
		for (var i = 0, l =this.leverArr[0]; i<l ;i++) {
			// 列数
			for (var j = 0,l =this.leverArr[1]; j<l ;j++) {
				// 给每张图片一个索引值
				// 索引递增规则为  从左到右  从上到下
				this.imgOrigArr.push(i*this.leverArr[0] + j);
				// 创建div
				_cell = document.createElement('div');
				// 给div添加id
				_cell.className = "imgCell";
				// 给每张一个索引，方便后面点击的时候进行判断
				_cell.index = i*this.leverArr[0] + j;
				// 给div添加样式
				_cell.style.width = this.cellWidth+'px';
				_cell.style.height = this.cellHeight+'px';
				_cell.style.left =  j*this.cellWidth+'px';
				_cell.style.top = i*this.cellHeight + 'px';
				_cell.style.backgroundImage= "url("+this.imgUrl+")";
				// 这里因为100%就让背景图的大小等于了一个小格子的大小
				// 而我们只需要原始图的一部分，并不是想缩小原图
				// 所以根据小格子的个数来放大图片
				_cell.style.backgroundSize = this.leverArr[1]+'00%';
				// 移动背景图，行成最后切成的一块块的效果
				_cell.style.backgroundPosition = (-j)*this.cellWidth + 'px ' + (-i)*this.cellHeight+'px';
				// 让背景图从边框开始平铺
				_cell.style.backgroundOrigin = "border-box";
				this.imgArea.appendChild(_cell);
			}
		}

		// 获取小格子的dom元素
		this.imgCells = document.querySelectorAll('.imgCell');

		// 改变body y轴的overflow
		document.body.style.overflowY = "auto";

		// 使按钮绑定事件，点击按钮开始整个游戏
		// this.gameStartBtnObj.onclick = this.clickHandle();
		this.clickHandle();
	},
	// 处理开始按钮的点击事件  * 核心部分 *
	clickHandle:function(){
		// 存储当前this
		var _self = this;
		// return function(){
			// 判断游戏是否已经开始
			if(_self.hasStart == 0){
				// 将点击事件锁上
				_self.hasStart = 1;
				// 打乱图片索引
				_self.randomArr();
				// 给小格子根据乱序数组移动到相应的位置
				_self.cellOrder();
				// 给每一张小格子注册一个点击事件
				for(var i = 0,l = _self.imgCells.length;i<l;i++){
					_self.imgCells[i].onclick = function(){
						// 如果是第一次点击
						if(_self.sel === null){
							// 把索引赋值给他
							_self.sel = this.index;
							// 给其加上一个变量
							this.style.border = "2px solid red";
						}else{
							// 第二次点击
							// 清除边框线
							_self.imgCells.forEach(function(element){
								element.style.border = "1px solid #fff";
							});
							
							// 如果两次点击的是同一张图片，就不执行下面的代码
							if(this.index === _self.sel){
								_self.sel = null;
								return ;
							}else{

								// 交换点击的两个图片的位置
								_self.cellExchange(_self.sel,this.index);
							}

							// 让sel值归为null
							_self.sel = null;
						}

					}
				}
			}
		// }
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
			// 当前应该排列在第N个（从0开始...） 
			// 除以 一行小格子的个数 得到余数 
			// 这个余数就是当前应该处在第几列（从0开始算）
			// 再乘以一个格子的宽度 就是其left值，乘以一个格子的高度 就是top值
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
		this.imgCells[from].style.left = _toCol*this.cellWidth + 'px';
		this.imgCells[from].style.top = _toRow*this.cellHeight + 'px';

		this.imgCells[to].style.left = _fromCol*this.cellWidth + 'px';
		this.imgCells[to].style.top = _fromRow*this.cellHeight + 'px';

		// 将乱序数组中的两个值交换位置
		// 定义一个临时变量来实现交换顺序
		var _temp = this.imgRandomArr[from];
		this.imgRandomArr[from] = this.imgRandomArr[to];
		this.imgRandomArr[to] = _temp;

		//如果乱序数组和原数组一致，则表示拼图已完成
		if(this.imgOrigArr.toString() === this.imgRandomArr.toString()){
			// 调用成功方法
			this.success();
		}	
	},
	// 成功
	success:function(){
		// 让开始游戏可以继续点击
		this.hasStart = 0;
		setTimeout(function(){
			alert('Puzzle Solved');
		},600);
	}

}
		
	/*	
	window.onload = function(){
		gameStart();
	}
	
	function gameStart(){
		random_d();
	}
	var d=new Array(10);
	d[0]=0;d[1]=1;d[2]=2;d[3]=3;d[4]=4;d[5]=5;d[6]=6;d[7]=7;d[8]=8; 
	
	var d_posXY=new Array(
    	[0],//同样，我们不使用第一个元素
    	[0,0],//第一个表示left,第二个表示top，比如第一块的位置为let:0px,top:0px
    	[120,0],
    	[240,0],
    	[0,120],
    	[120,120],
    	[240,120],
    	[0,240],
    	[120,240],
    	[240,240]
	);
   
	function random_d(){
		for(var i=0; i<8; ++i){
			
    			var to=Math.floor(Math.random() * (8 - i + 1)) + i;//产生随机数，范围为1到i，不能超出范围，因为没这个id的DIV
			
    		// if(d[i]!=0){
    			document.getElementById("d"+d[i]).style.left=d_posXY[to][0]+"px";
    			document.getElementById("d"+d[i]).style.top=d_posXY[to][1]+"px";
    // 		}
    		//把当前的DIV位置设置为随机产生的DIV的位置
    		 //if(d[to]!=0){
    			document.getElementById("d"+d[to]).style.left=d_posXY[i][0]+"px";
    			document.getElementById("d"+d[to]).style.top=d_posXY[i][1]+"px";
   	// 		}
    		//把随机产生的DIV的位置设置为当前的DIV的位置
    		var tem=d[to];
    		d[to]=d[i];
    		d[i]=tem;
    		//然后把它们两个的DIV保存的编号对调一下
		}
	}
	*/
	
</script>
</html>