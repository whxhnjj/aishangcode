
import { Core } from '../../extend/core.js';
var core = new Core();

var WxParse = require('../../extend/wxParse/wxParse.js');

const app = getApp();

Page({

  data: {

  },

  onLoad: function (e) {
    let that = this; 
    that.setData({ type:e.type});
    that.getSystem();
  },


  //获取说明
  getSystem:function(){
    let that = this;
    core.request({
      url: 'api/info/system',
      method: 'GET',
      success: function (res) {
        var info = res.data.info;
        WxParse.wxParse('promotion_desc', 'html', info.promotion_desc, that, 5);
        WxParse.wxParse('integral_desc', 'html', info.integral_desc, that, 5);
        that.setData({
          info: info
        });
      },
      fail: function (res) {
      }
    });
  },

  //拨打电话
  callPhone: function (e) {
    wx.makePhoneCall({
      phoneNumber: e.target.dataset.contacts
    });
  }

})