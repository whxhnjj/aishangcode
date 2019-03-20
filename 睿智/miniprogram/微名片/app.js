//app.js
App({
  globalData: {
    isIphoneX: false,
  },
  onShow: function () {
    let that = this;
    wx.getSystemInfo({
      success: function(res) {
        let modelmes = res.model;
        if (modelmes.search('iPhone X') != -1) {
          that.globalData.isIphoneX = true
        }
      }
    })
  }


})