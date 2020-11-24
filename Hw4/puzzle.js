// define puzzle constructor
var Puzzle = function(option){
	this._init(option);
}
Puzzle.prototype = {
	_init:function(option){
		this.leverNow = option.leverNow <=2 ? 2 : option.leverNow || 2;
		this.leverArr = [this.leverNow,this.leverNow];
		this.imgOrigArr = [];
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
	// create ramdom array for graph
	randomArr:function(){
		this.imgRandomArr = [];
		var _flag = true;
		for(var i=0,l=this.imgOrigArr.length;i<l;i++){
			var order = Math.floor(Math.random()*this.imgOrigArr.length);
			if(this.imgRandomArr.length>0){
				while(this.imgRandomArr.indexOf(order) >-1){
					order = Math.floor(Math.random()*this.imgOrigArr.length);
				}
			}
			this.imgRandomArr.push(order);
		}

		// check if the ramdom array is the same as initial array
		if(this.imgRandomArr.length === this.imgOrigArr.length){
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

		// 
		if(_flag){
			this.randomArr();
		}
	},
	//move tile to the index in imgRandomArr
	cellOrder:function(){
		var _self = this;

		this.imgCells.forEach(function(element,index){
			element.id = _self.imgRandomArr[index];
			element.style.left = _self.imgRandomArr[index] % _self.leverArr[1] * _self.cellWidth+'px';
			element.style.top = Math.floor(_self.imgRandomArr[index] / _self.leverArr[1]) * _self.cellHeight+'px';
			
		});
	},
	// swap tiles
	cellExchange:function(from,to){
		var _fromRow = Math.floor(this.imgRandomArr[from] / this.leverArr[1]);
		var _fromCol = this.imgRandomArr[from] % this.leverArr[1];
		var _toRow = Math.floor(this.imgRandomArr[to] / this.leverArr[1]);
		var _toCol = this.imgRandomArr[to] % this.leverArr[1];

		var idtmp = this.imgCells[from].id;
		var idtmpx = this.imgCells[to].id;


		this.imgCells[from].id = idtmpx;
		this.imgCells[from].style.left = _toCol*this.cellWidth + 'px';
		this.imgCells[from].style.top = _toRow*this.cellHeight + 'px';

		this.imgCells[to].id = idtmp;
		this.imgCells[to].style.left = _fromCol*this.cellWidth + 'px';
		this.imgCells[to].style.top = _fromRow*this.cellHeight + 'px';

		var _temp = this.imgRandomArr[from];
		this.imgRandomArr[from] = this.imgRandomArr[to];
		this.imgRandomArr[to] = _temp;

		//
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



  　

