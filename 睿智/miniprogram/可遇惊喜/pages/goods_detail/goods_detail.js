
import { Core } from '../../extend/core.js';
var core = new Core();

const app = getApp();

var WxParse = require('../../extend/wxParse/wxParse.js');

Page({

  data: {
    hostName: core.baseUrl,
    mengShow: false,
    aniStyle: true,
    btn_state:false,
    goods:{
      price:0,
      market_price:0,
      sale_count:0,
      sale_ext_count:0,
      store_count:999
    },
    index_btn:false,
    // video_state:true 轮播视频
    video_state: false
  },

  onLoad: function (e) {

    let that = this;
    var scene =  e.scene ? e.scene.split('-') : [];
    // console.log(scene)
    if (scene && scene[0] == 'p'){
      //poster
      wx.setStorageSync('member_id', scene[1]);
      that.setData({
        index_btn: true
      });
    }
    if (scene && scene[0] == 's') {
      //share
      that.setData({
        index_btn: true
      });
    }
    that.setData({
      isIphone: app.globalData.isIphone,
      id: scene[2] ? scene[2] : e.id
    });
    that.getGoodsDetail();
  },

  
  //获取商品信息
  getGoodsDetail: function () {
    let that = this;
    core.request({
      url: 'api/goods/details',
      method: 'GET',
      data: {
        id:that.data.id
      },
      success: function (res) {

        var goods = res.data.goods;
        
        var now_time = new Date().getTime();
        goods.goods_pic = goods.goods_pic.split(',');
        goods.price = (goods.price/100).toFixed(2);
        goods.market_price = (goods.market_price / 100).toFixed(2);
        goods.back_cash = (goods.back_cash / 100).toFixed(2);
        
        WxParse.wxParse('content', 'html', goods.content, that, 5);
        WxParse.wxParse('usage_rule', 'html', goods.usage_rule, that, 5);
        WxParse.wxParse('statements', 'html', goods.statements, that, 5);
    
        if (goods.end_time != 0 && goods.begin_time != 0){
          if (now_time > goods.end_time * 1000 || now_time < goods.begin_time * 1000 || goods.store_count == 0) {
            that.setData({
              btn_state: true
            });
          }
        }      
     
        that.setData({
          goods:goods
        });
        

      },
      fail: function (res) {
        that.setData({
          error_msg:res.msg,
          error_state : 1
        });
      }
    });
  },

  //立即购买
  buyGoods:function(){
    let that = this;
    core.request({
      url: 'api/goods/verify',
      method: 'GET',
      data: {
        id: that.data.id
      },
      success: function (res) {
        wx.navigateTo({
          url: '../goods_buy/goods_buy?id=' + that.data.id,
        });
      },
      fail: function (res) {
        wx.showToast({
          title: res.msg,
          icon: 'none'
        });
      }
    });
  },

  //拨打电话
  callPhone:function(e){
    wx.makePhoneCall({
      phoneNumber: e.target.dataset.contacts
    });
  },

  //打开地图
  openMap: function (e) {
    var latlng = e.target.dataset.latlng.split(',');
    wx.openLocation({
      latitude: Number(latlng[0]),
      longitude: Number(latlng[1]),
      scale: 28,
      name: e.target.dataset.name,
      address: e.target.dataset.address
    })
  },

  //分享海报
  shareGoods:function(){
    let that = this;
    console.log(that.data.id);
    core.request({
      url: 'api/goods/poster',
      method: 'GET',   
      data: {
        id: that.data.id
      },
      success: function (res) {
        wx.previewImage({
          urls: [that.data.hostName + res.data.poster + '?' + Math.random()]
        });
      },
      fail: function (res) {
        wx.showToast({
          title: res.msg,
          icon: 'none'
        });
      }
    });

  },

  //回首页
  backIndex:function(){
    wx.switchTab({
      url: '../index/index'
    });
  },

  //弹出层显示
  showPop: function () {
    let that = this;
    that.setData({
      mengShow: true,
      aniStyle: true
    });
  },

  //弹出层隐藏
  outbtn: function () {
    let that = this;
    that.setData({
      aniStyle: false
    });
    setTimeout(function () {
      that.setData({
        mengShow: false
      })
    }, 500);
  },

  //阻止冒泡事件
  inbtn: function (e) { },

  //视频显示
  videoShow: function(){
    let that = this;
    that.setData({
      video_state: true,
    });
  },

  //视频隐藏
  videoHide: function(){
    let that = this;
    that.setData({
      video_state: false
    });
  },

  // 视频
  // bindPlay: function (e) {
  //   let that = this;
  //   that.videoContext = wx.createVideoContext('mdcVideo');
  //   that.videoContext.pause();
  //   setTimeout(function () {
  //     that.videoContext.play();
  //   }, 150);
  //   that.setData({
  //     video_state:false
  //   });
  // },

  // backPlay:function(){
  //   let that = this;
  //   that.setData({
  //     video_state: true
  //   });
  // },

  // fullsPlay:function(e){
  //   let that = this;
  //   if (e.detail.fullScreen == false ){
  //     that.setData({
  //       video_state: true
  //     });
  //   }
  // },

  
  //分享
  onShareAppMessage: function () {
    let that = this;
    return {
      title: that.data.goods.share_desc ? that.data.goods.share_desc : that.data.goods.name,
      path: 'pages/goods_detail/goods_detail?scene=s-' + app.globalData.member.id + '-' + that.data.goods.id
    }
  }
})