

Page({


  data: {
    hostName: core.baseUrl,

  },

  
  onLoad: function (e) {
    let that = this;
    that.setData({
      url:e.url
    });
  },


  onShow: function () {

  }


})