const app = getApp()
var theme_id
Page({

  /**
   * 页面的初始数据
   */
  data: {
    domain: app.globalData.domain,
    is_loaded: false
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (e) {
    var that=this
    theme_id = app.globalData.theme_id
    console.log(theme_id)
    this.getData();
  },

  //用户点击右上角分享
  onShareAppMessage: function () {
    var that = this
    return {
      title: '奖品',
      path: '/pages/route/route?url=reward&theme_id=' + app.globalData.theme_id,
    }
  },

  getData:function(){
    var that = this
    wx.showLoading({
      title: '加载中',
    })
    //调用接口
    wx.request({
      url: app.globalData.domain + '/api/reward/get', //仅为示例，并非真实的接口地址
      method: 'POST',
      data: {
        theme_id: theme_id

      },
      success: function (res) {
        console.log(res)
        wx.hideLoading()
        if (res.data.code > 0) {

          that.setData({
            rewards: res.data.data.reward,
            theme: res.data.data.theme,
            is_loaded: true
          });
        } else {
          wx.showToast({
            title: '数据加载失败'
          });
        }
      },
      fail: function (res) {
        wx.showToast({ title: '数据加载失败' });
      },
      complete: function () {
        wx.hideLoading();
      }
    })
  },

})