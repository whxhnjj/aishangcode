
import { Core } from '../../extend/core.js';
var core = new Core();

var app = getApp();

Page({

  onLoad: function (e) {
    let that = this;
    app.getMemberInfo(function (res) {
      app.globalData.member_rule = res.data.member.rule;
      app.globalData.is_complete = res.data.member.is_complete;
      wx.switchTab({
        url: '../index/index',
      });
    });
  },
 
})