var note='欺诈'
const app = getApp()
Page({

  //页面的初始数据
  data: {
    items: [
      { name: 'cheat', value: '欺诈', checked: 'true'},
      { name: 'sexy', value: '色情'},
      { name: 'rumor', value: '政治谣言'},
      { name: 'port', value: '常识性谣言'},
      { name: 'dict', value: '诱导分享'},
      { name: 'smear', value: '恶意营销'},
      { name: 'info', value: '隐私信息收集'},
      { name: 'tort', value: '其他侵权（冒名、诽谤、抄袭）'},
    ]
  },
  radioChange: function (e) {
    note = e.detail.value
  },
  formSubmit:function(e){
    var that = this
    console.log(e.detail.value);
    var desc = e.detail.value.desc
    var phone = e.detail.value.phone
    var wechat = e.detail.value.wechat
    
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
            note: note+'：'+desc,
            phone: phone,
            wechat: wechat,
            theme_id: app.globalData.theme_id
          },
          success: function (res) {
            console.log(res.data)
            if (res.data.code > 0) {
              wx.redirectTo({
                url: '../success/success'
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