
import { Core } from '../../extend/core.js';
var core = new Core();

const app = getApp();

Page({

  data: {
    price:[],
    member_rule: 0
  },

  onLoad: function () {
    let that = this;
    that.setData({
      member_rule: app.globalData.member_rule
    });
    that.requestPriceType();
  },

  onShow: function () {
  
  },

  //请求价格分类
  requestPriceType: function(){
    let that = this;
    core.request({
      url: 'api/complete/price',
      method: 'GET',
      success: function (res) {
        that.setData({
          price:res.data.price
        })
      },
      fail: function (res) {
      }
    });
  },

  //去首页
  gotoIndex:function(){
    wx.switchTab({
      url: '../index/index'
    })
  },

  onShareAppMessage: function () {
  }
})