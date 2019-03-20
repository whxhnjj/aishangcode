// pages/detail/detail.js
const app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
    nullHouse: true, 
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    var that = this
    //调用应用实例的方法获取全局数据
    app.getUserInfo(function (userInfo) {
      //更新数据
      that.setData({
        userInfo: userInfo
      })
    })
  },
  //弹出浮层
  vote:function(){
    var that = this;
    this.setData({
      nullHouse: false, //弹窗显示
    })
  },
  //跳转送礼物
  vote2: function () {
    wx.navigateTo({
      url: '/pages/pay/pay'
    })
  },
   //关闭浮层
  colse:function(){
    var that = this;
    this.setData({
      nullHouse: true, //弹窗显示
    })
  },
  Home: function () {
    wx.switchTab({
      url: '../index/index'
    })
  },


  giving: function () {
    wx.navigateTo({
      url: '/pages/pay/pay'
    })

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