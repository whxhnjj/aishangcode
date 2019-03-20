const app = getApp()
Page({
  /**
   * 页面的初始数据
   */
  data: {
    avatarUrl: "",//用户头像  
    nickName: "",//用户昵称
    checkcode: ""
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    app.getUserInfo()
    var that = this;
    that.getData();
    /**  
     * 获取用户信息  
     */
    wx.getUserInfo({
      success: function (res) {
        console.log(res);
        var avatarUrl = 'userInfo.avatarUrl';
        var nickName = 'userInfo.nickName';
        that.setData({
          [avatarUrl]: res.userInfo.avatarUrl,
          [nickName]: res.userInfo.nickName,
        })
      }
    })  
  },

  //获取数据
  getData: function () {
    var that = this
    app.getUserOpenId(function (err, skey) {
      if (!err) {
        //获取发出的数据
        wx.showLoading({ title: '加载中' });
        wx.request({
          url: app.globalData.domain + '/api/actor/getactorcheckcode',
          method: 'POST',
          header: {
            'content-type': 'application/x-www-form-urlencoded',
            'Cookie': 'PHPSESSID=' + wx.getStorageSync('PHPSESSID')
          },
          data: {
            theme_id: app.globalData.theme_id,
            skey: skey
          },
          success: function (res) {
            wx.hideLoading();
            console.log(res)
            if (res.data.code > 0) {
                that.setData({
                  checkcode: res.data.data
                });
            }
            else {
              that.setData({
                checkcode: 'none'
              });
            }
          },
          fail: function (res) {
            wx.hideLoading();
            wx.showToast({ title: '数据加载失败' });
          }
        });

      } else {
        console.log('err:', err)
        that.setData({
          loading: false
        })
      }
    })
  },
  copyCheckcode: function (e) {
    var that = this;
    wx.setClipboardData({
      data: that.data.checkcode,
      success: function (res) {
        wx.showModal({
          title: '提示',
          content: '复制成功',
          showCancel: false
        })
      }
    });
  }
})