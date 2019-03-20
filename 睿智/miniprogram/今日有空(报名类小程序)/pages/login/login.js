//index.js
//获取应用实例
const app = getApp()

Page({
  data: {
    userInfo: {},
    imgalist: ['http://t.nongjia365.com.cn/guanli/assets/image/erweima.png']
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
  //评价图片预览
  previewImage: function (e) {
    var current = e.target.dataset.src;
    wx.previewImage({
      current: current, // 当前显示图片的http链接  
      urls: this.data.imgalist // 需要预览的图片http链接列表  
    })
  },
  // 允许从相机和相册扫码
  Scancodeimg: function (e) {
    wx.scanCode({
      success: (res) => {
        console.log(res)
      }
    })
  }
})
