//app.js
App({
  globalData: {
    domain:'https://hongguanqipao.com',
    userInfo: null,
    theme_id:0,
    deadline:0
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

    if (that.globalData.skey) {
      callback(null, that.globalData.skey)
    } else {
      wx.login({
        success: function (data) {
          console.log(data)
          wx.setStorageSync('PHPSESSID', data.code);
            //console.log(that.globalData.userInfo)
            wx.request({
              url: that.globalData.domain+'/api/wechat/login',
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
    }
  },


  getUserInfo: function(){
    var that = this
    wx.getUserInfo({
      withCredentials: false,
      success: function (res) {
        console.log(res)
        that.globalData.userInfo = res.userInfo
      }
    })
  },

  //多图上传
  uploadimg: function (data) {
    var that = this,
    i = data.i ? data.i : 0,
    succ = data.succ ? data.succ : 0,
    fail = data.fail ? data.fail : 0;
    wx.uploadFile({
      url: data.url,
      filePath: data.path[i],
      name: 'file',
      formData: {
        id: data.id,
        i:i
      },
      success: function (res) {
        succ++;
        console.log(res);
        console.log(i);

      },
      fail: function (res) {
        fail++;
        console.log('fail:' + i + "fail:" + fail);
      },
      complete: function (res) {
        console.log(i);
        i++;
        if (i == data.path.length) {  //当图片传完时，停止调用
          data.success();
          console.log('执行完毕');
          console.log('成功：' + succ + " 失败：" + fail);
        } else {
          console.log(i);
          data.i = i;
          data.succ = succ;
          data.fail = fail;
          that.uploadimg(data);
        }

      }
    })
  }
})