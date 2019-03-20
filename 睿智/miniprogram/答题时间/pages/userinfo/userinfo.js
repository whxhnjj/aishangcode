// pages/userinfo/userinfo.js
const app = getApp()
var exam_id;
var times;
Page({

  onLoad: function (e) {
    var that = this
    console.log(e)
    exam_id = e.exam_id
    times = e.times
  },

  formSubmit: function (e) {
    var that = this
    console.log(e.detail.value);
    var real_name = e.detail.value.real_name.replace(/(^\s*)|(\s*$)/g, "")
    var mobile = e.detail.value.mobile
    if (real_name == ''){
      wx.showModal({
        title: '提示',
        content: '请输入姓名',
        showCancel: false,
        confirmColor: "#4d83e3"
      })
      return
    }
    if (mobile == '') {
      wx.showModal({
        title: '提示',
        content: '请输入手机号码',
        showCancel: false,
        confirmColor: "#4d83e3"
      })
      return
    }
    if (!/^1[34578]\d{9}$/.test(mobile)){
      wx.showModal({
        title: '提示',
        content: '请输入正确的手机号码',
        showCancel: false,
        confirmColor:"#4d83e3"
      })
      return
    }


    wx.showLoading({ title: '提交中...' });
    app.getUserOpenId(function (err, skey) {
      if (!err) {
        //获取发出的数据
        wx.request({
          url: app.globalData.domain + '/api/member/updatemember',
          method: 'POST',
          header: {
            'content-type': 'application/x-www-form-urlencoded',
            'Cookie': 'PHPSESSID=' + wx.getStorageSync('PHPSESSID')
          },
          data: {
            skey: skey,
            real_name: real_name,
            mobile: mobile
          },
          success: function (res) {
            console.log(res.data)
            if (res.data.code > 0) {
              wx.redirectTo({
                url: "../subject/subject?exam_id=" + exam_id + "&times=" + times,
              })
            } else {
              wx.showToast({ title: '提交失败' });
            }
          },
          fail: function (res) {
            wx.showToast({ title: '提交失败' });
          }
        });

      } else {
        console.log('err:', err)
        that.setData({
          loading: false
        })
      }
    })
  }

  
})