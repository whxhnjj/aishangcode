
var app = getApp();


Page({

  data: {
    pic:[
      'https://webimage.cn/pc/assets/images/card/banner1.jpg',
      'https://webimage.cn/pc/assets/images/card/banner2.jpg',
      'https://webimage.cn/pc/assets/images/card/banner3.jpg'
    ]
  },

  onLoad: function (e) {
    var that = this;
    that.setData({
      isIphoneX: app.globalData.isIphoneX
    })
  },

  //拨打电话
  callNum: function (e) {
    let that = this;
    wx.makePhoneCall({
      phoneNumber: e.target.dataset.msg
    })
  },

  //打开地图
  openMap: function (e) {
    let that = this;
    var latlng = e.target.dataset.latlng.split(',');
    wx.openLocation({
      latitude: parseFloat(latlng[0]),
      longitude: parseFloat(latlng[1])
    })
  },

  
  onShareAppMessage: function () {
    return {
      title: '微智创想小程序官网'
    }
  }
})