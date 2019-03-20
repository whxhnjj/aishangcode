var note='欺诈'
Page({

  /**
   * 页面的初始数据
   */
  data: {
    items: [
      { name: 'cheat', value: '欺诈', checked: 'true'},
      { name: 'sexy', value: '色情'},
      { name: 'rumor', value: '政治谣言'},
      { name: 'port', value: '常识性谣言'},
      { name: 'dict', value: '诱导分享'},
      { name: 'smear', value: '恶意营销'},
      { name: 'info', value: '营私信息收集'},
      { name: 'tort', value: '其他侵权类（冒名、诽谤、抄袭）'},
    ]
  },
  radioChange: function (e) {
    note = e.detail.value
  },
  formSubmit:function(e){
    console.log(e.detail.value);

    wx.showLoading({ title: '提交中...' });
    wx.request({
      url: 'https://womendehunli.com/api/feedback/add',
      method:'POST',
      data: {
        openid: wx.getStorageSync('openid'),
        note:note,
        phone: e.detail.value.phone,
        wechat: e.detail.value.wechat
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
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
  
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {
  
  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
  
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {
  
  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {
  
  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {
  
  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {
  
  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
  
  }
})