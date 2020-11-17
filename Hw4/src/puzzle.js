var Puzzle = function(option){
	this._init(option);
}

Puzzle.prototype = {
	// 初始化数据
	_init:function(option){
	    // 图片原始索引
		this.imgOrigArr = [];
		// 打乱的图片索引
		this.imgRandomArr = [];
		
	    // 获取input的DOM元素
	    this.inputObj = document.getElementById('file');	
		// 获取到图片存放区域
		this.imgArea = document.getElementById('game');
		// 获取点击开始的按钮的DOM元素
// 		this.gameStartBtnObj = document.getElementById('gameStart');
		// 获取游戏区域的DOM元素
		this.gameAreaObj = document.getElementById('container');
		// 获取每一个小格子的DOM元素
		this.imgCells = '';
		
		// 每个小格子的宽
		this.cellWidth = 120;
		// 每个小格子的宽
		this.cellHeight = 120;
		
		// 定义一把锁,让开始游戏的按钮只能点击一次
		this.hasStart = 0;

		// 定义第一次点击的图片的索引
		this.sel = null;
	},
	gameStart:function(){
		// 获取图片路径
		this.getImageUrl();
	},
    getImageUrl:function(){	
        var self = this;
        this.btnObj.onclick = function(){
			self.inputObj.click();
		}
		
		this.inputObj.onchange = function(){
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

		//将选择图片的按钮移动到可视区域外
		this.btnObj.style.left= -this.btnObj.offsetWidth+'px';
		// 将图片移入可视区域
		// this.gameAreaObj.style.background = 'url('+this.imgUrl +')';
		this.gameAreaObj.style.left = '50%';
		this.gameAreaObj.style.transform= 'translateX(-50%)';

		// 改变body y轴的overflow
		document.body.style.overflowY = "auto";

		// 使按钮绑定事件，点击按钮开始整个游戏
		this.gameStartBtnObj.onclick = this.clickHandle();
	},
    clickHandle:function(){
		// 存储当前this
		var _self = this;
		return function(){
		    // 判断游戏是否已经开始
			if(_self.hasStart == 0){
				// 将点击事件锁上
				_self.hasStart = 1;
				// _self.randomArr();
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
							
								var ids ="";

								$(".imgCell").each(function(){
										var id =$(this).attr("id");
										ids=ids+id+"-"
								});

						      //  $.get("up.php?data=2&arr="+ids,function(result){});
					        }
					        _self.sel = null;
						}
					}
				}
			}
						
		}
    }
}