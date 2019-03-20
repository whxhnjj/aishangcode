// pages/pay/pay.js
const app = getApp()
Page({
  data: {

      catalogs: [
      {
        "imgurl":"/pages/images/paoche.png",
        "catalogName": "跑车",
        "num":"5票",
        "select": 1
      },
      {
        "imgurl": "/pages/images/meigui.png",
        "catalogName": "奶糖",
        "num": "10票",
        "select": 2
      },
      {
        "imgurl": "/pages/images/baoshi.png",
        "catalogName": "美女",
        "num": "50票",
        "select": 3
      },
      {
        "imgurl": "/pages/images/meigui.png",
        "catalogName": "棒棒糖",
        "num": "100票",
        "select": 4
      },
      {
        "imgurl": "/pages/images/baoshi.png",
        "catalogName": "美女",
        "num": "50票",
        "select": 5
      },
      {
        "imgurl": "/pages/images/meigui.png",
        "catalogName": "棒棒糖",
        "num": "100票",
        "select":6
      },
    ],
    catalogSelect: 1,//判断是否选中
      
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


  chooseCatalog: function (data) {
    var that = this;
    that.setData({//把选中值放入判断值
      catalogSelect: data.currentTarget.dataset.select
    })
  },
  payUrl:function(){
    wx.navigateTo({
      url: '/pages/detail/detail'
    })
  },
  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
  
  }
})