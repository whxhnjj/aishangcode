//获取数据函数
var getData = function (that) {
  
  wx.showLoading({ title: '加载中' });
  wx.request({
    url: 'https://womendehunli.com/api/feedback/faqinfo',
    data: {
    },

    success: function (res) {
      console.log(res.data)
      if (res.data.code > 0) {
        that.setData({ helps: res.data.data });
        wx.hideLoading();
      } else {
        wx.showToast({ title: '数据加载失败，刷新试试。' });
      }
    },
    fail: function (res) {
      wx.showToast({ title: '数据加载失败' });
    }
  });
}



Page({

  /**
   * 页面的初始数据
   */
  data: {
    helps:[
      {show:false}
    ]
  },
  showhide: function (e) {
    var index = parseInt(e.currentTarget.dataset.index);
    var key = "helps[" + index + "].show";
    var val = this.data.helps[index].show;
    console.log(val);
    this.setData({
      [key]: !val
    })
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    getData(this);
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