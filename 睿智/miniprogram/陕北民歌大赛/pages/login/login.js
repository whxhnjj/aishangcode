// pages/login/login.js

const app = getApp()
var url

Page({

  /**
   * 页面的初始数据
   */
  data: {

  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (e) {
    if (e.url) {
      console.log('url:', decodeURIComponent(e.url))
      url = decodeURIComponent(e.url)
    } else {
      url = '../index/index'
    }
  },


  onGotUserInfo: function (res) {
    if (res.detail.userInfo) {
      app.globalData.userInfo = res.detail.userInfo;
      wx.redirectTo({
        url: url
      });
      wx.switchTab({
        url: url
      });
    }
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})