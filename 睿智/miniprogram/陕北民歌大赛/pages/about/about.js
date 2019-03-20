Page({

  //页面的初始数据
  data: {
  
  },

  //生命周期函数--监听页面加载
  onLoad: function (options) {
  
  },
  //用户点击右上角分享
  onShareAppMessage: function () {
    var that = this
    return {
      title: '关于花火盒',
      path: '/pages/route/route?url=about&theme_id=' + app.globalData.theme_id,
    }
  },
})