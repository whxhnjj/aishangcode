//app.js
App({

  globalData: {
    // domain: 'http://dati.web',
    domain: 'https://dati.webimage.cn',
    // domain: 'http://exam.web',
    user: null,
    own_times:0
  },

  onLaunch: function () {
    var that = this
    //登录
    //this.appLogin();
    //调用API从本地缓存中获取数据
    var logs = wx.getStorageSync('logs') || []
    logs.unshift(Date.now())
    //console.log(logs);
    wx.setStorageSync('logs', logs)
    //that.getUserInfo()
  },

  getUserOpenId: function (callback) {
    var that = this

    // if (that.globalData.skey) {
    //   callback(null, that.globalData.skey)
    // } else {
      wx.login({
        success: function (data) {
          console.log(data)
          wx.setStorageSync('PHPSESSID', data.code);
          //console.log(that.globalData.userInfo)
          wx.request({
            url: that.globalData.domain + '/api/wechat/login',
            method: 'POST',
            header: {
              'content-type': 'application/x-www-form-urlencoded',
              'Cookie': 'PHPSESSID=' + data.code
            },
            data: {
              code: data.code
            },
            success: function (res) {
              console.log('登录成功', res)
              //wx.setStorageSync('openid', res.data.data)
              console.log(res)

              that.globalData.skey = res.data.data
              console.log(that.globalData.skey)
              callback(null, that.globalData.skey)
            },
            fail: function (res) {
              console.log('拉取用户openid失败，将无法正常使用开放接口等服务', res)
              callback(res)
            }
          })



        },
        fail: function (err) {
          console.log('wx.login 接口调用失败，将无法正常使用开放接口等服务', err)
          callback(err)
        }
      })
    // }
  }


})