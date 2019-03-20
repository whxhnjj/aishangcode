

import { Core } from '../../extend/core.js';
var core = new Core();

Page({
  data: {
    hostName: core.baseUrl,
    isAllSelect: false,
    totalMoney: 0,
    startX: 0, //开始坐标
    startY: 0,
    carts:[]
  },

  //监听用户下拉
  onPullDownRefresh: function () {
    let that = this;
    that.getCartList();
    that.setData({
      isAllSelect: false,
      totalMoney: 0
    });
    wx.stopPullDownRefresh()
  },

  //勾选事件处理函数  
  switchSelect: function (e) {
    // 获取item项的id，和数组的下标值  
    var Allprice = 0, i = 0;
    let id = e.target.dataset.id, index = parseInt(e.target.dataset.index);
    this.data.carts[index].isSelect = !this.data.carts[index].isSelect;
    //价钱统计
    if (this.data.carts[index].isSelect) {
      this.data.totalMoney = this.data.totalMoney + this.data.carts[index].goods_price * this.data.carts[index].buy_count;
    }
    else {
      this.data.totalMoney = this.data.totalMoney - this.data.carts[index].goods_price * this.data.carts[index].buy_count;
    }
    //是否全选判断
    for (i = 0; i < this.data.carts.length; i++) {
      Allprice = Allprice + this.data.carts[i].goods_price * this.data.carts[index].buy_count;
    }
    if (Allprice == this.data.totalMoney) {
      this.data.isAllSelect = true;
    }
    else {
      this.data.isAllSelect = false;
    }
    this.setData({
      carts: this.data.carts,
      totalMoney: this.data.totalMoney,
      isAllSelect: this.data.isAllSelect,
    })
  },

  //全选
  allSelect: function (e) {
    //处理全选逻辑
    let i = 0;
    if (!this.data.isAllSelect) {
      this.data.totalMoney = 0;
      for (i = 0; i < this.data.carts.length; i++) {
        if (this.data.carts[i].is_sale == 1){
        this.data.carts[i].isSelect = true;
        this.data.totalMoney = this.data.totalMoney + this.data.carts[i].goods_price * this.data.carts[i].buy_count;
        }
      }
    }
    else {
      for (i = 0; i < this.data.carts.length; i++) {
        this.data.carts[i].isSelect = false;
      }
      this.data.totalMoney = 0;
    }
    this.setData({
      carts: this.data.carts,
      isAllSelect: !this.data.isAllSelect,
      totalMoney: this.data.totalMoney,
    })
  },

  // 去结算
  toBuy() {
    let that = this;
    that.goBuyInfo();  
  },
  goBuyInfo: function () {
    let that = this;
    var buy_sku = [];
    var carts = that.data.carts;
    for (var i = 0; i < carts.length;i++){
      if (carts[i].isSelect == true){
        buy_sku.push({
          id: carts[i].goods_id,
          spec_value: JSON.stringify(carts[i].spec_value.split(",")),
          count: carts[i].buy_count
        })
      }
    }
    if (buy_sku.length > 0){
    core.request({
      url: 'api/submit/cart',
      data: {
        cart: JSON.stringify(buy_sku)
      },
      method: 'POST',
      success: function (res) {
        wx.navigateTo({
          url: '../buy/buy?goods=' + JSON.stringify(buy_sku)
        })
      },
      fail: function (res) {  
        wx.showToast({
          title: res.msg,
          icon:'none'
        }) 
      }
    });
    }
  },

  // 减
  bindMinus: function (e) {
    let that = this;
    var index = parseInt(e.currentTarget.dataset.index);
    var id = e.currentTarget.dataset.id;
    var num = that.data.carts[index].buy_count;
    var oneprice = that.data.carts[index].goods_price;
    if (num > 1) {
      num--;
      that.setDec(id,index)
      if (that.data.carts[index].isSelect) {
        that.setData({
          totalMoney: this.data.totalMoney - oneprice,
        })
      }
    }else{
      wx.showToast({
        title: '不能再减少了~',
        icon:'none'
      })
    }
  },

  // 加
  bindPlus: function (e) {
    let that = this;
    var index = parseInt(e.currentTarget.dataset.index);
    var id = e.currentTarget.dataset.id;
    var num = that.data.carts[index].buy_count;
    var oneprice = that.data.carts[index].goods_price;
    if (num < 100) {
      num++;
      that.setInc(id,index)
      if (this.data.carts[index].isSelect) {
        this.setData({
          totalMoney: this.data.totalMoney + oneprice,
        })
      }
    }
  },

  setInc: function (id,index) {
    let that = this;
    core.request({
      url: 'api/cart/inc',
      data: {
        id: id
      },
      method: 'GET',
      success: function (res) {
        var carts = that.data.carts;
        carts[index].buy_count = res.data.cart_info.buy_count;
        that.setData({
          carts: carts
        })
      },
      fail: function (res) {
        console.log(res);
      }
    });
  },

  setDec: function (id,index) {
    let that = this;
    core.request({
      url: 'api/cart/dec',
      data: {
        id: id,
      },
      method: 'GET',
      success: function (res) {
       console.log(res)
       var carts = that.data.carts;
       carts[index].buy_count = res.data.cart_info.buy_count;
       that.setData({
         carts: carts
       })
      },
      fail: function (res) {
        console.log(res);
      }
    });
  },

  delCart: function (id,e) {
    let that = this;
    core.request({
      url: 'api/cart/del',
      data: {
        id: id,
      },
      method: 'GET',
      success: function (res) {
        that.data.carts.splice(e.currentTarget.dataset.index, 1);
        that.setData({
          carts: that.data.carts
        })
        wx.showToast({
          title: '删除成功',
          icon:'none'
        })
      },
      fail: function (res) {
        wx.showToast({
          title: '删除失败，请稍后再试',
          icon: 'none'
        })
      }
    });
  },

  onShow: function () {
    let that = this;
    that.getCartList();
    that.setData({
      isAllSelect: false,
      totalMoney: 0
    })
  },

  //手指触摸动作开始 记录起点X坐标
  touchstart: function (e) {
    //开始触摸时 重置所有删除
    this.data.carts.forEach(function (v, i) {
      if (v.isTouchMove)//只操作为true的
        v.isTouchMove = false;
    })
    this.setData({
      startX: e.changedTouches[0].clientX,
      startY: e.changedTouches[0].clientY,
      carts: this.data.carts
    })
  },

  //滑动事件处理
  touchmove: function (e) {
    var that = this,
      index = e.currentTarget.dataset.index,//当前索引
      startX = that.data.startX,//开始X坐标
      startY = that.data.startY,//开始Y坐标
      touchMoveX = e.changedTouches[0].clientX,//滑动变化坐标
      touchMoveY = e.changedTouches[0].clientY,//滑动变化坐标
      //获取滑动角度
      angle = that.angle({ X: startX, Y: startY }, { X: touchMoveX, Y: touchMoveY });
    that.data.carts.forEach(function (v, i) {
      v.isTouchMove = false
      //滑动超过30度角 return
      if (Math.abs(angle) > 30) return;
      if (i == index) {
        if (touchMoveX > startX) //右滑
          v.isTouchMove = false
        else //左滑
          v.isTouchMove = true
      }
    })
    //更新数据
    that.setData({
      carts: that.data.carts
    })
  },

  /**
   * 计算滑动角度
   * @param {Object} start 起点坐标
   * @param {Object} end 终点坐标
   */
  angle: function (start, end) {
    var _X = end.X - start.X,
      _Y = end.Y - start.Y
    //返回角度 /Math.atan()返回数字的反正切值
    return 360 * Math.atan(_Y / _X) / (2 * Math.PI);
  },

  //删除事件
  del: function (e) {
    let that = this;
    var index = parseInt(e.currentTarget.dataset.index);
    var id = e.currentTarget.dataset.id;
    var oneprice = that.data.carts[index].goods_price;
    var num = that.data.carts[index].buy_count;
    that.delCart(id,e)
    if (that.data.carts[index].isSelect) {
      that.setData({
        totalMoney: that.data.totalMoney - oneprice*num,
      })
    }
  },


  //请求商品列表
  getCartList: function(){
    let that = this;
    core.request({
      url: 'api/cart/lists',
      method: 'GET',
      success: function (res) {
        var carts = res.data.cart;
        for (var i = 0; i < carts.length; i++) {
          carts[i].isTouchMove = false;
          carts[i].isSelect = false;
          carts[i].spec_value = JSON.parse(carts[i].spec_value).toString();
        }
        that.setData({
          carts: carts
        })
      },
      fail: function (res) {
        console.log(res);
      }
    });

  },

  goIndex:function(){
    wx.switchTab({
      url: '../index/index'
    })

  }
})
