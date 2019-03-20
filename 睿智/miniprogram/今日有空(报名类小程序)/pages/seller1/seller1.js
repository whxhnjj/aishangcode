const app = getApp()

Page({
  data: {
    userInfo: {},
    listData: [
      { "code": "项目", "text": "人数", "type": "价格" },
      { "code": "微课程", "text": "10", "type": "￥32" },
      { "code": "亲子", "text": "5", "type": "￥312" },
      { "code": "超值体验", "text": "8", "type": "￥132" },
      { "code": "温泉", "text": "4", "type": "￥42" },
      { "code": "滑雪", "text": "6", "type": "￥52" },
      { "code": "健身", "text": "5", "type": "￥37" }
    ]
  },
  onLoad: function () {
    console.log('onLoad')
    var that = this
    //调用应用实例的方法获取全局数据
    app.getUserInfo(function (userInfo) {
      //更新数据
      that.setData({
        userInfo: userInfo
      })
    })
  },
  // 允许从相机和相册扫码
  Scancodeimg: function (e) {
    wx.scanCode({
      success: (res) => {
        console.log(res)
      }
    })
  },
  /**
  * 用户点击右上角分享
  */
  onShareAppMessage: function (res) {

  }
})
