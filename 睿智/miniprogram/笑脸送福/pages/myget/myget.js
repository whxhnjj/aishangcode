//index.js
//获取应用实例
const app = getApp()

Page({
  data: {
    userInfo: {}
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


    app.getUserOpenId(function (err, openid) {
      if (!err) {

        //获取发出的数据
        wx.showLoading({ title: '加载中' });
        wx.request({
          url: 'https://womendehunli.com/api/user/received',
          method: 'POST',
          data: {
            openid: openid,
          },
          success: function (res) {
            console.log(res.data)
            if (res.data.code > 0) {
              that.setData({
                received: res.data.data.receive,

                sumreward: res.data.data.sumre,
                sumnum: res.data.data.sumnum,
              });
              wx.hideLoading();
            } else {
              wx.showToast({
                title: '请重刷',
                image: '../images/cuowu.png'
              });
            }
          },
          fail: function (res) {
            wx.showToast({ title: '数据加载失败' });
          }
        });





      } else {
        console.log('err:', err)
        self.setData({
          loading: false
        })
      }
    })
   
  },
  /**
  * 用户点击右上角分享
  */
  onShareAppMessage: function (res) {

  }
})
