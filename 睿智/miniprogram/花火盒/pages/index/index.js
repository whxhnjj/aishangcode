const app = getApp()
Page({
  //页面的初始数据
  data: {
    domain: app.globalData.domain,
    now: Date.parse(new Date()) / 1000
  },

  //监听页面加载
  onLoad: function (e) {
    app.globalData.theme_id = 0
    app.globalData.deadline = 0
    this.getData()
  },

  getData: function(){
    var that = this
    wx.showLoading({
      title: '加载中',
    })
    //调用接口
    wx.request({
      url: app.globalData.domain + '/api/theme/get', //仅为示例，并非真实的接口地址
      method: 'POST',
      data: {
      },
      success: function (res) {
        console.log(res.data)
        if (res.data.code > 0) {
          that.setData({
            themes: res.data.data,
          });
        }
        else {
          wx.showToast({ title: '暂无活动' });
        }
      },
      fail: function (res) {
        wx.showToast({ title: '数据加载失败' });
      },
      complete:function(){
        wx.hideLoading();
      }
    }) 
  },
 

  //用户点击右上角分享
  onShareAppMessage: function () {
    return {
      title: '花火盒投票小程序',
      path: '/pages/index/index',
      success: function (res) {
        // 转发成功
      },
      fail: function (res) {
        // 转发失败
      }
    }
  }
})