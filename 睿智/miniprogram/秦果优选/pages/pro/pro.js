// pages/pro/pro.js

var app = getApp();
import { Core } from '../../extend/core.js';
var core = new Core();
var WxParse = require('../../extend/wxParse/wxParse.js');
var timer;

Page({
  data: {
    hostName: core.baseUrl,
    coupon_state: 0,
    pop_state: 0,
    pronum: 1,
    // 视频的封面图显示消失的参数
    xiaoshi: false,
    // 视频显示消失的参数
    mdc_show: true,
    goods:[],
    pop_sku_bottom: -900,
    goods_price_min:0,
    goods_price_max:0
  },

  sortNumber: function (a, b) {
    return a - b
  },

  //请求商品信息
  getProList: function (goods_id) {
    let that = this;
    core.request({
      url: 'api/goods/details',
      data: {
        id: goods_id
      },
      method: 'GET',
      success: function (res) {
        var goods = res.data.detail;
        var goods_price_section = [];
        that.setData({
          goods: goods,
          spec_key: goods.spec_key,
          sku: goods.skus[0]
        })
        WxParse.wxParse('content', 'html', goods.content, that, 5);
        var specs = [];
        var spec_key = goods.spec_key;
        var skus_one = [];
        var skus_two = [];
        var skus_three = [];
        for (var i = 0; i < goods.skus.length; i++) {
          skus_one.push(goods.skus[i].spec_value);
          goods_price_section.push(goods.skus[i].price)
        }
        goods_price_section.sort(that.sortNumber);
        for (var j = 0; j < spec_key.length; j++) {
          skus_two = [];
          for (var i = 0; i < skus_one.length; i++) {
            if (!skus_two.includes(skus_one[i][j])) {
              skus_two.push(skus_one[i][j])
            }
          }
          skus_three = [];
          for (var n = 0; n < skus_two.length; n++) {
            if(n == 0){
              skus_three.push({
                value: skus_two[n],
                isSelect: true
              });
            }else{
              skus_three.push({
                value: skus_two[n],
                isSelect: false
              })
            }
          }
          if (j == 0) {
            specs.push({
              name: spec_key[j],
              isSelect: true, //商品规则name是否被选中，默认无
              child: skus_three,
            })
          }else{
            specs.push({
              name: spec_key[j],
              isSelect: true, //商品规则name是否被选中，默认无
              child: skus_three,
            })
          }
          
        }
        var new_spec_key =[];
        for (var i = 0; i < specs.length;i++){
          new_spec_key.push(specs[i].child[0].value);          
        }
        that.setData({
          specs: specs,
          spec_key: new_spec_key,
          goods_price_min: goods_price_section[0],
          goods_price_max: goods_price_section[goods_price_section.length - 1]
        })
      },
      fail: function (res) {
        that.setData({
          pro_error: res.msg,
        })
      }
    });
  },

  //请求商品sku
  getComboInfo: function (id, combo) {
    let that = this;
    core.request({
      url: 'api/goods/sku',
      data: {
        id: id,
        spec_value: JSON.stringify(combo)
      },
      method: 'POST',
      success: function (res) {
        that.setData({
          sku: res.data.sku
        })
      },
      fail: function (res) {
        console.log(res);
      }
    });
  },

  //请求购物车数量
  requestCartNum: function () {
    let that = this;
    core.request({
      url: 'api/cart/counts',
      method: 'GET',
      success: function (res) {
        that.setData({
          cart_count: res.data.count
        })
      },
      fail: function (res) {
      }
    });
  },

  //加入购物车
  addCarInfo: function (id, spc_key, pronum) {
    let that = this;
    core.request({
      url: 'api/cart/add',
      data: {
        id: id,
        spec_value: JSON.stringify(spc_key),
        count: pronum
      },
      method: 'POST',
      success: function (res) {
        wx.showToast({
          title: '加入成功',
          icon: 'none'
        });
        that.requestCartNum();
      },
      fail: function (res) {
        wx.showToast({
          title: res.msg,
          icon: 'none'
        })
      }
    });
  },

  //立即购买
  goBuyInfo: function (id, spc_key, pronum) {
    let that = this;
    core.request({
      url: 'api/submit/direct',
      data: {
        id: id,
        spec_value: JSON.stringify(spc_key),
        count: pronum
      },
      method: 'POST',
      success: function (res) {
        var goods = [];
        goods.push({
          id: id,
          spec_value: JSON.stringify(spc_key),
          count: pronum
        })
        goods = JSON.stringify(goods)
        wx.navigateTo({
          url: '../buy/buy?goods=' + goods
        })
      },
      fail: function (res) {
        wx.showToast({
          title: res.msg,
          icon: 'none'
        })
      }
    });
  },

  //优惠劵显示隐藏
  couponShow: function () {
    let that = this;
    if (that.data.coupon_state == 0) {
      that.setData({ coupon_state: 1 })
    } else if (that.data.coupon_state == 1) {
      that.setData({ coupon_state: 0 })
    }
  },

  //商品规格隐藏
  closePop: function () {
    let that = this;
    that.setData({ pop_state: 0, pop_sku_bottom:-600})
  },

  //商品规格弹出
  showPop: function () {
    let that = this;
    that.setData({ pop_state: 1 })
    if (that.data.isIphoneX){
      that.startMove(68)
    }else{
      that.startMove(0)
    }
    
  },

  startMove:function(targrt){
    let that =this;
    var x = that.data.isIphoneX ? 68 : -1;
    clearInterval(timer);
    timer = setInterval(function(){
      if (that.data.pop_sku_bottom > x){
        clearInterval(timer);
      }else{
        var speed = (x + 1 -that.data.pop_sku_bottom) / 5;
        var now_bottom = that.data.pop_sku_bottom;
        now_bottom += speed;
        that.setData({ pop_sku_bottom: now_bottom});
      }
    },10)
  },

  onLoad: function (e) {
    var that = this;
    that.getProList(e.goods_id);
    that.requestCartNum();
    that.setData({
      isIphoneX: app.globalData.isIphoneX
    })
  },

  // 减
  bindMinus: function (e) {
    // console.log(index);
    var num = this.data.pronum;
    if (num > 1) {
      num--;
    }
    this.setData({
      pronum: num,
    });
  },

  // 加
  bindPlus: function (e) {
    let that = this;
    var num = that.data.pronum;
    if (num < 100) {
      num++;
    }
    this.setData({
      pronum: num,
    });
  },

  //防止事件冒泡
  inbtn: function (e) {  
  }, 

  //用户点击右上角分享
  onShareAppMessage: function () {
    let that = this;
    return {
       title: that.data.goods.share_desc ? that.data.goods.share_desc : that.data.goods.name
    }
  },

  bindPlay: function (e) {
    var that = this;
    that.videoContext = wx.createVideoContext('mdcVideo');
    // that.videoContext.play()
    that.videoContext.pause();
    setTimeout(function () {
      that.videoContext.play()
    }, 150);
    that.setData({
      xiaoshi: true,
      mdc_show: false
    })

  },
  //安卓系统能检测到 video touchemove 滑动的x距离，已此作为切换的swiper的凭证
  //ios系统，检测不到video touchemove 滑动的x距离，通过cover-view 点击事件进行切换
  mdc_move: function (e) {
    var that = this;
    that.videoContext = wx.createVideoContext('mdcVideo');
    if (e.touches[0].clientX > 20) {
      that.setData({
        xiaoshi: false,
        mdc_show: true,
      })
    }
  },

  //sku
  clickTab: function (e) {
    let that = this;
    var index = e.target.dataset.index;
    var key = e.target.dataset.key;
    var specs = that.data.specs;
    console.log(e, specs)
    for (var i = 0; i < specs[index].child.length; i++) {
      if (i == key) {
        specs[index].child[key].isSelect = true;
      } else {
        specs[index].child[i].isSelect = false;
      }
    }
    specs[index].isSelect = true;
    that.setData({
      specs: specs
    })
    var combo = [];
    for (var i = 0; i < specs.length; i++) {
      for (var j = 0; j < specs[i].child.length; j++) {
        if (specs[i].child[j].isSelect == true) {
          combo.push(specs[i].child[j].value)
          that.setData({
            spec_key: combo
          })
        }
      }
    }
    if (combo.length == specs.length) {
      that.getComboInfo(that.data.goods.id, combo)
    }
    // console.log(JSON.stringify(combo))
  },

  //加入购物车
  addCar: function () {
    let that = this;
    that.setData({
      add_car:true,
      go_buy:false
    })
    that.showPop()
  },

  //加入购物车-弹框
  addCarPop: function () {
    let that = this;
    var specs = that.data.specs;
    for (var i = 0; i < specs.length;i++){
      if (!specs[i].isSelect){
        wx.showToast({
          title: '请选择商品规格',
          icon:'none'
        });
        return;
      }
    }
    that.addCarInfo(that.data.goods.id, that.data.spec_key, that.data.pronum);
    that.closePop();
  },

  //购物车跳转
  goCart: function () {
    wx.switchTab({
      url: '../cart/cart'
    })
  },

  //首页跳转
  gotoIndex: function (){
    wx.switchTab({
      url: '../index/index',
    })
  },

  goToBuy: function () {
    let that = this;
    that.setData({
      add_car: false,
      go_buy: true
    })
    that.showPop()
  },

  goToBuyPop: function () {
    let that = this;
    var specs = that.data.specs;
    for (var i = 0; i < specs.length; i++) {
      if (!specs[i].isSelect) {
        wx.showToast({
          title: '请选择商品规格',
          icon: 'none'
        });
        return;
      }
    }
    that.goBuyInfo(that.data.goods.id, that.data.spec_key, that.data.pronum);
    that.closePop();
  },

})