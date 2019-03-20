
class Core {

  constructor() {
    this.baseUrl = 'http://amp.com/';
    this.tokenUrl = 'api/token/fetch';
  }

  //请求
  request(param) {
    var that = this;
    var url = that.baseUrl + param.url;
    var data = param.data ? param.data : {};
    var method = param.method ? param.method : 'GET';
    wx.showLoading({
      title: '加载中'
    });
    wx.request({
      url: url,
      data: data,
      header: {
        'content-type': 'application/x-www-form-urlencoded',
        'token': wx.getStorageSync('token')
      },
      method: method,
      success: function (res) {
        wx.hideLoading();
        if (res.statusCode == 200 && res.data.code == 0) {
          param.success && param.success(res.data);
        } else {
          if (res.statusCode == 401) {
            that._token(param);
          } else {
            param.fail && param.fail(res.data);
          }
        }
      },
      fail: function () {
        wx.hideLoading();
        console.log('wx.request调用失败');
      }
    });
  }

  //用户登录 此方法只能内部调用
  _token(param) {
    var that = this;
    var url = that.baseUrl + that.tokenUrl;
    wx.login({
      success: function (res) {
        wx.request({
          url: url,
          data: {
            code: res.code
          },
          header: {
            'content-type': 'application/x-www-form-urlencoded'
          },
          method: 'POST',
          success: function (res) {
            if (res.statusCode == 200 && res.data.code == 0) {
              wx.setStorageSync('token', res.data.data.token);
              that.request(param);
            } else {
              console.log('登录失败');
            }
          },
          fail: function () {
            console.log('wx.request调用失败');
          }
        });
      },
      fail: function () {
        console.log('wx.login调用失败');
      }
    });
  }
}

export { Core };
