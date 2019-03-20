class Core {

  constructor() {
    // this.baseUrl = 'http://amp.com/';
    this.baseUrl = 'https://shop.webimage.cn/';
    this.tokenUrl = 'api/token/fetch';
  }

  //请求
  request(param) {
    var that = this;
    var url = that.baseUrl + param.url;
    var data = param.data ? param.data : {};
    var method = param.method ? param.method : 'GET';
    param.silent ? false : wx.showNavigationBarLoading();
    wx.request({
      url: url,
      data: data,
      header: {
        'content-type': 'application/x-www-form-urlencoded',
        'token': wx.getStorageSync('token')
      },
      method: method,
      success: function(res) {
        param.silent ? false : wx.hideNavigationBarLoading();
        if (res.statusCode == 200 && res.data.code == 0) {
          param.success && param.success(res.data);
        } else {
          if (res.statusCode == 401) {
            that.getToken(false, param);
          } else {
            param.fail && param.fail(res.data);
          }
        }
      },
      fail: function() {
        param.silent ? false : wx.hideNavigationBarLoading();
        wx.showToast({
          title: '请求失败，请检查网络状态或者稍后再试',
          icon: 'none'
        });
      }
    });
  }

  getToken(app, param) {
    var that = this;
    var url = that.baseUrl + that.tokenUrl;
    wx.login({
      success: function(res) {
        wx.request({
          url: url,
          data: {
            code: res.code
          },
          header: {
            'content-type': 'application/x-www-form-urlencoded'
          },
          method: 'POST',
          success: function(res) {
            if (res.statusCode == 200 && res.data.code == 0) {
              wx.setStorageSync('token', res.data.data.token);
              app ? (app.onLaunchReady && app.onLaunchReady()) : that.request(param);
            } else {
              wx.showToast({
                title: '登录失败，请重试',
                icon: 'none'
              });
            }
          },
          fail: function() {
            wx.showToast({
              title: '请求失败，请检查网络状态或者稍后再试',
              icon: 'none'
            });
          }
        });
      },
      fail: function() {
        wx.showToast({
          title: '登录失败',
          icon: 'none'
        });
      }
    });
  }
}

export {
  Core
};