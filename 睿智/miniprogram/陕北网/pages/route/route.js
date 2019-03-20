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
    console.log(e)
    app.globalData.theme_id = e.theme_id
    if (e.scene) { app.globalData.theme_id = e.scene }
    var url
    if (e.url) {
      url = '/pages/' + e.url + '/' + e.url
    }else{
      url = '/pages/theme/theme'
    }
    wx.switchTab({
      url: url
    })


  }
})