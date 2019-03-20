
import { Core } from '../../extend/core.js';
var core = new Core();

const app = getApp();

Page({

  data: {

  },

  onShow: function () {
    let that = this;
    that.setData({
      realname: app.globalData.member.realname,
      mobile: app.globalData.member.mobile
    });
  },


  //更新用户信息
  formSubmit: function (e) {
    let that = this;
    core.request({
      url: 'api/member/complete',
      data: {
        realname: e.detail.value.realname,
        mobile: e.detail.value.mobile
      },
      method: 'POST',
      success: function (res) {
          app.globalData.member.realname = e.detail.value.realname
          app.globalData.member.mobile = e.detail.value.mobile
          wx.showToast({
            title: '更新成功',
          });
          setTimeout(function(){
            wx.switchTab({
              url: '../user/user'
            });
          },500)
          
      },
      fail: function (res) {
        wx.showToast({
          title: res.msg,
          icon: 'none'
        });
      }
    });
  }

})