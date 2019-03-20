class Core {

  constructor() {
    this.baseUrl = 'https://leyou.site.webimage.cn/';
    // this.baseUrl = 'http://tour.amp.com/';
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
        if (res.statusCode == 200) {
          param.success && param.success(res.data);
        } else {
          if (res.statusCode == 401) {
            that._getToken(param);
          } else {
            param.fail && param.fail(res.data);
          }
        }
      },
      fail: function() {
        param.silent ? false : wx.hideNavigationBarLoading();
        wx.showToast({
          title: '请求失败，请检查网络状态或稍后再试',
          icon: 'none'
        });
      }
    });
  }

  //上传
  upload(param) {
    var that = this;
    var url = that.baseUrl + param.url;
    var filePath = param.filePath;
    var name = param.name;
    var formData = param.formData ? param.formData : {};
    param.silent ? false : wx.showNavigationBarLoading();
    wx.uploadFile({
      url: url,
      header: {
        'content-type': 'multipart/form-data',
        'token': wx.getStorageSync('token')
      },
      filePath: filePath,
      name: name,
      formData: formData,
      success: function(res) {
        param.silent ? false : wx.hideNavigationBarLoading();
        if (res.statusCode == 200) {
          param.success && param.success(JSON.parse(res.data));
        } else {
          if (res.statusCode == 401) {
            that._getToken(param, true);
          } else {
            param.fail && param.fail(JSON.parse(res.data));
          }
        }
      },
      fail: function() {
        param.silent ? false : wx.hideNavigationBarLoading();
        wx.showToast({
          title: '上传失败，请检查网络状态或稍后再试',
          icon: 'none'
        });
      }
    });
  }

  _getToken(param, upload) {
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
            if (res.statusCode == 200) {
              wx.setStorageSync('token', res.data.data.token);
              if (upload) {
                that.upload(param);
              } else {
                that.request(param);
              }
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