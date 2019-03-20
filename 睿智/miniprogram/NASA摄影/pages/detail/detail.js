// pages/detail/detail.js
Page({

  /**
   * 页面的初始数据
   */
  data: {
    nullHouse: true, 
    movies: [
      { url: '/pages/images/detail01.jpg' },
      { url: '/pages/images/detail02.jpg' },
      { url: '/pages/images/detail03.jpg' },
      { url: '/pages/images/detail04.jpg' },
    ]    
  },

  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
  
  },
  calphone: function (e) {
    wx.makePhoneCall({
      phoneNumber: '10086'
    })
  },
  //弹出操作框
  depict: function () {
    this.setData({
      nullHouse: false, //弹窗显示
    })
  },
  //取消操作框
  cancel: function () {
    this.setData({
      nullHouse: true, //弹窗关闭
    })
  },
  submit:function(e){
    var phone = e.detail.value.phone;
    var myreg = /^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1})|(17[0-9]{1}))+\d{8})$/;  
    if (phone.length == '') {
      wx.showToast({
        title: '手机号位数为空',
        image: '/pages/images/cuowu.png',
        duration: 1500
      })
      return
    }
    if (phone.length < 11) {
      wx.showToast({
        title: '手机号长度有误',
        image: '/pages/images/cuowu.png',
        duration: 1500
      })
      return
    }
    if (!myreg.test(phone)) {
      wx.showToast({
        title: '手机号有误！',
        image: '/pages/images/cuowu.png',
        duration: 1500
      })
      return;
    } else {
      wx.showModal({
        title: '提示',
        content: '预约成功',
        showCancel: false,
         success: function (res) {
          if (res.confirm) {
            wx.redirectTo({
              url: '/pages/detail/detail'
            })
          }
        }
      })  
    }
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