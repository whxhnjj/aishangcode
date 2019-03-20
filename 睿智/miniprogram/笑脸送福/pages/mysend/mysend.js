//index.js
//获取应用实例
const app = getApp()

Page({
  data: {
    hasUserInfo: {},
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
          url: 'https://womendehunli.com/api/user/sendtheme',
          method: 'POST',
          data: {
            openid: openid,
          },
          success: function (res) {
            console.log(res.data)
            if (res.data.code > 0) {
              that.setData({
                usersend: res.data.data.theme,
                sumreward: res.data.data.sumre,
                sumredivsor: res.data.data.sumdiv,

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
 * 生命周期函数--监听页面初次渲染完成
 */
  onReady: function () {

  },
  /**
  * 用户点击右上角分享
  */
  onShareAppMessage: function (res) {

  }
})
