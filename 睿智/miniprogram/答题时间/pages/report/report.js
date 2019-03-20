const app = getApp()
var exam_id;
var url;


Page({

  data: {
    domain: app.globalData.domain 
  },

  onLoad: function (e) {
    var that = this
    console.log(e)
    exam_id = e.exam_id
    url=e.url
    if (e.scene) { exam_id = e.scene }
  },

  formSubmit: function (e) {
    var that = this
    console.log(e.detail.value);
    var desc = e.detail.value.desc
    if(desc ==""){
      wx.showModal({
        title: '提示',
        content: '错误信息不能为空',
        showCancel: false
      })
      return false
    }
    wx.showLoading({ title: '提交中...' });
    app.getUserOpenId(function (err, skey) {
      if (!err) {
        //获取发出的数据
        wx.request({
          url: app.globalData.domain + '/api/feedback/add',
          method: 'POST',
          header: {
            'content-type': 'application/x-www-form-urlencoded',
            'Cookie': 'PHPSESSID=' + wx.getStorageSync('PHPSESSID')
          },
          data: {
            skey: skey,
            note: '错题报告：'+ desc,
            phone: '',
            wechat: '',
            path: "/pages/" + url + "/" + url + "?exam_id=" + exam_id
            // exam_id: exam_id
          },
          success: function (res) {
            console.log(res.data)
            if (res.data.code > 0) {
              wx.redirectTo({
                url: '../success/success?state=1&exam_id=' + exam_id
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