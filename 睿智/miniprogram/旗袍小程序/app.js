

App({

  globalData: {
    domain: 'https://dati.webimage.cn',
    user: null
  },

  onLaunch: function () {
    var that = this;
    var logs = wx.getStorageSync('logs') || [];
    logs.unshift(Date.now());
    wx.setStorageSync('logs', logs);
  },

  getUserOpenId: function (callback) {
    var that = this;
    wx.login({
      success: function (data) {
        wx.setStorageSync('PHPSESSID', data.code);
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
            that.globalData.skey = res.data.data;
            callback(null, that.globalData.skey);
          },
          fail: function (res) {
            callback(res);
          }
        });
      },
      fail: function (err) {
        callback(err);
      }
    });
  }
  
})