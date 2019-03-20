const app = getApp()
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
    var that = this
    var key = e.key;
    var theme_id = e.theme_id
    wx.request({
      url: app.globalData.domain + '/api/theme/search',
      method: 'POST',
      data: {
        theme_id: theme_id,
        sear: key

      },
      success: function (res) {
        console.log(res)
        wx.hideLoading()
        if (res.data.code > 0) {
          that.setData({
            domain: app.globalData.domain,
            actors: res.data.data
          });
        }
        else {
          wx.showToast({ title: '数据加载失败，刷新试试。' });
        }
      },
      fail: function (res) {
        wx.showToast({ title: '数据加载失败' });
      }
    });
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
  
  }
})