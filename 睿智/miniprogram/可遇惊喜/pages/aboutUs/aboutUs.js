
import { Core } from '../../extend/core.js';
var core = new Core();

var WxParse = require('../../extend/wxParse/wxParse.js');

Page({

  data: {
    hostName: core.baseUrl,
  },

 
  onLoad: function () {
    let that = this;
    that.getShopInfo();
  },

  //获取店铺信息
  getShopInfo:function(){
    let that = this;
    core.request({
      url: 'api/info/system',
      method: 'GET',
      success: function (res) {
        var info = res.data.info;
        WxParse.wxParse('about', 'html', info.about, that, 5);
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